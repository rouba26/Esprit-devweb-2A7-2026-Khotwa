<?php
namespace App\Models;

abstract class Model
{
    private static ?\PDO $pdo = null;

    protected static function db(): \PDO
    {
        if (self::$pdo === null) {
            $c = require __DIR__ . '/../../config.php';
            self::$pdo = new \PDO($c['dsn'], $c['user'], $c['pass'], [
                \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
                \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
            ]);
        }
        return self::$pdo;
    }
}
