<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header("Location: login.html");
    exit;
}
echo "<h1>Welcome, Admin!</h1>";
?>
