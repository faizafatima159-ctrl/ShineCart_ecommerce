<?php
// user/orders.php
require_once __DIR__ . '/../includes/header.php';

if (!isLoggedIn()) {
    redirect($baseUrl . '/auth/login.php');
}

$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY id DESC");
$stmt->execute([$user_id]);
$orders = $stmt->fetchAll();
?>

<div class="container mb-4">
    <div class="dashboard-layout">
        <aside class="sidebar">
            <ul class="sidebar-nav">
                <li><a href="dashboard.php">My Profile</a></li>
                <li><a href="orders.php" class="active">My Orders</a></li>
                <li><a href="<?php echo $baseUrl; ?>/auth/logout.php">Logout</a></li>
            </ul>
        </aside>
        
        <div class="dashboard-content">
            <h2 class="mb-4">My Orders</h2>
            
            <div class="card">
                <table style="width: 100%; border-collapse: collapse; text-align: left;">
                    <thead>
                        <tr style="border-bottom: 2px solid var(--border);">
                            <th style="padding: 1rem;">Order ID</th>
                            <th style="padding: 1rem;">Date</th>
                            <th style="padding: 1rem;">Total</th>
                            <th style="padding: 1rem;">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($orders as $o): ?>
                        <tr style="border-bottom: 1px solid var(--border);">
                            <td style="padding: 1rem;">#<?php echo $o['id']; ?></td>
                            <td style="padding: 1rem;"><?php echo date('M d, Y', strtotime($o['created_at'])); ?></td>
                            <td style="padding: 1rem;">$<?php echo number_format($o['total_price'], 2); ?></td>
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
                        </tr>
                        <?php endforeach; ?>
                        <?php if(empty($orders)): ?>
                        <tr><td colspan="4" style="padding: 1rem; text-align: center;">You have no orders yet.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
