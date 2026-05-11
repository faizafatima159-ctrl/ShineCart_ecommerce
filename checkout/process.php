<?php
// checkout/process.php
require_once __DIR__ . '/../includes/header.php';

if (!isLoggedIn()) {
    redirect($baseUrl . '/auth/login.php');
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect($baseUrl . '/cart/index.php');
}

$user_id = $_SESSION['user_id'];
$address = sanitizeInput($_POST['address']);
$phone = sanitizeInput($_POST['phone']);

// Get cart items
$stmt = $pdo->prepare("SELECT c.*, p.price FROM cart c JOIN products p ON c.product_id = p.id WHERE c.user_id = ?");
$stmt->execute([$user_id]);
$cart_items = $stmt->fetchAll();

if (empty($cart_items)) {
    redirect($baseUrl . '/cart/index.php');
}

$total_price = 0;
foreach($cart_items as $item) {
    $total_price += $item['price'] * $item['quantity'];
}

try {
    $pdo->beginTransaction();
    
    // Create Order
    $stmt = $pdo->prepare("INSERT INTO orders (user_id, total_price, status, shipping_address, phone) VALUES (?, ?, 'Pending', ?, ?)");
    $stmt->execute([$user_id, $total_price, $address, $phone]);
    $order_id = $pdo->lastInsertId();
    
    // Create Order Items
    $stmtItem = $pdo->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
    foreach($cart_items as $item) {
        $stmtItem->execute([$order_id, $item['product_id'], $item['quantity'], $item['price']]);
    }
    
    // Clear Cart
    $stmtClear = $pdo->prepare("DELETE FROM cart WHERE user_id = ?");
    $stmtClear->execute([$user_id]);
    
    $pdo->commit();
    
    $success = true;
} catch (Exception $e) {
    $pdo->rollBack();
    $success = false;
}
?>

<div class="container text-center" style="padding: 5rem 0;">
    <?php if($success): ?>
        <i class="fa-solid fa-circle-check text-success mb-4" style="font-size: 5rem;"></i>
        <h2 class="mb-2">Order Placed Successfully!</h2>
        <p class="text-muted mb-4">Thank you for your purchase. Your order number is #<?php echo $order_id; ?>.</p>
        <a href="<?php echo $baseUrl; ?>/user/orders.php" class="btn btn-primary">View Orders</a>
        <a href="<?php echo $baseUrl; ?>/products/index.php" class="btn btn-outline ml-2">Continue Shopping</a>
    <?php else: ?>
        <i class="fa-solid fa-circle-xmark text-danger mb-4" style="font-size: 5rem;"></i>
        <h2 class="mb-2">Order Failed</h2>
        <p class="text-muted mb-4">Something went wrong while processing your order. Please try again.</p>
        <a href="<?php echo $baseUrl; ?>/checkout/index.php" class="btn btn-primary">Try Again</a>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
