<?php 
// Common header for all pages
if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? $pageTitle : 'Budget Supermarket'; ?></title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body class="main-page">
    <div class="header">
        <div class="supermarket-name">Budget Supermarket</div>
        <div class="nav-buttons">
            <?php if ($_SESSION['role'] == 'admin'): ?>
                <a href="../admin/dashboard.php" class="nav-btn <?php echo basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : ''; ?>">Dashboard</a>
                <a href="../admin/users.php" class="nav-btn <?php echo basename($_SERVER['PHP_SELF']) == 'users.php' ? 'active' : ''; ?>">Users</a>
                <a href="../admin/reports.php" class="nav-btn <?php echo basename($_SERVER['PHP_SELF']) == 'reports.php' ? 'active' : ''; ?>">Reports</a>
                <a href="../admin/goods.php" class="nav-btn <?php echo basename($_SERVER['PHP_SELF']) == 'goods.php' ? 'active' : ''; ?>">Inventory</a>
            <?php elseif ($_SESSION['role'] == 'cashier'): ?>
                <a href="../cashier/dashboard.php" class="nav-btn <?php echo basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : ''; ?>">Dashboard</a>
                <a href="../cashier/sales.php" class="nav-btn <?php echo basename($_SERVER['PHP_SELF']) == 'sales.php' ? 'active' : ''; ?>">Sales</a>
                <a href="../cashier/inventory.php" class="nav-btn <?php echo basename($_SERVER['PHP_SELF']) == 'inventory.php' ? 'active' : ''; ?>">Inventory</a>
            <?php elseif ($_SESSION['role'] == 'receptionist'): ?>
                <a href="../receptionist/dashboard.php" class="nav-btn <?php echo basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : ''; ?>">Dashboard</a>
                <a href="../receptionist/goods.php" class="nav-btn <?php echo basename($_SERVER['PHP_SELF']) == 'goods.php' ? 'active' : ''; ?>">Goods</a>
                <a href="../receptionist/add-goods.php" class="nav-btn <?php echo basename($_SERVER['PHP_SELF']) == 'add-goods.php' ? 'active' : ''; ?>">Add Goods</a>
                <a href="../receptionist/stock-history.php" class="nav-btn <?php echo basename($_SERVER['PHP_SELF']) == 'stock-history.php' ? 'active' : ''; ?>">Stock History</a>
            <?php endif; ?>
        </div>
        <div class="user-welcome">
            <span>Welcome <?php echo htmlspecialchars($_SESSION['nickname']); ?> (<?php echo ucfirst($_SESSION['role']); ?>)</span>
        </div>
    </div>
    
    <div class="logout-btn-container">
        <a href="../logout.php" class="logout-btn">Log Out</a>
    </div>
    
    <div class="version">Version 1.0.0</div>
    <div class="system-title">Warehouse Goods Registration Management System</div>
    
    <div class="container">