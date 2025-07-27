<?php
require_once '../app/Core/Auth.php';

if (!Auth::check()) {
    header("Location: index.php");
    exit;
}

echo "Welcome to GlobalBank Dashboard!";
echo "<br><a href='logout.php'>Logout</a>";
