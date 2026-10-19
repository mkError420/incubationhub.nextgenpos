<?php
/**
 * ============================================================
 * NextGen Farming Incubation Hub - cPanel Configuration File
 * ============================================================
 * 
 * Configured with live cPanel MySQL Database credentials.
 */

// 1. Database Host
defined('DB_HOST') or define('DB_HOST', 'localhost');

// 2. Database Port (Default MySQL port is 3306)
defined('DB_PORT') or define('DB_PORT', '3306');

// 3. Database Name
defined('DB_NAME') or define('DB_NAME', 'nextgenp_incubation');

// 4. Database User
defined('DB_USER') or define('DB_USER', 'nextgenp_incubation');

// 5. Database Password
defined('DB_PASS') or define('DB_PASS', 'KdfTxkeQF?h~u9L;');

// 6. Base URL (Optional: Leave empty for auto-detection or set to 'https://yourdomain.com')
defined('BASE_URL') or define('BASE_URL', '');

// 7. Site Environment ('production' for live cPanel, 'development' for local testing)
defined('APP_ENV') or define('APP_ENV', 'production');

if (APP_ENV === 'production') {
    // Hide PHP errors from live visitors for security
    error_reporting(0);
    @ini_set('display_errors', '0');
} else {
    // Show errors during development
    error_reporting(E_ALL);
    @ini_set('display_errors', '1');
}
