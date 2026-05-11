<?php
// cart/action.php
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../config/database.php';

if (!isLoggedIn()) {
    redirect(getBaseUrl() . '/auth/login.php');
}

$user_id = $_SESSION['user_id'];
$action = $_POST['action'] ?? '';
$product_id = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;
$quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;

if ($action === 'add' && $product_id > 0) {
    // Check if already in cart
    $stmt = $pdo->prepare("SELECT id, quantity FROM cart WHERE user_id = ? AND product_id = ?");
    $stmt->execute([$user_id, $product_id]);
    $item = $stmt->fetch();
    
    if ($item) {
        $stmt = $pdo->prepare("UPDATE cart SET quantity = quantity + ? WHERE id = ?");
        $stmt->execute([$quantity, $item['id']]);
    } else {
        $stmt = $pdo->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)");
        $stmt->execute([$user_id, $product_id, $quantity]);
    }
} elseif ($action === 'update' && $product_id > 0) {
    if ($quantity > 0) {
        $stmt = $pdo->prepare("UPDATE cart SET quantity = ? WHERE user_id = ? AND product_id = ?");
        $stmt->execute([$quantity, $user_id, $product_id]);
    } else {
        $stmt = $pdo->prepare("DELETE FROM cart WHERE user_id = ? AND product_id = ?");
        $stmt->execute([$user_id, $product_id]);
    }
} elseif ($action === 'remove' && $product_id > 0) {
    $stmt = $pdo->prepare("DELETE FROM cart WHERE user_id = ? AND product_id = ?");
    $stmt->execute([$user_id, $product_id]);
}

redirect(getBaseUrl() . '/cart/index.php');
?>
