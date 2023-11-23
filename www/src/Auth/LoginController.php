<?php
namespace App\Auth;
use App\Core\APiController;
class LoginController extends APiController
{
    public function loginUser()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        
        try {
            $this->validateLoginData($data);

            $user = $this->userDAO->getByUsername($data['username']);

            if ($user && password_verify($data['password'], $user['password'])) {

                // Assuming user authentication was successful
                $user = ['id' => $user['id'], 'username' => $user['username'], 'email' => $user['username']];
                $token = $this->apiToken->generateToken($user);
                
                // Respond with success message and token
                $response = ['message' => 'Login successful', 'token' => $token];
                $this->sendJsonResponse(200, $response);

            } else {
                // Invalid credentials
                throw new \Exception('Invalid username or password');
            }
        } catch (\Exception $e) {
            // Respond with error message
            $response = ['error' => $e->getMessage()];
            $this->sendJsonResponse(401, $response);
        }
    }

    private function validateLoginData($data)
    {
        // Basic validation
        if (empty($data['username']) || empty($data['password'])) {
            throw new \Exception('Username and password are required fields.');
        }
    }

}
