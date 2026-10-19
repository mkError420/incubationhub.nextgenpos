<?php
/**
 * NextGen Farming Incubation Hub - Admin Logout
 */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

unset($_SESSION['admin_user']);
session_destroy();

header("Location: login.php?logout=1");
exit;
