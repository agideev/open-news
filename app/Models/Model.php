<?php
namespace App\Models;

use PDO;
use InvalidArgumentException;
use Throwable;

abstract class Model
{
    protected PDO $db;
    protected string $table;
    protected string $primaryKey = 'id';
    protected array $fillable = [];

    public function __construct()
    {
        $this->db = getDbConnection();
    }

    protected function filterFillable(array $data): array
    {
        if (!empty($this->fillable)) {
            $data = array_intersect_key($data, array_flip($this->fillable));
        }
        foreach ($data as $key => $value) {
            if (is_bool($value)) {
                $data[$key] = $value ? 1 : 0;
            }
        }
        return $data;
    }

    protected function assertValidColumn(string $column): void
    {
        if (!preg_match('/^[a-zA-Z_][a-zA-Z0-9_]*$/', $column)) {
            throw new InvalidArgumentException("Invalid column name: {$column}");
        }
    }

    public function create(array $data): ?array
    {
        $data = $this->filterFillable($data);

        if (empty($data)) {
            throw new InvalidArgumentException('No fillable fields provided.');
        }

        $fields = array_keys($data);

        foreach ($fields as $field) {
            $this->assertValidColumn($field);
        }

        $columns      = implode(', ', array_map(fn($f) => "`{$f}`", $fields));
        $placeholders = ':' . implode(', :', $fields);

        $sql  = "INSERT INTO `{$this->table}` ({$columns}) VALUES ({$placeholders})";
        $stmt = $this->db->prepare($sql);

        if ($stmt->execute($data)) {
            $id = (int) $this->db->lastInsertId();
            return $this->find($id);
        }

        return null;
    }

    public function update(mixed $id, array $data): bool
    {
        $data = $this->filterFillable($data);

        if (empty($data)) {
            return false;
        }

        $fields = [];
        foreach (array_keys($data) as $field) {
            $this->assertValidColumn($field);
            $fields[] = "`{$field}` = :{$field}";
        }
        $setClause = implode(', ', $fields);

        $sql  = "UPDATE `{$this->table}` SET {$setClause} WHERE `{$this->primaryKey}` = :__pk_id";
        $stmt = $this->db->prepare($sql);

        $data['__pk_id'] = $id;

        return $stmt->execute($data);
    }

    public function delete(mixed $id): bool
    {
        $sql  = "DELETE FROM {$this->table} WHERE {$this->primaryKey} = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }

    public function find(mixed $id): ?array
    {
        if (!is_scalar($id)) return null;

        $sql  = "SELECT * FROM {$this->table} WHERE {$this->primaryKey} = :id LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function findAll(): array
    {
        $sql  = "SELECT * FROM {$this->table}";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findBy(array $conditions): array
    {
        if (empty($conditions)) {
            return $this->findAll();
        }

        $whereClauses = [];
        foreach (array_keys($conditions) as $column) {
            $this->assertValidColumn($column);
            $whereClauses[] = "{$column} = :{$column}";
        }
        $whereSql = implode(' AND ', $whereClauses);

        $sql  = "SELECT * FROM {$this->table} WHERE {$whereSql}";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($conditions);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findOneBy(array $conditions): ?array
    {
        if (empty($conditions)) {
            return null;
        }

        $whereClauses = [];
        foreach (array_keys($conditions) as $column) {
            $this->assertValidColumn($column);
            $whereClauses[] = "{$column} = :{$column}";
        }
        $whereSql = implode(' AND ', $whereClauses);

        $sql  = "SELECT * FROM {$this->table} WHERE {$whereSql} LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($conditions);

        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function exists(mixed $id): bool
    {
        if (!is_scalar($id)) return false;

        $sql  = "SELECT 1 FROM {$this->table} WHERE {$this->primaryKey} = :id LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        return (bool) $stmt->fetchColumn();
    }

    public function transaction(callable $callback): mixed
    {
        $this->db->beginTransaction();
        try {
            $result = $callback($this);
            $this->db->commit();
            return $result;
        } catch (Throwable $e) {
            $this->db->rollBack();
            throw $e;
        }
    }
}
