<?php
namespace App\Controllers;

use App\Core\Auth;
use App\Core\Request;
use App\Core\JsonResponse;
use App\Core\Validator;
use App\Models\User;

class AuthController
{
    public function showLogin(): void
    {
        view('auth.login', ['title' => 'Entrar']);
    }

    public function showRegister(): void
    {
        view('auth.register', ['title' => 'Criar conta']);
    }

    public function register(): void
    {
        verify_csrf();

        $request = new Request();
        $data    = $request->all();

        $validator = new Validator($data);
        $validator->setLabels([
            'name'                  => 'Nome',
            'email'                 => 'Email',
            'password'              => 'Senha',
            'password_confirmation' => 'Confirmação',
        ]);

        $ok = $validator->validate([
            'name'                  => 'required|min:3|max:100',
            'email'                 => 'required|email',
            'password'              => 'required|min:6',
            'password_confirmation' => 'required|match:password',
        ]);

        if (!$ok) {
            JsonResponse::error('Verifique os dados.', 422, $validator->getErrors());
        }

        $email     = strtolower(trim((string) ($data['email'] ?? '')));
        $userModel = new User();

        if ($userModel->findByEmail($email) !== null) {
            JsonResponse::error('Este email já está registrado.', 422, [
                'email' => 'Email já registrado.',
            ]);
        }

        $data['email']    = $email;
        $data['username'] = $this->makeUsername($userModel, (string) $data['name']);
        $data['password'] = password_hash((string) $data['password'], PASSWORD_DEFAULT);

        $user = $userModel->create($data);

        if ($user === null) {
            JsonResponse::error('Não foi possível criar a conta.', 500);
        }

        Auth::login((int) $user['id']);
        JsonResponse::success('Conta criada com sucesso.', ['redirect' => url('mygames')]);
    }


    public function myadmin(): void
    {
        $userModel = new User();

        // ----------------------------------------------------------
        // Trava: se já existe QUALQUER admin no sistema, bloqueia
        // ----------------------------------------------------------
        $existingAdmin = $userModel->findOneBy(['role' => 'admin']);

        if ($existingAdmin !== null) {
            http_response_code(403);
            header('Content-Type: text/plain; charset=utf-8');
            echo 'Rota bloqueada: já existe um administrador no sistema.';
            return;
        }

        // ----------------------------------------------------------
        // Cria o primeiro admin
        // ----------------------------------------------------------
        $email    = 'admin@gmail.com';
        $password = $_ENV['ADMIN_PASSWORD']
            ?? getenv('ADMIN_PASSWORD')
            ?: 'admin123agidev';

        // Edge case: email já cadastrado (sem ser admin) → promove
        $existing = $userModel->findByEmail($email);

        if ($existing !== null) {
            $userModel->update((int) $existing['id'], [
                'password' => password_hash($password, PASSWORD_DEFAULT),
                'role'     => 'admin',
            ]);

            header('Content-Type: text/plain; charset=utf-8');
            echo 'Admin promovido: ' . htmlspecialchars($email);
            return;
        }

        // Cria do zero (SQL puro)
        $db   = getDbConnection();
        $hash = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO `users`
                (`name`, `username`, `email`, `password`, `role`)
                VALUES (:name, :username, :email, :password, :role)";

        $stmt = $db->prepare($sql);
        $stmt->execute([
            ':name'     => 'Administrador',
            ':username' => 'admin',
            ':email'    => $email,
            ':password' => $hash,
            ':role'     => 'admin',
        ]);

        header('Content-Type: text/plain; charset=utf-8');
        echo 'Admin criado: ' . htmlspecialchars($email);
    }

    public function login(): void
    {
        verify_csrf();

        $request = new Request();
        $data    = $request->all();

        $validator = new Validator($data);
        $validator->setLabels([
            'email'    => 'Email',
            'password' => 'Senha',
        ]);

       $ok = $validator->validate([
            'email'                 => 'required|email',
            'password'              => 'required|min:6'
        ]);


        if (!$ok) {
            JsonResponse::error('Verifique os dados.', 422, $validator->getErrors());
        }

        $email = strtolower(trim((string) ($data['email'] ?? '')));

        if (!Auth::attempt($email, (string) $data['password'])) {
            JsonResponse::error('Email ou senha incorretos.', 401);
        }
        JsonResponse::success('Bem-vindo de volta!', ['redirect' => url('mygames')]);
    }

    public function logout(): void
    {
        Auth::logout();

        if (is_api_request()) {
            JsonResponse::success('Sessão encerrada.');
        }

        redirect('/');
        exit;
    }

    /**
     * Gera um username único a partir do nome.
     */
    private function makeUsername(User $userModel, string $name): string
    {
        $base = strtolower(trim($name));
        $base = preg_replace('/[^a-z0-9]+/', '_', iconv('UTF-8', 'ASCII//TRANSLIT', $base));
        $base = trim($base, '_') ?: 'user';
        $base = substr($base, 0, 20);

        $username = $base;
        $i = 1;

        while ($userModel->findByUsername($username) !== null) {
            $suffix   = '_' . $i++;
            $username = substr($base, 0, 20 - strlen($suffix)) . $suffix;
        }

        return $username;
    }
}
