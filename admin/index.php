<?php
// admin/index.php
require_once __DIR__ . '/../includes/header.php';

if (!isAdmin()) {
    redirect($baseUrl . '/index.php');
}

// Get statistics
$total_users = $pdo->query("SELECT COUNT(*) FROM users WHERE role = 'customer'")->fetchColumn();
$total_orders = $pdo->query("SELECT COUNT(*) FROM orders")->fetchColumn();
$total_revenue = $pdo->query("SELECT SUM(total_price) FROM orders WHERE status != 'Pending'")->fetchColumn() ?: 0;
$total_products = $pdo->query("SELECT COUNT(*) FROM products")->fetchColumn();
?>

<div class="container">
    <div class="dashboard-layout">
        <aside class="sidebar">
            <ul class="sidebar-nav">
                <li><a href="index.php" class="active">Dashboard</a></li>
                <li><a href="categories.php">Categories</a></li>
                <li><a href="products.php">Products</a></li>
                <li><a href="orders.php">Orders</a></li>
            </ul>
        </aside>
        
        <div class="dashboard-content">
            <h2 class="mb-4">Admin Dashboard</h2>
            
            <div class="grid-cols-4 mb-4">
                <div class="card text-center">
                    <h3>Total Users</h3>
                    <p class="text-primary" style="font-size: 2rem; font-weight: bold;"><?php echo $total_users; ?></p>
                </div>
                <div class="card text-center">
                    <h3>Total Orders</h3>
                    <p class="text-primary" style="font-size: 2rem; font-weight: bold;"><?php echo $total_orders; ?></p>
                </div>
                <div class="card text-center">
                    <h3>Revenue</h3>
                    <p class="text-success" style="font-size: 2rem; font-weight: bold;">$<?php echo number_format($total_revenue, 2); ?></p>
                </div>
                <div class="card text-center">
                    <h3>Products</h3>
                    <p class="text-primary" style="font-size: 2rem; font-weight: bold;"><?php echo $total_products; ?></p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
