<?php
// products/index.php
require_once __DIR__ . '/../includes/header.php';

$search = isset($_GET['search']) ? sanitizeInput($_GET['search']) : '';
$category_filter = isset($_GET['category']) ? (int)$_GET['category'] : 0;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$per_page = 8;
$offset = ($page - 1) * $per_page;

$where = [];
$params = [];

if ($search) {
    $where[] = "p.name LIKE ?";
    $params[] = "%$search%";
}
if ($category_filter) {
    $where[] = "p.category_id = ?";
    $params[] = $category_filter;
}

$whereClause = !empty($where) ? "WHERE " . implode(' AND ', $where) : "";

// Count total
$countSql = "SELECT COUNT(*) FROM products p $whereClause";
$stmtCount = $pdo->prepare($countSql);
$stmtCount->execute($params);
$total_products = $stmtCount->fetchColumn();
$total_pages = ceil($total_products / $per_page);

// Get products
$sql = "SELECT p.*, c.name as category_name FROM products p LEFT JOIN categories c ON p.category_id = c.id $whereClause ORDER BY p.id DESC LIMIT $per_page OFFSET $offset";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$products = $stmt->fetchAll();

$categories = $pdo->query("SELECT * FROM categories")->fetchAll();
?>

<div class="container mb-4">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <h2>Our Products</h2>
        <form action="" method="GET" style="display: flex; gap: 1rem;">
            <?php if($search): ?><input type="hidden" name="search" value="<?php echo htmlspecialchars($search); ?>"><?php endif; ?>
            <select name="category" class="form-control" onchange="this.form.submit()" style="width: auto;">
                <option value="0">All Categories</option>
                <?php foreach($categories as $cat): ?>
                    <option value="<?php echo $cat['id']; ?>" <?php echo $category_filter == $cat['id'] ? 'selected' : ''; ?>><?php echo htmlspecialchars($cat['name']); ?></option>
                <?php endforeach; ?>
            </select>
        </form>
    </div>

    <?php if(empty($products)): ?>
        <p class="text-center text-muted">No products found.</p>
    <?php else: ?>
        <div class="grid-cols-4">
            <?php foreach($products as $p): ?>
            <div class="product-card">
                <?php if($p['image']): ?>
                    <img src="<?php echo $baseUrl; ?>/assets/images/uploads/<?php echo htmlspecialchars($p['image']); ?>" class="product-img" alt="<?php echo htmlspecialchars($p['name']); ?>">
                <?php else: ?>
                    <div class="product-img" style="background: #eee; display: flex; align-items: center; justify-content: center; color: var(--text-muted);">No Image</div>
                <?php endif; ?>
                <div class="product-info">
                    <p class="text-muted" style="font-size: 0.875rem;"><?php echo htmlspecialchars($p['category_name'] ?? 'Uncategorized'); ?></p>
                    <h3 class="product-title"><?php echo htmlspecialchars($p['name']); ?></h3>
                    <p class="product-price">$<?php echo number_format($p['price'], 2); ?></p>
                    <div class="product-actions">
                        <a href="detail.php?id=<?php echo $p['id']; ?>" class="btn btn-outline" style="flex: 1;">View</a>
                        <form action="<?php echo $baseUrl; ?>/cart/action.php" method="POST" style="flex: 1; display:flex;">
                            <input type="hidden" name="action" value="add">
                            <input type="hidden" name="product_id" value="<?php echo $p['id']; ?>">
                            <input type="hidden" name="quantity" value="1">
                            <button type="submit" class="btn btn-primary" style="width: 100%;"><i class="fa-solid fa-cart-plus"></i></button>
                        </form>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        
        <!-- Pagination -->
        <?php if($total_pages > 1): ?>
        <div style="display: flex; justify-content: center; gap: 0.5rem; margin-top: 3rem;">
            <?php for($i=1; $i<=$total_pages; $i++): ?>
                <a href="?page=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>&category=<?php echo $category_filter; ?>" class="btn <?php echo $page == $i ? 'btn-primary' : 'btn-outline'; ?>"><?php echo $i; ?></a>
            <?php endfor; ?>
        </div>
        <?php endif; ?>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
