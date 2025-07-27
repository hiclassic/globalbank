<?php
require_once '../app/Core/Database.php';
require_once '../app/Core/Auth.php';
require_once '../app/Controllers/UserController.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['register'])) {
        $uc = new UserController();
        $uc->register($_POST['name'], $_POST['email'], $_POST['password']);
    } elseif (isset($_POST['login'])) {
        $uc = new UserController();
        if ($uc->login($_POST['email'], $_POST['password'])) {
            header("Location: dashboard.php");
            exit;
        } else {
            echo "Login failed!";
        }
    }
}
?>

<form method="POST">
    <h3>Register</h3>
    <input name="name" placeholder="Name" required><br>
    <input name="email" placeholder="Email" required><br>
    <input name="password" type="password" placeholder="Password" required><br>
    <button name="register" type="submit">Register</button>
</form>

<form method="POST">
    <h3>Login</h3>
    <input name="email" placeholder="Email" required><br>
    <input name="password" type="password" placeholder="Password" required><br>
    <button name="login" type="submit">Login</button>
</form>
<?php
if (isset($_SESSION['user'])) {
    echo "Welcome, " . $_SESSION['user']['name'] . "!";
    echo "<a href='logout.php'>Logout</a>";
} else {
    echo "<a href='login.php'>Login</a>";
}
?>