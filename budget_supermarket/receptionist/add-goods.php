<?php 
include '../includes/config.php';
checkRole('receptionist');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $category = $_POST['category'];
    $description = $_POST['description'];
    $quantity = $_POST['quantity'];
    $price = $_POST['price'];
    $added_by = $_SESSION['user_id'];
    
    $stmt = $pdo->prepare("INSERT INTO goods (name, category, description, quantity, price, added_by) 
                          VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$name, $category, $description, $quantity, $price, $added_by]);
    
    $_SESSION['message'] = "Goods added successfully!";
    header("Location: add-goods.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Goods</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body class="main-page">
    <?php include '../includes/header.php'; ?>
    
    <div class="container">
        <h1>Add Goods to Stock</h1>
        
        <?php if (isset($_SESSION['message'])): ?>
            <div class="message"><?php echo $_SESSION['message']; unset($_SESSION['message']); ?></div>
        <?php endif; ?>
        
        <form method="post" action="add-goods.php">
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="category">Category:</label>
                <select id="category" name="category" required>
                    <option value="Food">Food</option>
                    <option value="Beverages">Beverages</option>
                    <option value="Toiletries">Toiletries</option>
                    <option value="Household">Household</option>
                    <option value="Other">Other</option>
                </select>
            </div>
            <div class="form-group">
                <label for="description">Description:</label>
                <textarea id="description" name="description"></textarea>
            </div>
            <div class="form-group">
                <label for="quantity">Quantity:</label>
                <input type="number" id="quantity" name="quantity" required min="1">
            </div>
            <div class="form-group">
                <label for="price">Price:</label>
                <input type="number" id="price" name="price" required min="0.01" step="0.01">
            </div>
            <button type="submit" class="submit-btn">Add Goods</button>
        </form>
    </div>
</body>
</html>