<?php 
include '../includes/config.php';
checkRole('admin');

// Default filters
$start_date = $_GET['start_date'] ?? date('Y-m-01');
$end_date = $_GET['end_date'] ?? date('Y-m-d');
$category = $_GET['category'] ?? '';

// Get sales report
$sql = "SELECT s.*, g.name, g.category, u.nickname as cashier 
        FROM sales s 
        JOIN goods g ON s.goods_id = g.id 
        JOIN users u ON s.sold_by = u.id 
        WHERE s.sold_at BETWEEN ? AND ?";
$params = [$start_date, $end_date.' 23:59:59'];

if (!empty($category)) {
    $sql .= " AND g.category = ?";
    $params[] = $category;
}

$sql .= " ORDER BY s.sold_at DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$sales = $stmt->fetchAll();

// Get categories for filter
$stmt = $pdo->query("SELECT DISTINCT category FROM goods ORDER BY category");
$categories = $stmt->fetchAll(PDO::FETCH_COLUMN);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales Reports</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body class="main-page">
    <?php include '../includes/header.php'; ?>
    
    <div class="container">
        <h1>Sales Reports</h1>
        
        <form method="get" class="filter-form">
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
                <button type="button" onclick="printReport()" class="print-btn">Print Report</button>
            </div>
        </form>
        
        <div class="report-summary">
            <h3>Summary</h3>
            <p>Total Sales: ksh.<?php 
                $total = array_reduce($sales, function($carry, $sale) {
                    return $carry + ($sale['quantity'] * $sale['price']);
                }, 0);
                echo number_format($total, 2);
            ?></p>
            <p>Total Items Sold: <?php 
                $count = array_reduce($sales, function($carry, $sale) {
                    return $carry + $sale['quantity'];
                }, 0);
                echo $count;
            ?></p>
        </div>
        
        <table class="report-table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Item</th>
                    <th>Category</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Total</th>
                    <th>Cashier</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($sales as $sale): ?>
                <tr>
                    <td><?php echo date('M j, Y H:i', strtotime($sale['sold_at'])); ?></td>
                    <td><?php echo htmlspecialchars($sale['name']); ?></td>
                    <td><?php echo htmlspecialchars($sale['category']); ?></td>
                    <td><?php echo $sale['quantity']; ?></td>
                    <td>ksh.<?php echo number_format($sale['price'], 2); ?></td>
                    <td>ksh.<?php echo number_format($sale['quantity'] * $sale['price'], 2); ?></td>
                    <td><?php echo htmlspecialchars($sale['cashier']); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    
    <script>
    function printReport() {
        window.print();
    }
    </script>
</body>
</html>