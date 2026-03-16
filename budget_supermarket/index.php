<?php 
include 'includes/config.php';

// Redirect logged in users to their dashboard
if (isset($_SESSION['user_id'])) {
    header("Location: ".$_SESSION['role']."/dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Budget Supermarket - Login</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body style="background-image: url('assets/images/login-bg.jpg'); 
             background-size: cover;
             background-position: center;
             background-attachment: fixed;">
    <div class="auth-container">
        <div class="auth-tabs">
            <button class="tab-btn active" onclick="openTab('login')">Login</button>
            <button class="tab-btn" onclick="openTab('signup')">Sign Up</button>
        </div>
        
        <?php if (isset($_SESSION['error'])): ?>
            <div class="error"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['message'])): ?>
            <div class="message"><?php echo $_SESSION['message']; unset($_SESSION['message']); ?></div>
        <?php endif; ?>
        
        <div id="login" class="auth-form active">
            <h2>Login to Your Account</h2>
            <form action="includes/auth.php" method="post">
                <input type="hidden" name="action" value="login">
                <div class="form-group">
                    <label for="login-username">Username:</label>
                    <input type="text" id="login-username" name="username" required>
                </div>
                <div class="form-group">
                    <label for="login-password">Password:</label>
                    <input type="password" id="login-password" name="password" required>
                </div>
                <button type="submit" class="auth-btn">Login</button>
            </form>
        </div>
        
        <div id="signup" class="auth-form">
            <h2>Create New Account</h2>
            <form action="includes/auth.php" method="post">
                <input type="hidden" name="action" value="signup">
                <div class="form-group">
                    <label for="signup-username">Username:</label>
                    <input type="text" id="signup-username" name="username" required>
                </div>
                <div class="form-group">
                    <label for="signup-nickname">Nickname:</label>
                    <input type="text" id="signup-nickname" name="nickname" required>
                </div>
                <div class="form-group">
                    <label for="signup-password">Password:</label>
                    <input type="password" id="signup-password" name="password" required>
                </div>
                <button type="submit" class="auth-btn">Sign Up</button>
            </form>
        </div>
    </div>
    <script src="assets/js/script.js"></script>
</body>
</html>