<?php
session_start();
include 'config.php';

// Debugging: Show all errors
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if ($_POST['action'] == 'signup') {
        // Only admin can create accounts
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
            $_SESSION['error'] = "Only admin can create accounts!";
            header("Location: ../index.php");
            exit();
        }
        
        $username = trim($_POST['username']);
        $nickname = trim($_POST['nickname']);
        $password = trim($_POST['password']);
        $role = $_POST['role'];
        
        // Debug output
        echo "Signup attempt - Username: $username, Password: $password<br>";
        
        // Validate inputs
        if (empty($username) || empty($nickname) || empty($password)) {
            $_SESSION['error'] = "All fields are required!";
            header("Location: users.php");
            exit();
        }

        if (strlen($password) < 8) {
            $_SESSION['error'] = "Password must be at least 8 characters!";
            header("Location: users.php");
            exit();
        }

        // Hash the password with cost 12
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
        
        // Debug output
        echo "Hashed password: $hashedPassword<br>";
        
        try {
            $stmt = $pdo->prepare("INSERT INTO users (username, nickname, password, role) VALUES (?, ?, ?, ?)");
            $stmt->execute([$username, $nickname, $hashedPassword, $role]);
            $_SESSION['message'] = "Account created successfully!";
            header("Location: users.php");
            exit();
        } catch (PDOException $e) {
            $_SESSION['error'] = "Username already exists!";
            header("Location: users.php");
            exit();
        }
    } elseif ($_POST['action'] == 'login') {
        $username = trim($_POST['username']);
        $password = trim($_POST['password']);
        
        // Debug output
        echo "Login attempt - Username: $username, Password: $password<br>";
        
        // Validate inputs
        if (empty($username) || empty($password)) {
            $_SESSION['error'] = "Username and password are required!";
            header("Location: ../index.php");
            exit();
        }

        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();
        
        // Debug output
        echo "User found: " . print_r($user, true) . "<br>";
        
        if ($user) {
            // Debug output
            echo "Stored hash: " . $user['password'] . "<br>";
            echo "Password verify result: " . (password_verify($password, $user['password']) ? 'true' : 'false') . "<br>";
            
            if (password_verify($password, $user['password'])) {
                // Check if a newer hashing algorithm should be used
                if (password_needs_rehash($user['password'], PASSWORD_BCRYPT, ['cost' => 12])) {
                    $newHash = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
                    $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
                    $stmt->execute([$newHash, $user['id']]);
                }
                
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['nickname'] = $user['nickname'];
                $_SESSION['role'] = $user['role'];
                
                // Debug output
                echo "Login successful! Redirecting to dashboard...<br>";
                
                // Redirect to appropriate dashboard
                header("Location: ../".$user['role']."/dashboard.php");
                exit();
            }
        }
        
        // If we get here, login failed
        $_SESSION['error'] = "Invalid username or password!";
        header("Location: ../index.php");
        exit();
    }
}

// If debugging, you might want to see output before exit
die("Script completed");
?>