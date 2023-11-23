<?php
return [
    '/auth/register' => ['\App\Auth\RegistrationController', 'registerUser'],
    '/auth/login' => ['\App\Auth\LoginController', 'loginUser'],
    '/auth/profile' => ['\App\Auth\ProfileController', 'getUserProfile'],
    '/document/upload' => ['\App\Document\DocumentController', 'upload'],
];
