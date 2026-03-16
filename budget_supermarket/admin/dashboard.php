<?php 
include '../includes/config.php';
checkRole('admin');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body class="main-page">
    <?php include '../includes/header.php'; ?>
    
    <div class="dashboard-container">
        <h1>Admin Dashboard</h1>
        
        <div class="stats-container">
            <div class="stat-card">
                <h3>Total Goods</h3>
                <p><?php 
                    $stmt = $pdo->query("SELECT COUNT(*) as total FROM goods");
                    echo $stmt->fetch()['total'];
                ?></p>
            </div>
            <div class="stat-card">
                <h3>Today's Sales</h3>
                <p>ksh.<?php 
                    $stmt = $pdo->query("SELECT SUM(quantity * price) as total FROM sales 
                                       WHERE DATE(sold_at) = CURDATE()");
                    echo number_format($stmt->fetch()['total'] ?? 0, 2);
                ?></p>
            </div>
            <div class="stat-card">
                <h3>Low Stock</h3>
                <p><?php 
                    $stmt = $pdo->query("SELECT COUNT(*) as total FROM goods WHERE quantity < 10");
                    echo $stmt->fetch()['total'];
                ?></p>
            </div>
        </div>
        
        <div class="quick-links">
            <a href="users.php" class="quick-link">Manage Users</a>
            <a href="reports.php" class="quick-link">View Reports</a>
            <a href="goods.php" class="quick-link">Inventory</a>
        </div>
    </div>
</body>
</html>