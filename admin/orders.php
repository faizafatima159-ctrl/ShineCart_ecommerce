<?php
// admin/orders.php
require_once __DIR__ . '/../includes/header.php';

if (!isAdmin()) {
    redirect($baseUrl . '/index.php');
}

$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $order_id = (int)$_POST['order_id'];
    $status = $_POST['status'];
    $valid_statuses = ['Pending', 'Shipped', 'Delivered'];
    
    if (in_array($status, $valid_statuses)) {
        $stmt = $pdo->prepare("UPDATE orders SET status = ? WHERE id = ?");
        $stmt->execute([$status, $order_id]);
        $success = "Order #$order_id status updated to $status.";
    }
}

$orders = $pdo->query("SELECT o.*, u.name as user_name FROM orders o JOIN users u ON o.user_id = u.id ORDER BY o.id DESC")->fetchAll();
?>

<div class="container">
    <div class="dashboard-layout">
        <aside class="sidebar">
            <ul class="sidebar-nav">
                <li><a href="index.php">Dashboard</a></li>
                <li><a href="categories.php">Categories</a></li>
                <li><a href="products.php">Products</a></li>
                <li><a href="orders.php" class="active">Orders</a></li>
            </ul>
        </aside>
        
        <div class="dashboard-content">
            <h2 class="mb-4">Manage Orders</h2>
            
            <?php if($success): ?>
                <div class="mb-4 text-success" style="background: #d1fae5; padding: 1rem; border-radius: var(--radius);"><?php echo $success; ?></div>
            <?php endif; ?>

            <div class="card">
                <table style="width: 100%; border-collapse: collapse; text-align: left;">
                    <thead>
                        <tr style="border-bottom: 2px solid var(--border);">
                            <th style="padding: 1rem;">ID</th>
                            <th style="padding: 1rem;">Customer</th>
                            <th style="padding: 1rem;">Total</th>
                            <th style="padding: 1rem;">Date</th>
                            <th style="padding: 1rem;">Status</th>
                            <th style="padding: 1rem;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($orders as $o): ?>
                        <tr style="border-bottom: 1px solid var(--border);">
                            <td style="padding: 1rem;">#<?php echo $o['id']; ?></td>
                            <td style="padding: 1rem;"><?php echo htmlspecialchars($o['user_name']); ?></td>
                            <td style="padding: 1rem;">$<?php echo number_format($o['total_price'], 2); ?></td>
                            <td style="padding: 1rem;"><?php echo date('M d, Y', strtotime($o['created_at'])); ?></td>
                            <td style="padding: 1rem;">
                                <?php
                                    $badgeClass = '';
                                    if($o['status'] == 'Pending') $badgeClass = 'background: #fef3c7; color: #d97706;';
                                    elseif($o['status'] == 'Shipped') $badgeClass = 'background: #e0e7ff; color: #4338ca;';
                                    elseif($o['status'] == 'Delivered') $badgeClass = 'background: #d1fae5; color: #059669;';
                                ?>
                                <span style="<?php echo $badgeClass; ?> padding: 0.25rem 0.5rem; border-radius: var(--radius); font-size: 0.875rem; font-weight: bold;">
                                    <?php echo htmlspecialchars($o['status']); ?>
                                </span>
                            </td>
                            <td style="padding: 1rem;">
                                <form action="orders.php" method="POST" style="display: flex; gap: 0.5rem;">
                                    <input type="hidden" name="order_id" value="<?php echo $o['id']; ?>">
                                    <select name="status" class="form-control" style="width: auto; padding: 0.25rem;">
                                        <option value="Pending" <?php echo $o['status'] == 'Pending' ? 'selected' : ''; ?>>Pending</option>
                                        <option value="Shipped" <?php echo $o['status'] == 'Shipped' ? 'selected' : ''; ?>>Shipped</option>
                                        <option value="Delivered" <?php echo $o['status'] == 'Delivered' ? 'selected' : ''; ?>>Delivered</option>
                                    </select>
                                    <button type="submit" name="update_status" class="btn btn-primary" style="padding: 0.25rem 0.5rem;">Update</button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php if(empty($orders)): ?>
                        <tr><td colspan="6" style="padding: 1rem; text-align: center;">No orders found.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
