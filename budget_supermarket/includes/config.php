<?php
session_start();

$host = 'localhost';
$dbname = 'budget_supermarket';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Could not connect to the database: " . $e->getMessage());
}

// Redirect if not logged in or wrong role
function checkRole($requiredRole) {
    if (!isset($_SESSION['user_id'])) {
        header("Location: ../index.php");
        exit();
    }
    if ($_SESSION['role'] != $requiredRole && $_SESSION['role'] != 'admin') {
        header("Location: ../".$_SESSION['role']."/dashboard.php");
        exit();
    }
}
?>