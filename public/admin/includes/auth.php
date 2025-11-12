<?php
// Auth utility file (deprecated - auth now in header.php)
// Keeping this for backward compatibility if needed

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/db.php';

function admin_name() {
    return $_SESSION['admin_name'] ?? 'Admin';
}

// Untuk include manual (opsional):
// if (empty($_SESSION['admin_id'])) {
//     header('Location: login.php');
//     exit;
// }
?>
