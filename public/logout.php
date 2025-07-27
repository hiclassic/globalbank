<?php
require_once '../app/Core/Auth.php';
Auth::logout();
header("Location: index.php");
exit;
