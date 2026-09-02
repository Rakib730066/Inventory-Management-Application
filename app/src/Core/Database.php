<?php

namespace App\Core;

use App\Config\AppConfig;
use PDO;
use PDOException;

class Database
{
    private static $pdo = null;

    public static function getConnection(): PDO
    {
        if (self::$pdo !== null) {
            return self::$pdo;
        }

        $dsn = 'mysql:host=' . AppConfig::DB_HOST .
            ';port=' . AppConfig::DB_PORT .
            ';dbname=' . AppConfig::DB_NAME .
            ';charset=' . AppConfig::DB_CHARSET;

        try {
            self::$pdo = new PDO(
                $dsn,
                AppConfig::DB_USER,
                AppConfig::DB_PASS,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false
                ]
            );
        } catch (PDOException $e) {
            if (AppConfig::APP_DEBUG) {
                die('Database connection failed: ' . $e->getMessage());
            }

            http_response_code(500);
            die('Database connection failed.');
        }

        return self::$pdo;
    }
}