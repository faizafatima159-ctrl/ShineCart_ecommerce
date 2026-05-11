<?php
// index.php
require_once __DIR__ . '/includes/header.php';

$featured_products = $pdo->query("SELECT p.*, c.name as category_name FROM products p LEFT JOIN categories c ON p.category_id = c.id ORDER BY p.id DESC LIMIT 4")->fetchAll();
?>

<style>
/* Remove the gap between navbar and slider */
.main-content { padding-top: 0 !important; }
</style>

 <!-- hero slider -->

 <div class="hero-slider">
    <div class="slide active" style="background-image: url('<?php echo $baseUrl; ?>/assets/images/slider/slider1.png');">
        <div class="slide-overlay"></div>
        <div class="slide-content">
            <h1 class="slide-title">Elegance Redefined</h1>
            <p class="slide-text">Discover our exclusive collection of luxury diamond rings.</p>
            <a href="products/index.php" class="btn btn-primary" style="font-size: 1rem; padding: 1rem 2.5rem;">Shop The Collection</a>
        </div>
    </div>
    <div class="slide" style="background-image: url('<?php echo $baseUrl; ?>/assets/images/slider/slider2.png');">
        <div class="slide-overlay"></div>
        <div class="slide-content">
            <h1 class="slide-title">Timeless Beauty</h1>
            <p class="slide-text">Adorn yourself with intricate, sophisticated necklaces.</p>
            <a href="products/index.php" class="btn btn-primary" style="font-size: 1rem; padding: 1rem 2.5rem;">Discover More</a>
        </div>
    </div>




<div class="slide" style="background-image: url('<?php echo $baseUrl; ?>/assets/images/slider/slider3.jpg');">
    <div class="slide-overlay"></div>
    <div class="slide-content">
        <h1 class="slide-title">New Collection</h1>
        <p class="slide-text">Fresh designs just arrived.</p>
        <a href="products/index.php" class="btn btn-primary">Shop Now</a>
    </div>
</div>





    
    <button class="slider-btn prev-btn"><i class="fa-solid fa-chevron-left"></i></button>
    <button class="slider-btn next-btn"><i class="fa-solid fa-chevron-right"></i></button>
    
    <div class="slider-dots">
        <div class="dot active" data-index="0"></div>
        <div class="dot" data-index="1"></div>
        <div class="dot" data-index="2"></div>
<div class="dot" data-index="3"></div>
    </div>
</div>

 /*featured products

<div class="container mb-4" style="padding-top: 3rem; padding-bottom: 2rem;">
    <div class="text-center mb-4 animate-on-scroll fade-up">
        <h2 style="font-family: 'Cinzel', serif; font-size: 3rem; letter-spacing: 3px; margin-bottom: 0.5rem; color: var(--primary); text-transform: uppercase; text-shadow: 1px 2px 4px rgba(0,0,0,0.15);">Featured Creations</h2>
        <div style="width: 80px; height: 3px; background-color: var(--gold); margin: 0 auto 1rem auto; box-shadow: 0 2px 4px rgba(0,0,0,0.1);"></div>
        <p class="text-muted" style="font-size: 1.2rem; font-family: 'Playfair Display', serif; font-style: italic;">Handpicked masterpieces from our exclusive catalog</p>
    </div>
    <div class="grid-cols-4">
        <?php foreach($featured_products as $index => $p): ?>
        <div class="product-card animate-on-scroll fade-up" style="transition-delay: <?php echo $index * 150; ?>ms;">
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
                    <a href="products/detail.php?id=<?php echo $p['id']; ?>" class="btn btn-outline" style="flex: 1;">View</a>
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
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
