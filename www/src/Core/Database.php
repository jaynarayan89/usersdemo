<?php
namespace App\Core; 
class Database
{
    private $db;

    public function __construct()
    {
        $DBuser = $_ENV['MYSQL_USER'];
        $DBpass = $_ENV['MYSQL_PASSWORD'];
        $DBname = $_ENV['MYSQL_DATABASE'];
        $dsn = "mysql:host=database:3306;dbname=$DBname";
        try {
            $this->db =  new \PDO($dsn, $DBuser, $DBpass);
            $this->db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        } catch (\PDOException $e) {
            die('Connection failed: ' . $e->getMessage());
        }
    }

    public function executeQuery($query, $params = [])
    {
        $stmt = $this->db->prepare($query);

        // Bind parameters
        $paramCount = count($params);
        for ($i = 0; $i < $paramCount; $i++) {
            $stmt->bindValue($i + 1, $params[$i]);
        }

        // Execute the query
        $stmt->execute();

        return $stmt;
    }
}