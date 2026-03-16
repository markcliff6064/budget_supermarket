<?php 
include '../includes/config.php';
checkRole('receptionist');

// Get goods count
$stmt = $pdo->query("SELECT COUNT(*) as total_goods FROM goods");
$total_goods = $stmt->fetch()['total_goods'];

// Get low stock items (less than 10)
$stmt = $pdo->query("SELECT COUNT(*) as low_stock FROM goods WHERE quantity < 10");
$low_stock = $stmt->fetch()['low_stock'];

// Get total stock value
$stmt = $pdo->query("SELECT SUM(quantity * price) as total_value FROM goods");
$total_value = $stmt->fetch()['total_value'] ?? 0;

// Get recent activities
$stmt = $pdo->query("SELECT g.name, g.added_at, u.nickname 
                     FROM goods g JOIN users u ON g.added_by = u.id 
                     ORDER BY g.added_at DESC LIMIT 5");
$activities = $stmt->fetchAll();
?>

<?php $pageTitle = "Receptionist Dashboard"; include '../includes/header.php'; ?>

<h1>Receptionist Dashboard</h1>

<div class="stats-container">
    <div class="stat-card">
        <h3>Total Goods</h3>
        <p><?php echo $total_goods; ?></p>
    </div>
    <div class="stat-card">
        <h3>Low Stock Items</h3>
        <p><?php echo $low_stock; ?></p>
    </div>
    <div class="stat-card">
        <h3>Total Stock Value</h3>
        <p>ksh.<?php echo number_format($total_value, 2); ?></p>
    </div>
</div>

<div class="quick-actions">
    <a href="add-goods.php" class="quick-action-btn">
        <h3>Add Goods</h3>
        <p>Register new inventory items</p>
    </a>
    <a href="goods.php" class="quick-action-btn">
        <h3>View Goods</h3>
        <p>Browse all inventory items</p>
    </a>
</div>

<div class="recent-activities">
    <h2>Recent Activities</h2>
    <div class="activity-bar">
        <?php foreach ($activities as $activity): ?>
            <div class="activity-item">
                <span class="activity-name"><?php echo htmlspecialchars($activity['nickname']); ?></span>
                <span class="activity-action">added <?php echo htmlspecialchars($activity['name']); ?></span>
                <span class="activity-time"><?php echo date('M j, H:i', strtotime($activity['added_at'])); ?></span>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php include '../includes/footer.php'; ?>