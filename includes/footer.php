<?php
// includes/footer.php
$thisFile = str_replace('\\', '/', __FILE__);
$docRoot = str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT']);
$path = str_replace($docRoot, '', dirname(dirname($thisFile)));
$baseUrl = rtrim($path, '/');
?>
</main>
<footer class="footer">
    <div class="container footer-container">
        <div class="footer-section">
            <h3><i class="fa-solid fa-gem"></i> ShineCart</h3>
            <p>Your premium destination for luxury products online. Quality guaranteed.</p>
        </div>
        <div class="footer-section">
            <h4>Quick Links</h4>
            <ul>
                <li><a href="<?php echo $baseUrl; ?>/index.php">Home</a></li>
                <li><a href="<?php echo $baseUrl; ?>/products/index.php">Shop</a></li>
                <li><a href="<?php echo $baseUrl; ?>/cart/index.php">Cart</a></li>
            </ul>
        </div>
        <div class="footer-section">
            <h4>Contact</h4>
            <ul>
                <li><i class="fa-solid fa-envelope"></i> support@luxecart.com</li>
                <li><i class="fa-solid fa-phone"></i> +1 234 567 890</li>
            </ul>
        </div>
    </div>
    <div class="footer-bottom">
        <p>&copy; <?php echo date('Y'); ?> ShineCart. All rights reserved.</p>
    </div>
</footer>

<script src="<?php echo $baseUrl; ?>/assets/js/script.js?v=<?php echo time(); ?>"></script>
</body>
</html>
