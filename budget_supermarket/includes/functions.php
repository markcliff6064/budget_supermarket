<?php
function formatCurrency($amount) {
    return 'ksh.' . number_format($amount, 2);
}

function getLowStockThreshold() {
    // In a real system, this would come from database settings
    return 10;
}

function isLowStock($quantity) {
    return $quantity < getLowStockThreshold();
}

function getCurrentDateTime() {
    return date('Y-m-d H:i:s');
}

function redirectWithMessage($url, $message, $isError = false) {
    $_SESSION[$isError ? 'error' : 'message'] = $message;
    header("Location: $url");
    exit();
}

function validateInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

function getCategories($pdo) {
    $stmt = $pdo->query("SELECT DISTINCT category FROM goods ORDER BY category");
    return $stmt->fetchAll(PDO::FETCH_COLUMN);
}
?>