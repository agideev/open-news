CREATE DATABASE IF NOT EXISTS open_news_db
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE open_news_db;


-- USERS
CREATE TABLE users (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    username VARCHAR(60) NOT NULL,
    email VARCHAR(150) NOT NULL,
    password VARCHAR(255) NOT NULL,
    avatar VARCHAR(255) DEFAULT NULL,
    bio TEXT DEFAULT NULL,
    whatsapp VARCHAR(30) DEFAULT NULL,
    role ENUM('user', 'admin') NOT NULL DEFAULT 'user',

    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,

    UNIQUE KEY uniq_username (username),
    UNIQUE KEY uniq_email (email)
) ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_unicode_ci;


-- NEWS
CREATE TABLE news (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(180) NOT NULL,
    slug VARCHAR(200) NOT NULL,
    summary TEXT DEFAULT NULL,
    content LONGTEXT NOT NULL,
    image VARCHAR(255) DEFAULT NULL,
    audio VARCHAR(255) DEFAULT NULL,

    status ENUM('draft', 'published') NOT NULL DEFAULT 'draft',
    published_at DATETIME DEFAULT NULL,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,

    UNIQUE KEY uniq_news_slug (slug)
) ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_unicode_ci;


-- NEWS IMAGES
CREATE TABLE news_images (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    news_id INT UNSIGNED NOT NULL,
    image VARCHAR(255) NOT NULL,
    caption VARCHAR(255) DEFAULT NULL,
    position INT UNSIGNED NOT NULL DEFAULT 0,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_news_images_news
        FOREIGN KEY (news_id)
        REFERENCES news(id)
        ON DELETE CASCADE
) ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_unicode_ci;


-- NEWS FAVORITES
CREATE TABLE news_favorites (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    news_id INT UNSIGNED NOT NULL,
    user_id INT UNSIGNED NOT NULL,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    UNIQUE KEY uniq_news_user (news_id, user_id),

    CONSTRAINT fk_news_favorites_news
        FOREIGN KEY (news_id)
        REFERENCES news(id)
        ON DELETE CASCADE,

    CONSTRAINT fk_news_favorites_user
        FOREIGN KEY (user_id)
        REFERENCES users(id)
        ON DELETE CASCADE
) ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_unicode_ci;


CREATE TABLE ai_usage (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED NOT NULL,
    usage_date DATE NOT NULL,
    credits_used INT UNSIGNED NOT NULL DEFAULT 0,
    daily_limit INT UNSIGNED NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    UNIQUE KEY uniq_user_date (user_id, usage_date),

    CONSTRAINT fk_ai_usage_user
        FOREIGN KEY (user_id)
        REFERENCES users(id)
        ON DELETE CASCADE
);
