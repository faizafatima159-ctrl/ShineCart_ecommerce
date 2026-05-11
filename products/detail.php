<?php
// products/detail.php
require_once __DIR__ . '/../includes/header.php';

if (!isset($_GET['id'])) {
    redirect($baseUrl . '/products/index.php');
}

$id = (int)$_GET['id'];
$stmt = $pdo->prepare("SELECT p.*, c.name as category_name FROM products p LEFT JOIN categories c ON p.category_id = c.id WHERE p.id = ?");
$stmt->execute([$id]);
$product = $stmt->fetch();

if (!$product) {
    redirect($baseUrl . '/products/index.php');
}

// Handle review submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_review']) && isLoggedIn()) {
    $rating = (int)$_POST['rating'];
    $comment = sanitizeInput($_POST['comment']);
    $user_id = $_SESSION['user_id'];
    
    $stmt = $pdo->prepare("INSERT INTO reviews (product_id, user_id, rating, comment) VALUES (?, ?, ?, ?)");
    $stmt->execute([$id, $user_id, $rating, $comment]);
    redirect($baseUrl . "/products/detail.php?id=$id");
}

$reviews = $pdo->prepare("SELECT r.*, u.name as user_name FROM reviews r JOIN users u ON r.user_id = u.id WHERE r.product_id = ? ORDER BY r.created_at DESC");
$reviews->execute([$id]);
$reviews_list = $reviews->fetchAll();
?>

<div class="container mb-4">
    <div class="grid-cols-2">
        <div>
            <?php if($product['image']): ?>
                <img src="<?php echo $baseUrl; ?>/assets/images/uploads/<?php echo htmlspecialchars($product['image']); ?>" style="width: 100%; border-radius: var(--radius); box-shadow: var(--shadow-sm);" alt="<?php echo htmlspecialchars($product['name']); ?>">
            <?php else: ?>
                <div style="width: 100%; height: 400px; background: #eee; border-radius: var(--radius); display: flex; align-items: center; justify-content: center; color: var(--text-muted);">No Image</div>
            <?php endif; ?>
        </div>
        <div>
            <p class="text-muted mb-2"><?php echo htmlspecialchars($product['category_name'] ?? 'Uncategorized'); ?></p>
            <h1 class="mb-4"><?php echo htmlspecialchars($product['name']); ?></h1>
            <p class="text-primary mb-4" style="font-size: 2rem; font-weight: bold;">$<?php echo number_format($product['price'], 2); ?></p>
            
            <p class="mb-4" style="color: var(--text-muted); line-height: 1.8;">
                <?php echo nl2br(htmlspecialchars($product['description'])); ?>
            </p>
            
            <form action="<?php echo $baseUrl; ?>/cart/action.php" method="POST" style="display: flex; gap: 1rem; align-items: center;">
                <input type="hidden" name="action" value="add">
                <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                <input type="number" name="quantity" value="1" min="1" class="form-control" style="width: 80px;">
                <button type="submit" class="btn btn-primary btn-lg" style="flex: 1; padding: 1rem;"><i class="fa-solid fa-cart-plus"></i> Add to Cart</button>
            </form>
        </div>
    </div>

    <!-- Reviews Section -->
    <div class="mt-4 card">
        <h3 class="mb-4">Customer Reviews</h3>
        
        <?php if(isLoggedIn()): ?>
            <form action="" method="POST" class="mb-4" style="background: var(--background); padding: 1.5rem; border-radius: var(--radius);">
                <h4 class="mb-2">Write a Review</h4>
                <div class="form-group">
                    <label>Rating</label>
                    <select name="rating" class="form-control" style="width: 100px;">
                        <option value="5">5 Stars</option>
                        <option value="4">4 Stars</option>
                        <option value="3">3 Stars</option>
                        <option value="2">2 Stars</option>
                        <option value="1">1 Star</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Review</label>
                    <textarea name="comment" class="form-control" rows="3" required></textarea>
                </div>
                <button type="submit" name="submit_review" class="btn btn-primary">Submit Review</button>
            </form>
        <?php else: ?>
            <p class="text-muted mb-4"><a href="<?php echo $baseUrl; ?>/auth/login.php" class="text-primary">Login</a> to write a review.</p>
        <?php endif; ?>

        <?php if(empty($reviews_list)): ?>
            <p class="text-muted">No reviews yet. Be the first to review this product!</p>
        <?php else: ?>
            <?php foreach($reviews_list as $r): ?>
                <div style="border-bottom: 1px solid var(--border); padding: 1rem 0;">
                    <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                        <strong><?php echo htmlspecialchars($r['user_name']); ?></strong>
                        <span class="text-secondary">
                            <?php for($i=1; $i<=5; $i++) echo $i <= $r['rating'] ? '★' : '☆'; ?>
                        </span>
                    </div>
                    <p style="color: var(--text-muted); font-size: 0.9rem; margin-bottom: 0.5rem;"><?php echo date('M d, Y', strtotime($r['created_at'])); ?></p>
                    <p><?php echo nl2br(htmlspecialchars($r['comment'])); ?></p>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
