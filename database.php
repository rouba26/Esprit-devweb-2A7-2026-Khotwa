<?php

class Database
{
    private static ?PDO $instance = null;

    private static string $host    = 'localhost';
    private static string $dbname  = 'piweb';
    private static string $user    = 'root';
    private static string $pass    = '';
    private static string $charset = 'utf8mb4';

    public static function getInstance(): PDO
    {
        if (self::$instance === null) {
            $dsn = "mysql:host=" . self::$host
                 . ";dbname=" . self::$dbname
                 . ";charset=" . self::$charset;

            self::$instance = new PDO($dsn, self::$user, self::$pass, [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ]);
        }
        return self::$instance;
    }

    private function __construct() {}
    private function __clone()     {}
}
