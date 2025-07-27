<?php
require_once __DIR__ . '/../Core/Database.php';
require_once __DIR__ . '/../Models/User.php';

class UserController {
    public function register($name, $email, $password) {
        $user = new User();
        return $user->register($name, $email, $password);
    }

    public function login($email, $password) {
        return Auth::login($email, $password);
    }
}
