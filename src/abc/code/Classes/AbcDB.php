<?php

namespace Azt3k\SS\Classes;

use PDO;
use SilverStripe\Core\Environment;

class AbcDB extends PDO
{
    protected static ?self $instance = null;

    public function __construct(?string $dsn = null, ?string $username = null, ?string $password = null, ?array $driver_options = null)
    {
        if (!$dsn) {
            $server = Environment::getEnv('SS_DATABASE_SERVER') ?: 'localhost';
            $dbName = Environment::getEnv('SS_DATABASE_NAME') ?: '';
            $dsn = 'mysql:host=' . $server . ';dbname=' . $dbName;
        }

        if (!$username) {
            $username = Environment::getEnv('SS_DATABASE_USERNAME') ?: 'root';
        }

        if (!$password) {
            $password = Environment::getEnv('SS_DATABASE_PASSWORD') ?: '';
        }

        parent::__construct($dsn, $username, $password, $driver_options);
    }

    public static function getInstance(): self
    {
        if (empty(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }
}
