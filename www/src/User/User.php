<?php
// namespace Jaynarayan89\Jdrive;
namespace App\User;

use App\Core\Database;

class User
{
     private $db;

    // Constructor
    public function __construct()
    {
        $this->db = new Database();
    }
    public function create($userData)
    {
        // Example: Insert a new user into the database
        $query = "INSERT INTO users (username, email, password, first_name, last_name) VALUES (?, ?, ?, ?, ?)";
        $params = [
            $userData["username"],
            $userData["email"],
            $userData["password"],
            $userData["first_name"],
            $userData["last_name"],
        ];

        $this->db->executeQuery($query, $params);

        // You might want to return the newly created user ID or a success message
    }

    public function getByUsername($username)
    {
        // Example: Retrieve user by username from the database
        $query = "SELECT * FROM users WHERE username = ?";
        $params = [$username];

        $result = $this->db->executeQuery($query, $params);

        // Check if a user with the given username was found
        if ($result && $result->rowCount() > 0) {
            // Fetch the user data
            $userData = $result->fetch(\PDO::FETCH_ASSOC);

            // You might want to return the user data or create a User object and return that
            return $userData;
        } else {
            // No user found with the given username
            return null;
        }
    }
}
