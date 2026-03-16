<?php 
include '../includes/config.php';
checkRole('admin');

$search = isset($_GET['search']) ? "%{$_GET['search']}%" : '%';
$category = isset($_GET['category']) ? $_GET['category'] : '';

// Build query
$sql = "SELECT g.*, u.nickname as added_by_name FROM goods g 
        JOIN users u ON g.added_by = u.id 
        WHERE (g.name LIKE ? OR g.category LIKE ? OR g.description LIKE ?)";
$params = [$search, $search, $search];

if (!empty($category)) {
    $sql .= " AND g.category = ?";
    $params[] = $category;
}

$sql .= " ORDER BY g.name";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$goods = $stmt->fetchAll();

// Get categories for filter
$stmt = $pdo->query("SELECT DISTINCT category FROM goods ORDER BY category");
$categories = $stmt->fetchAll(PDO::FETCH_COLUMN);
?>

<?php $pageTitle = "Inventory Management"; include '../includes/header.php'; ?>

<h1>Inventory Management</h1>

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
            <th>Added By</th>
            <th>Added At</th>
            <th>Actions</th>
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
            <td><?php echo htmlspecialchars($item['added_by_name']); ?></td>
            <td><?php echo date('M j, Y', strtotime($item['added_at'])); ?></td>
            <td>
                <a href="../receptionist/add-goods.php?edit=<?php echo $item['id']; ?>" class="edit-btn">Edit</a>
                <?php if ($item['quantity'] == 0): ?>
                    <a href="?delete=<?php echo $item['id']; ?>" class="delete-btn" 
                       onclick="return confirm('Are you sure you want to delete this item?')">Delete</a>
                <?php endif; ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php include '../includes/footer.php'; ?>