<?php 
include '../includes/config.php';
checkRole('cashier');

// Get today's sales
$stmt = $pdo->prepare("SELECT COUNT(*) as count, SUM(s.quantity * s.price) as total 
                      FROM sales s 
                      WHERE DATE(s.sold_at) = CURDATE() AND s.sold_by = ?");
$stmt->execute([$_SESSION['user_id']]);
$salesData = $stmt->fetch();

// Get low stock items
$stmt = $pdo->query("SELECT name, quantity FROM goods WHERE quantity < 10 ORDER BY quantity LIMIT 5");
$lowStock = $stmt->fetchAll();
?>

<?php $pageTitle = "Cashier Dashboard"; include '../includes/header.php'; ?>

<h1>Cashier Dashboard</h1>

<div class="stats-container">
    <div class="stat-card">
        <h3>Today's Sales</h3>
        <p><?php echo $salesData['count']; ?></p>
    </div>
    <div class="stat-card">
        <h3>Today's Revenue</h3>
        <p>ksh.<?php echo number_format($salesData['total'] ?? 0, 2); ?></p>
    </div>
    <div class="stat-card">
        <h3>Low Stock Items</h3>
        <p><?php echo count($lowStock); ?></p>
    </div>
</div>

<div class="quick-actions">
    <a href="sales.php" class="quick-action-btn">
        <h3>Process Sale</h3>
        <p>Record new sales transactions</p>
    </a>
    <a href="inventory.php" class="quick-action-btn">
        <h3>View Inventory</h3>
        <p>Check product availability</p>
    </a>
</div>

<?php if (!empty($lowStock)): ?>
<div class="low-stock-warning">
    <h3>Low Stock Items</h3>
    <ul>
        <?php foreach ($lowStock as $item): ?>
        <li><?php echo htmlspecialchars($item['name']); ?> (<?php echo $item['quantity']; ?> left)</li>
        <?php endforeach; ?>
    </ul>
</div>
<?php endif; ?>

<?php include '../includes/footer.php'; ?>