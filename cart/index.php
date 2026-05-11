<?php
// cart/index.php
require_once __DIR__ . '/../includes/header.php';

if (!isLoggedIn()) {
    redirect($baseUrl . '/auth/login.php');
}

$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT c.*, p.name, p.price, p.image FROM cart c JOIN products p ON c.product_id = p.id WHERE c.user_id = ?");
$stmt->execute([$user_id]);
$cart_items = $stmt->fetchAll();

$total_price = 0;
?>

<div class="container mb-4">
    <h2 class="mb-4">Shopping Cart</h2>
    
    <?php if(empty($cart_items)): ?>
        <div class="card text-center py-5">
            <i class="fa-solid fa-cart-shopping mb-4" style="font-size: 4rem; color: var(--border);"></i>
            <h3 class="mb-2">Your cart is empty</h3>
            <p class="text-muted mb-4">Looks like you haven't added anything to your cart yet.</p>
            <a href="<?php echo $baseUrl; ?>/products/index.php" class="btn btn-primary">Start Shopping</a>
        </div>
    <?php else: ?>
        <div class="grid-cols-3" style="grid-template-columns: 2fr 1fr;">
            <div>
                <?php foreach($cart_items as $item): 
                    $subtotal = $item['price'] * $item['quantity'];
                    $total_price += $subtotal;
                ?>
                <div class="card mb-4" style="display: flex; gap: 1.5rem; align-items: center;">
                    <?php if($item['image']): ?>
                        <img src="<?php echo $baseUrl; ?>/assets/images/uploads/<?php echo htmlspecialchars($item['image']); ?>" style="width: 100px; height: 100px; object-fit: cover; border-radius: var(--radius);" alt="">
                    <?php else: ?>
                        <div style="width: 100px; height: 100px; background: #eee; border-radius: var(--radius); display: flex; align-items: center; justify-content: center;">No Img</div>
                    <?php endif; ?>
                    
                    <div style="flex: 1;">
                        <h4><?php echo htmlspecialchars($item['name']); ?></h4>
                        <p class="text-primary font-bold">$<?php echo number_format($item['price'], 2); ?></p>
                    </div>
                    
                    <div style="display: flex; align-items: center; gap: 1rem;">
                        <form action="action.php" method="POST" style="display: flex; gap: 0.5rem; align-items: center;">
                            <input type="hidden" name="action" value="update">
                            <input type="hidden" name="product_id" value="<?php echo $item['product_id']; ?>">
                            <input type="number" name="quantity" value="<?php echo $item['quantity']; ?>" min="1" class="form-control" style="width: 70px; padding: 0.25rem 0.5rem;" onchange="this.form.submit()">
                        </form>
                        
                        <p style="font-weight: bold; width: 80px; text-align: right;">$<?php echo number_format($subtotal, 2); ?></p>
                        
                        <form action="action.php" method="POST">
                            <input type="hidden" name="action" value="remove">
                            <input type="hidden" name="product_id" value="<?php echo $item['product_id']; ?>">
                            <button type="submit" class="text-danger" style="background: none; border: none; cursor: pointer; font-size: 1.25rem;"><i class="fa-solid fa-trash"></i></button>
                        </form>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            
            <div>
                <div class="card" style="position: sticky; top: 100px;">
                    <h3 class="mb-4">Order Summary</h3>
                    <div style="display: flex; justify-content: space-between; margin-bottom: 1rem;">
                        <span class="text-muted">Subtotal</span>
                        <span>$<?php echo number_format($total_price, 2); ?></span>
                    </div>
                    <div style="display: flex; justify-content: space-between; margin-bottom: 1rem;">
                        <span class="text-muted">Shipping</span>
                        <span>Free</span>
                    </div>
                    <hr class="mb-4" style="border: 0; border-top: 1px solid var(--border);">
                    <div style="display: flex; justify-content: space-between; margin-bottom: 2rem; font-size: 1.25rem; font-weight: bold;">
                        <span>Total</span>
                        <span class="text-primary">$<?php echo number_format($total_price, 2); ?></span>
                    </div>
                    <a href="<?php echo $baseUrl; ?>/checkout/index.php" class="btn btn-primary" style="width: 100%;">Proceed to Checkout</a>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
