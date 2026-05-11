<?php
// user/dashboard.php
require_once __DIR__ . '/../includes/header.php';

if (!isLoggedIn()) {
    redirect($baseUrl . '/auth/login.php');
}

$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

$total_orders = $pdo->prepare("SELECT COUNT(*) FROM orders WHERE user_id = ?");
$total_orders->execute([$user_id]);
$order_count = $total_orders->fetchColumn();
?>

<div class="container mb-4">
    <div class="dashboard-layout">
        <aside class="sidebar">
            <ul class="sidebar-nav">
                <li><a href="dashboard.php" class="active">My Profile</a></li>
                <li><a href="orders.php">My Orders</a></li>
                <li><a href="<?php echo $baseUrl; ?>/auth/logout.php">Logout</a></li>
            </ul>
        </aside>
        
        <div class="dashboard-content">
            <h2 class="mb-4">Welcome, <?php echo htmlspecialchars($user['name']); ?>!</h2>
            
            <div class="grid-cols-2 mb-4">
                <div class="card text-center">
                    <h3>Total Orders</h3>
                    <p class="text-primary" style="font-size: 2rem; font-weight: bold;"><?php echo $order_count; ?></p>
                </div>
                <div class="card">
                    <h3>Profile Information</h3>
                    <p class="mt-2"><strong>Name:</strong> <?php echo htmlspecialchars($user['name']); ?></p>
                    <p class="mt-2"><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
                    <p class="mt-2"><strong>Member Since:</strong> <?php echo date('M d, Y', strtotime($user['created_at'])); ?></p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
