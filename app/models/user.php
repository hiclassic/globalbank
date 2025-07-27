
<?php
public function register($name, $email, $password, $role = 'customer') {
    $db = Database::getInstance();
    $stmt = $db->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
    $hash = password_hash($password, PASSWORD_BCRYPT);
    $stmt->execute([$name, $email, $hash, $role]);
    return $db->lastInsertId();
}