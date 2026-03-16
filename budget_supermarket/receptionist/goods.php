<?php 
include '../includes/config.php';
checkRole('receptionist');

$search = isset($_GET['search']) ? "%{$_GET['search']}%" : '%';
$category = isset($_GET['category']) ? $_GET['category'] : '';

// Build query
$sql = "SELECT * FROM goods WHERE (name LIKE ? OR category LIKE ? OR description LIKE ?)";
$params = [$search, $search, $search];

if (!empty($category)) {
    $sql .= " AND category = ?";
    $params[] = $category;
}

$sql .= " ORDER BY name";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$goods = $stmt->fetchAll();

// Get categories for filter
$stmt = $pdo->query("SELECT DISTINCT category FROM goods ORDER BY category");
$categories = $stmt->fetchAll(PDO::FETCH_COLUMN);
?>

<?php $pageTitle = "Goods List"; include '../includes/header.php'; ?>

<h1>Goods List</h1>

<div class="search-bar">
    <form method="get" action="goods.php">
        <input type="text" name="search" placeholder="Search goods..." 
               value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
        <select name="category">
            <option value="">All Categories</option>
            <?php foreach ($categories as $cat): ?>
                <option value="<?php echo $cat; ?>" <?php echo $category == $cat ? 'selected' : ''; ?>>
                    <?php echo $cat; ?>
                </option>
            <?php endforeach; ?>
        </select>
        <button type="submit">Search</button>
    </form>
</div>

<table class="goods-table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Category</th>
            <th>Description</th>
            <th>Quantity</th>
            <th>Price</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($goods as $item): ?>
        <tr>
            <td><?php echo $item['id']; ?></td>
            <td><?php echo htmlspecialchars($item['name']); ?></td>
            <td><?php echo htmlspecialchars($item['category']); ?></td>
            <td><?php echo htmlspecialchars($item['description']); ?></td>
            <td><?php echo $item['quantity']; ?></td>
            <td>ksh.<?php echo number_format($item['price'], 2); ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<div class="print-section">
    <button onclick="window.print()" class="print-btn">Print Goods List</button>
</div>

<?php include '../includes/footer.php'; ?>