<?php
require_once __DIR__ . '/config/database.php';
try {
    $pdo->exec("INSERT IGNORE INTO categories (id, name) VALUES (1, 'Rings'), (2, 'Necklaces'), (3, 'Bracelets'), (4, 'Earrings')");
    
    // Clear old to avoid duplicates
    $pdo->exec("DELETE FROM products");
    
    $pdo->exec("INSERT INTO products (name, price, description, image, category_id) VALUES 
        ('Rose Gold Diamond Bracelet', 2499.99, 'A beautiful classic diamond bracelet in rose gold.', 'product1.png', 3),
        ('Luxury Diamond Drop Earrings', 1299.99, 'Elegant diamond earrings for sophisticated evenings.', 'product2.png', 4),
        ('Classic Solitaire Ring', 3899.99, 'Timeless solitaire diamond engagement ring.', 'product1.png', 1),
        ('Pearl & Gold Necklace', 1599.99, 'Classic string of pearls with a solid gold clasp.', 'product2.png', 2)");
    echo "Database seeded successfully!";
} catch(Exception $e) {
    echo "Error: " . $e->getMessage();
}
