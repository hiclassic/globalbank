<?php

require_once '../vendor/autoload.php';
require_once '../app/Core/Database.php';
require_once '../app/Core/Auth.php';
require_once '../app/Controllers/UserController.php';

header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];
$uri = $_GET['endpoint'] ?? '';

if ($method === 'POST' && $uri === 'login') {
    $input = json_decode(file_get_contents('php://input'), true);
    $uc = new UserController();
    if ($uc->login($input['email'], $input['password'])) {
        $token = Token::generate(['email' => $input['email']]);
        echo json_encode(['token' => $token]);
    } else {
        echo json_encode(['error' => 'Invalid']);
    }
}

?>