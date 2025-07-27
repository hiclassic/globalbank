<?php
require_once '../app/core/auth.php';
Auth::logout();
header("Location: index.php");
exit;
