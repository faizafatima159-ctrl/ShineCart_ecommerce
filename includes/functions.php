<?php
// includes/functions.php
session_start();

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function isAdmin() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

function sanitizeInput($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

function redirect($url) {
    header("Location: $url");
    exit;
}

function getBaseUrl() {
    // Determine base url dynamically
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
    $host = $_SERVER['HTTP_HOST'];
    $docRoot = str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT']);
    $thisFile = str_replace('\\', '/', __FILE__);
    // __FILE__ is includes/functions.php, so dirname(dirname(__FILE__)) is the root
    $rootDir = dirname(dirname($thisFile));
    $path = str_replace($docRoot, '', $rootDir);
    // If running in php built in server directly from root, $path might be empty or start with /
    if (strpos($path, '/') !== 0 && !empty($path)) {
        $path = '/' . $path;
    }
    return $path; // returning relative path is generally safer for links
}
?>
