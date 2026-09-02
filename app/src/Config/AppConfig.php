<?php

namespace App\Config;

final class AppConfig
{
    public const APP_NAME = 'Inventory Management System';
    public const APP_ENV = 'development';
    public const APP_DEBUG = true;
    public const APP_URL = 'http://localhost';

    public const DB_HOST = 'mysql';
    public const DB_PORT = '3306';
    public const DB_NAME = 'inventory_management';
    public const DB_USER = 'root';
    public const DB_PASS = 'secret123';
    public const DB_CHARSET = 'utf8mb4';

    public const SESSION_NAME = 'inventory_app_session';
    public const LOW_STOCK_THRESHOLD = 5;

    private function __construct()
    {
    }
}