<?php

class Database {
    private static ?Database $instance = null;
    private PDO $connection;

    private function __construct() {
        $DATABASE_CONFIGURATION_FILE = __DIR__ . '/../../config/database.ini';

        // Documentation : https://www.php.net/manual/fr/function.parse-ini-file.php
        $config = parse_ini_file($DATABASE_CONFIGURATION_FILE, true);

        if (!$config) {
            throw new Exception("Erreur lors de la lecture du fichier de configuration : " . $DATABASE_CONFIGURATION_FILE);
        }

        $host = $config['host'];
        $port = $config['port'];
        $database = $config['database'];
        $username = $config['username'];
        $password = $config['password'];

        // Documentation :
        //   - https://www.php.net/manual/fr/pdo.connections.php
        //   - https://www.php.net/manual/fr/ref.pdo-mysql.connection.php
        $this->connection = new PDO("mysql:host=$host;port=$port;dbname=$database;charset=utf8mb4", $username, $password);
    }

    public static function getInstance(): self {
        if (!isset(self::$instance)) {
            self::$instance = new self;
        }

        return self::$instance;
    }

    public function getConnection(): PDO {
        return $this->connection;
    }
}
