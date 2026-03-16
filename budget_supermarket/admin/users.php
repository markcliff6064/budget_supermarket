<?php 
include '../includes/config.php';
checkRole('admin');

// Handle user deletion
if (isset($_GET['delete'])) {
    $userId = $_GET['delete'];
    if ($userId != $_SESSION['user_id']) {
        $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
        $stmt->execute([$userId]);
        $_SESSION['message'] = "User deleted successfully!";
    } else {
        $_SESSION['error'] = "You cannot delete your own account!";
    }
    header("Location: users.php");
    exit();
}

// Get all users
$stmt = $pdo->query("SELECT * FROM users ORDER BY role, username");
$users = $stmt->fetchAll();
?>

<?php $pageTitle = "Manage Users"; include '../includes/header.php'; ?>

<h1>Manage Users</h1>

<?php if (isset($_SESSION['message'])): ?>
    <div class="message"><?php echo $_SESSION['message']; unset($_SESSION['message']); ?></div>
<?php endif; ?>

<?php if (isset($_SESSION['error'])): ?>
    <div class="error"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
<?php endif; ?>

<div class="add-user-btn">
    <button onclick="document.getElementById('addUserModal').style.display='block'" class="submit-btn">Add New User</button>
</div>

<table class="users-table">
    <thead>
        <tr>
            <th>Username</th>
            <th>Nickname</th>
            <th>Role</th>
            <th>Created At</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($users as $user): ?>
        <tr>
            <td><?php echo htmlspecialchars($user['username']); ?></td>
            <td><?php echo htmlspecialchars($user['nickname']); ?></td>
            <td><?php echo ucfirst($user['role']); ?></td>
            <td><?php echo date('M j, Y', strtotime($user['created_at'])); ?></td>
            <td>
                <?php if ($user['id'] != $_SESSION['user_id']): ?>
                    <a href="?delete=<?php echo $user['id']; ?>" class="delete-btn" 
                       onclick="return confirm('Are you sure you want to delete this user?')">Delete</a>
                <?php endif; ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<!-- Add User Modal -->
<div id="addUserModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="document.getElementById('addUserModal').style.display='none'">&times;</span>
        <h2>Add New User</h2>
        <form action="../includes/auth.php" method="post">
            <input type="hidden" name="action" value="signup">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="nickname">Nickname:</label>
                <input type="text" id="nickname" name="nickname" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-group">
                <label for="role">Role:</label>
                <select id="role" name="role" required>
                    <option value="admin">Admin</option>
                    <option value="cashier">Cashier</option>
                    <option value="receptionist">Receptionist</option>
                </select>
            </div>
            <button type="submit" class="submit-btn">Create User</button>
        </form>
    </div>
</div>

<script>
// Close modal when clicking outside
window.onclick = function(event) {
    if (event.target == document.getElementById('addUserModal')) {
        document.getElementById('addUserModal').style.display = "none";
    }
}
</script>

<?php include '../includes/footer.php'; ?>