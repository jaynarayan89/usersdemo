<?php
namespace App\Core;
use App\Core\ApiToken;
use App\User\User;
class APiController
{
    protected $userDAO;
    protected $apiToken;

    public function __construct()
    {
        $this->userDAO = new User();
        $this->apiToken = new ApiToken();
    }
    protected function sendJsonResponse($statusCode, $data)
    {
        header('Content-Type: application/json');
        http_response_code($statusCode);
        echo json_encode($data);
        exit;
    }

    protected function getTokenFromHeader()
    {
        $authorizationHeader = $_SERVER['HTTP_AUTHORIZATION'] ?? '';

        // Extract the token part from the Authorization header
        $tokenParts = explode(' ', $authorizationHeader);

        return isset($tokenParts[1]) ? $tokenParts[1] : null;
    }

    protected function authenicate()
    {
        try {
            $this->apiToken = new ApiToken();
            // Get the token from the Authorization header
            $token = $this->getTokenFromHeader();

            if (empty($token)) {
                // Token is missinn
                throw new \Exception('Invalid empty token');
            }

            // Verify the token and get user data
            $userData = $this->apiToken->verifyToken($token);

            if (empty($userData)) {

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
