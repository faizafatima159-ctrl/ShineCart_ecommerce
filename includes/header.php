<?php
// includes/header.php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/functions.php';

// Dynamically determine base URL to make it work anywhere
$baseUrl = '';
$scriptDir = dirname($_SERVER['SCRIPT_NAME']);
// if the script is in a subdirectory (like ecommerce/auth/login.php)
// we want to find the root 'ecommerce' folder path
// A reliable way is to define a constant in a top level file, but let's use a dynamic approach
$docRoot = $_SERVER['DOCUMENT_ROOT'];
$thisFile = str_replace('\\', '/', __FILE__);
$docRoot = str_replace('\\', '/', $docRoot);
$path = str_replace($docRoot, '', dirname(dirname($thisFile)));
$baseUrl = rtrim($path, '/');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Premium E-commerce</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;600;700&family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400&family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="<?php echo $baseUrl; ?>/assets/css/style.css?v=<?php echo time(); ?>">
</head>
<body>

<header class="navbar">
    <div class="container nav-container">
        <a href="<?php echo $baseUrl; ?>/index.php" class="logo">
            <i class="fa-solid fa-gem"></i> ShineCart
        </a>
        
        <div class="search-bar">
            <form action="<?php echo $baseUrl; ?>/products/index.php" method="GET">
                <input type="text" name="search" placeholder="Search products...">
                <button type="submit"><i class="fa-solid fa-search"></i></button>
            </form>
        </div>

        <nav class="nav-links">
            <a href="<?php echo $baseUrl; ?>/products/index.php">Shop</a>
            <a href="<?php echo $baseUrl; ?>/cart/index.php" class="cart-icon">
                <i class="fa-solid fa-cart-shopping"></i>
                <span class="cart-count" id="cart-count-badge">
                    <?php 
                        if(isLoggedIn() && isset($pdo)) {
                            try {
                                $stmt = $pdo->prepare("SELECT SUM(quantity) as count FROM cart WHERE user_id = ?");
                                $stmt->execute([$_SESSION['user_id']]);
                                $res = $stmt->fetch();
                                echo $res['count'] ? $res['count'] : "0";
                            } catch(Exception $e) {
                                echo "0";
                            }
                        } else {
                            echo "0";
                        }
                    ?>
                </span>
            </a>
            
            <?php if(isLoggedIn()): ?>
                <div class="dropdown">
                    <a href="#" class="dropbtn"><i class="fa-regular fa-user"></i> <?php echo htmlspecialchars($_SESSION['user_name']); ?></a>
                    <div class="dropdown-content">
                        <?php if(isAdmin()): ?>
                            <a href="<?php echo $baseUrl; ?>/admin/index.php">Dashboard</a>
                        <?php else: ?>
                            <a href="<?php echo $baseUrl; ?>/user/dashboard.php">My Account</a>
                            <a href="<?php echo $baseUrl; ?>/user/orders.php">My Orders</a>
                        <?php endif; ?>
                        <a href="<?php echo $baseUrl; ?>/auth/logout.php">Logout</a>
                    </div>
                </div>
            <?php else: ?>
                <a href="<?php echo $baseUrl; ?>/auth/login.php" class="btn btn-outline">Login</a>
                <a href="<?php echo $baseUrl; ?>/auth/register.php" class="btn btn-primary">Sign Up</a>
            <?php endif; ?>
        </nav>
    </div>
</header>
<main class="main-content">
