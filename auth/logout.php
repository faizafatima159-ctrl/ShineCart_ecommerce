<?php
// auth/logout.php
require_once __DIR__ . '/../includes/functions.php';

// Unset all of the session variables
$_SESSION = array();

// Destroy the session.
if (session_id() != "" || isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time() - 2592000, '/');
}
session_destroy();

redirect(getBaseUrl() . '/index.php');
?>
