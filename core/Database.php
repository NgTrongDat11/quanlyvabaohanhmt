<?php
/**
 * Database Connection - Singleton Pattern
 */

class Database
{
    private static $instance = null;
    private $connection;

    private function __construct()
    {
        $config = require ROOT_PATH . '/config/database.php';
        
        try {
            $dsn = "mysql:host={$config['host']};dbname={$config['database']};charset={$config['charset']}";
            $this->connection = new PDO($dsn, $config['username'], $config['password'], $config['options']);
        } catch (PDOException $e) {
            die("Kết nối database thất bại: " . $e->getMessage());
        }
    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection()
    {
        return $this->connection;
    }

    // Ngăn clone
    private function __clone() {}
    
    // Ngăn unserialize
    public function __wakeup() {}
}
