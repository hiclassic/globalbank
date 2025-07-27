<?php

require_once '../vendor/autoload.php';
require_once '../app/Core/Router.php';
require_once '../app/Core/Token.php';
require_once '../app/Controllers/UserController.php';
require_once '../app/Controllers/AccountController.php';
require_once '../app/Controllers/ReportController.php';

header('Content-Type: application/json');

$router = new Router();

// Example Public Login Route
$router->add('POST', '/login', function () {
    $input = json_decode(file_get_contents('php://input'), true);
    $uc = new UserController();

    if ($uc->login($input['email'], $input['password'])) {
        $token = Token::generate(['email' => $input['email']]);
        echo json_encode(['token' => $token]);
    } else {
        echo json_encode(['error' => 'Invalid credentials']);
    }
});

// Example Protected Route
$router->add('GET', '/accounts', function () {
    Token::verify(); // middleware-like guard

    $ac = new AccountController();
    $result = $ac->getAccounts($_GET['user_id']);
    echo json_encode($result);
});

// Dispatch
$method = $_SERVER['REQUEST_METHOD'];
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$router->dispatch($method, $path);
