<?php
include '../includes/config.php';
checkRole('admin');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Handle settings update
    $setting_name = $_POST['setting_name'];
    $setting_value = $_POST['setting_value'];
    
    // In a real system, you would store these in a settings table
    $_SESSION['message'] = "Settings updated successfully!";
    header("Location: settings.php");
    exit();
}
?>

<?php $pageTitle = "System Settings"; include '../includes/header.php'; ?>

<h1>System Settings</h1>

<?php if (isset($_SESSION['message'])): ?>
    <div class="message"><?php echo $_SESSION['message']; unset($_SESSION['message']); ?></div>
<?php endif; ?>

<form method="post" class="settings-form">
    <div class="form-group">
        <label for="company_name">Company Name:</label>
        <input type="text" id="company_name" name="setting_value" value="Budget Supermarket" required>
        <input type="hidden" name="setting_name" value="company_name">
    </div>
    
    <div class="form-group">
        <label for="currency">Currency:</label>
        <select id="currency" name="setting_value" required>
            <option value="Ksh">Kenyan Shilling (Ksh)</option>
            <option value="$">Dollar ($)</option>
            <option value="€">Euro (€)</option>
            <option value="£">Pound (£)</option>
        </select>
        <input type="hidden" name="setting_name" value="currency">
    </div>
    
    <div class="form-group">
        <label for="low_stock_threshold">Low Stock Threshold:</label>
        <input type="number" id="low_stock_threshold" name="setting_value" value="10" min="1" required>
        <input type="hidden" name="setting_name" value="low_stock_threshold">
    </div>
    
    <div class="form-group">
        <label for="receipt_footer">Receipt Footer Text:</label>
        <textarea id="receipt_footer" name="setting_value">Thank you for shopping with us!</textarea>
        <input type="hidden" name="setting_name" value="receipt_footer">
    </div>
    
    <button type="submit" class="submit-btn">Save Settings</button>
</form>

<?php include '../includes/footer.php'; ?>