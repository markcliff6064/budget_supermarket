<?php 
include '../includes/config.php';
checkRole('receptionist');

$search = isset($_GET['search']) ? "%{$_GET['search']}%" : '%';
$category = isset($_GET['category']) ? $_GET['category'] : '';
$start_date = $_GET['start_date'] ?? date('Y-m-01');
$end_date = $_GET['end_date'] ?? date('Y-m-d');

// Build query
$sql = "SELECT g.*, u.nickname as added_by_name 
        FROM goods g JOIN users u ON g.added_by = u.id 
        WHERE (g.name LIKE ? OR g.category LIKE ? OR g.description LIKE ?)
        AND g.added_at BETWEEN ? AND ?";
$params = [$search, $search, $search, $start_date, $end_date.' 23:59:59'];

if (!empty($category)) {
    $sql .= " AND g.category = ?";
    $params[] = $category;
}

$sql .= " ORDER BY g.added_at DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$goods = $stmt->fetchAll();

// Get categories for filter
$stmt = $pdo->query("SELECT DISTINCT category FROM goods ORDER BY category");
$categories = $stmt->fetchAll(PDO::FETCH_COLUMN);
?>

<?php $pageTitle = "Stock History"; include '../includes/header.php'; ?>

<h1>Stock History</h1>

<div class="filter-form">
    <form method="get" action="stock-history.php">
        <div class="form-row">
            <div class="form-group">
                <label for="start_date">Start Date:</label>
                <input type="date" id="start_date" name="start_date" value="<?php echo $start_date; ?>">
            </div>
            <div class="form-group">
                <label for="end_date">End Date:</label>
                <input type="date" id="end_date" name="end_date" value="<?php echo $end_date; ?>">
            </div>
            <div class="form-group">
                <label for="search">Search:</label>
                <input type="text" id="search" name="search" 
                       value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
            </div>
            <div class="form-group">
                <label for="category">Category:</label>
                <select id="category" name="category">
                    <option value="">All Categories</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?php echo $cat; ?>" <?php echo $category == $cat ? 'selected' : ''; ?>>
                            <?php echo $cat; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="filter-btn">Filter</button>
            <button type="button" onclick="window.print()" class="print-btn">Print Report</button>
        </div>
    </form>
</div>

<table class="history-table">
    <thead>
        <tr>
            <th>Name</th>
            <th>Category</th>
            <th>Description</th>
            <th>Quantity</th>
            <th>Price</th>
            <th>Added By</th>
            <th>Date Added</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($goods as $item): ?>
        <tr>
            <td><?php echo htmlspecialchars($item['name']); ?></td>
            <td><?php echo htmlspecialchars($item['category']); ?></td>
            <td><?php echo htmlspecialchars($item['description']); ?></td>
            <td><?php echo $item['quantity']; ?></td>
            <td>ksh.<?php echo number_format($item['price'], 2); ?></td>
            <td><?php echo htmlspecialchars($item['added_by_name']); ?></td>
            <td><?php echo date('M j, Y H:i', strtotime($item['added_at'])); ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php include '../includes/footer.php'; ?>