<?php
namespace App\Auth;
use App\Core\APiController;

class RegistrationController extends APiController
{
    public function registerUser()
    {
        // Assume JSON payload for simplicity
        $data = json_decode(file_get_contents('php://input'), true);
        try {
            $this->validateRegistrationData($data);

            // Hash the password (you should use a secure password hashing method)
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

            // Insert user into the database
            $this->userDAO->create($data);

            // Respond with success message
            $response = ['message' => 'User registered successfully'];
            $this->sendJsonResponse(200, $response);
        } 
        catch (\PDOException $e) {
            // Check if the exception is due to a unique constraint violation
            if ($e->getCode() == '23000') {
                $errorMessage = $e->getMessage();
                
                if (preg_match("/Duplicate entry '(.*?)' for key '(.*?)'/", $errorMessage, $matches)) {
                    $columnName = explode('.',$matches[2]); 
                    
                    $this->sendJsonResponse(400,['error' => "{$columnName[1]} already exists."]);
                } else {
                    // Unable to extract details, generic error message
                    $this->sendJsonResponse(400,['error' => 'Unique constraint violation']);
                }
            } else {
                // Handle other types of PDO exceptions if needed
                $this->sendJsonResponse(400,['error' => $e->getMessage()]);
            }
        }
        catch (\Exception $e) {
            // Respond with error message
            $response = ['error' => $e->getMessage()];
            $this->sendJsonResponse(400, $response);
        }
    }

    private function validateRegistrationData($data)
    {
        // Basic validation
        if (empty($data['username']) || empty($data['email']) || empty($data['password'])) {
            throw new \Exception('Username, email, and password are required fields.');
        }

    }

}
