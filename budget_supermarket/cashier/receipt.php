<?php
include '../includes/config.php';
checkRole('cashier');

if (!isset($_GET['sale_id'])) {
    header("Location: sales.php");
    exit();
}

$sale_id = $_GET['sale_id'];
$stmt = $pdo->prepare("SELECT s.*, g.name as item_name, g.price, u.nickname as cashier 
                      FROM sales s 
                      JOIN goods g ON s.goods_id = g.id 
                      JOIN users u ON s.sold_by = u.id 
                      WHERE s.id = ?");
$stmt->execute([$sale_id]);
$sale = $stmt->fetch();

if (!$sale) {
    $_SESSION['error'] = "Sale not found!";
    header("Location: sales.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        @media print {
            body * {
                visibility: hidden;
            }
            .receipt-container, .receipt-container * {
                visibility: visible;
            }
            .receipt-container {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
            }
            .no-print {
                display: none;
            }
        }
        .receipt-container {
            width: 300px;
            margin: 20px auto;
            padding: 20px;
            border: 1px dashed #ccc;
            font-family: monospace;
        }
        .receipt-header {
            text-align: center;
            margin-bottom: 15px;
        }
        .receipt-details {
            margin: 15px 0;
        }
        .receipt-items {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }
        .receipt-items th {
            text-align: left;
            border-bottom: 1px dashed #000;
        }
        .receipt-items td {
            padding: 5px 0;
        }
        .receipt-total {
            text-align: right;
            font-weight: bold;
            margin-top: 10px;
            border-top: 1px dashed #000;
            padding-top: 10px;
        }
        .receipt-footer {
            text-align: center;
            margin-top: 20px;
            font-size: 0.8em;
        }
    </style>
</head>
<body>
    <div class="no-print" style="text-align: center; margin: 20px;">
        <button onclick="window.print()" class="print-btn">Print Receipt</button>
        <a href="sales.php" class="nav-btn">Back to Sales</a>
    </div>
    
    <div class="receipt-container">
        <div class="receipt-header">
            <h2>BUDGET SUPERMARKET</h2>
            <p>123 Market Street, Nairobi</p>
            <p>Tel: 0700 000000</p>
        </div>
        
        <div class="receipt-details">
            <p><strong>Receipt #:</strong> <?php echo str_pad($sale['id'], 6, '0', STR_PAD_LEFT); ?></p>
            <p><strong>Date:</strong> <?php echo date('M j, Y H:i', strtotime($sale['sold_at'])); ?></p>
            <p><strong>Cashier:</strong> <?php echo htmlspecialchars($sale['cashier']); ?></p>
        </div>
        
        <table class="receipt-items">
            <thead>
                <tr>
                    <th>Item</th>
                    <th>Qty</th>
                    <th>Price</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><?php echo htmlspecialchars($sale['item_name']); ?></td>
                    <td><?php echo $sale['quantity']; ?></td>
                    <td>ksh.<?php echo number_format($sale['price'], 2); ?></td>
                    <td>ksh.<?php echo number_format($sale['quantity'] * $sale['price'], 2); ?></td>
                </tr>
            </tbody>
        </table>
        
        <div class="receipt-total">
            <p>Total: ksh.<?php echo number_format($sale['quantity'] * $sale['price'], 2); ?></p>
        </div>
        
        <div class="receipt-footer">
            <p>Thank you for shopping with us!</p>
            <p>Goods once sold are not returnable</p>
        </div>
    </div>
</body>
</html>