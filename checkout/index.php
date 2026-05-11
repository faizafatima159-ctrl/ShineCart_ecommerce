<?php
// checkout/index.php
require_once __DIR__ . '/../includes/header.php';

if (!isLoggedIn()) {
    redirect($baseUrl . '/auth/login.php');
}

$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT c.*, p.name, p.price FROM cart c JOIN products p ON c.product_id = p.id WHERE c.user_id = ?");
$stmt->execute([$user_id]);
$cart_items = $stmt->fetchAll();

if (empty($cart_items)) {
    redirect($baseUrl . '/cart/index.php');
}

$total_price = 0;
foreach($cart_items as $item) {
    $total_price += $item['price'] * $item['quantity'];
}
?>

<div class="container mb-4">
    <h2 class="mb-4">Checkout</h2>
    
    <div class="grid-cols-3" style="grid-template-columns: 2fr 1fr;">
        <div>
            <div class="card">
                <h3 class="mb-4">Shipping Information</h3>
                <form action="process.php" method="POST" id="checkout-form">
                    <div class="form-group">
                        <label>Full Name</label>
                        <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($_SESSION['user_name']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Phone Number</label>
                        <input type="tel" name="phone" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Shipping Address</label>
                        <textarea name="address" class="form-control" rows="4" required></textarea>
                    </div>
                    <!-- Payment would be here in a real app -->
                </form>
            </div>
        </div>
        
        <div>
            <div class="card" style="position: sticky; top: 100px;">
                <h3 class="mb-4">Order Summary</h3>
                <div style="max-height: 300px; overflow-y: auto; margin-bottom: 1rem; border-bottom: 1px solid var(--border); padding-bottom: 1rem;">
                    <?php foreach($cart_items as $item): ?>
                    <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem; font-size: 0.9rem;">
                        <span><?php echo htmlspecialchars($item['name']); ?> (x<?php echo $item['quantity']; ?>)</span>
                        <span>$<?php echo number_format($item['price'] * $item['quantity'], 2); ?></span>
                    </div>
                    <?php endforeach; ?>
                </div>
                
                <div style="display: flex; justify-content: space-between; margin-bottom: 2rem; font-size: 1.25rem; font-weight: bold;">
                    <span>Total</span>
                    <span class="text-primary">$<?php echo number_format($total_price, 2); ?></span>
                </div>
                
                <button type="button" class="btn btn-primary" style="width: 100%;" onclick="document.getElementById('checkout-form').submit();">Place Order</button>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
