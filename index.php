<?php
require_once 'config/database.php';
session_start();

if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}

header("Location: login.php");
exit();
