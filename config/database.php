<?php
// config/database.php
$host = 'localhost';
$db   = 'ecommerce_db';
$user = 'root'; // default XAMPP/WAMP user
$pass = '';     // default XAMPP/WAMP pass
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    // For development, we might want to see the error. In production, generic message.
    die("Database connection failed: " . $e->getMessage());
}
?>
