<?php
/**
 * NextGen Farming Incubation Hub - Admin Logout
 */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 1. Unset all session variables in memory
$_SESSION = [];

// 2. Delete the session cookie in browser
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}

// 3. Completely destroy the server session
session_destroy();

// 4. Send anti-caching HTTP headers so browser history doesn't store protected pages
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");

// 5. Redirect to login page
header("Location: login.php?logout=1");
exit;

