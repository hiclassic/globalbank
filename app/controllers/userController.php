<?php
require_once __DIR__ . '/../core/database.php';
require_once __DIR__ . '/../models/user.php';

class UserController {
    public function register($name, $email, $password) {
        $user = new User();
        return $user->register($name, $email, $password);
    }

    public function login($email, $password) {
        return Auth::login($email, $password);
    }
}
