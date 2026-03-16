<?php 
include '../includes/config.php';
checkRole('cashier');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $goods_id = $_POST['goods_id'];
    $quantity = $_POST['quantity'];
    
    // Get goods info
    $stmt = $pdo->prepare("SELECT * FROM goods WHERE id = ?");
    $stmt->execute([$goods_id]);
    $goods = $stmt->fetch();
    
    if ($goods && $goods['quantity'] >= $quantity) {
        // Record sale
        $stmt = $pdo->prepare("INSERT INTO sales (goods_id, quantity, price, sold_by) 
                              VALUES (?, ?, ?, ?)");
        $stmt->execute([$goods_id, $quantity, $goods['price'], $_SESSION['user_id']]);
        
        // Update stock
        $stmt = $pdo->prepare("UPDATE goods SET quantity = quantity - ? WHERE id = ?");
        $stmt->execute([$quantity, $goods_id]);
        
        $_SESSION['message'] = "Sale recorded successfully!";
    } else {
        $_SESSION['error'] = "Insufficient stock or invalid item!";
    }
    header("Location: sales.php");
    exit();
}

// Get goods for dropdown
$stmt = $pdo->query("SELECT * FROM goods WHERE quantity > 0 ORDER BY name");
$goods = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Process Sale</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body class="main-page">
    <?php include '../includes/header.php'; ?>
    
    <div class="container">
        <h1>Process Sale</h1>
        
        <?php if (isset($_SESSION['message'])): ?>
            <div class="message"><?php echo $_SESSION['message']; unset($_SESSION['message']); ?></div>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['error'])): ?>
            <div class="error"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
        <?php endif; ?>
        
        <form method="post">
            <div class="form-group">
                <label for="goods_id">Item:</label>
                <select id="goods_id" name="goods_id" required>
                    <?php foreach ($goods as $item): ?>
                        <option value="<?php echo $item['id']; ?>">
                            <?php echo htmlspecialchars($item['name']); ?> (ksh.<?php echo number_format($item['price'], 2); ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="quantity">Quantity:</label>
                <input type="number" id="quantity" name="quantity" required min="1">
            </div>
            <button type="submit" class="submit-btn">Process Sale</button>
        </form>
        
        <div class="print-section">
            <button onclick="window.print()" class="print-btn">Print Receipt</button>
        </div>
    </div>
</body>
</html>