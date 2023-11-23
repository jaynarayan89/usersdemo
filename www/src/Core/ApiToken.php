<?php
namespace App\Core; 

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class ApiToken
{
    private $secretKey;

    public function __construct()
    {
        $this->secretKey = 'fghfh78324fsd8fa2szd*-/-/*ewt';;
    }

    public function generateToken($user)
    {
        $tokenId    = base64_encode(random_bytes(32));
        $issuedAt   = time();
        $expire     = $issuedAt + 3600;  // Expires in 1 hour
        $serverName = "localhost";

        $data = [
            'iat'  => $issuedAt,
            'jti'  => $tokenId,
            'iss'  => $serverName,
            'exp'  => $expire,
            'data' => $user
        ];
        return JWT::encode($data, $this->secretKey,'HS256');
    }

    public function verifyToken($token)
    {
        try {
            $decoded = $decoded = JWT::decode($token, new Key($this->secretKey, 'HS256'));

            return $decoded->data;
        } catch (\Exception $e) {
            // Token is invalid or expired
            return false;
        }
    }
}