<?php

namespace App\Auth;

use App\Core\APiController;

class ProfileController extends APiController
{
    public function getUserProfile()
    {
        

        try {
            // Get the token from the Authorization header
            $token = $this->getTokenFromHeader();

            if(empty($token))
            {
                // Token is missinn
                throw new \Exception('Invalid emptytoken');
            }

            // Verify the token and get user data
            $userData = $this->apiToken->verifyToken($token);

            if ($userData) {

                $user = $this->userDAO->getByUsername($userData->username);
                // Respond with the user's profile details in JSON format
                $response = [
                    'id'       => $user['id'],
                    'username' => $user['username'],
                    'email'    => $user['email'],
                    'first_name'    => $user['first_name'],
                    'last_name'    => $user['last_name']
                ];
                $this->sendJsonResponse(200, $response);
            } else {
                // Token is invalid or expired
                throw new \Exception('Invalid token');
            }
        } catch (\Exception $e) {
            // Respond with error message
            $response = ['error' => $e->getMessage()];
            $this->sendJsonResponse(401, $response);
        }
    }

  

}

