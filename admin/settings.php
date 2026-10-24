<?php
/**
 * NextGen Farming Incubation Hub - Admin Profile & System Settings (Normal & Classic Style)
 */

require_once __DIR__ . '/auth.php';
requireAdminAuth();

$admin = getCurrentAdmin();
$successMsg = '';
$errorMsg = '';
$dbConnected = isDbConnected();

// Handle Admin Actions
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    
    // 1. Update Current Admin Profile & Password
    if ($_POST['action'] === 'update_profile') {
        $fullName = trim($_POST['full_name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $newPass = trim($_POST['new_password'] ?? '');
        $confirmPass = trim($_POST['confirm_password'] ?? '');

        if (!empty($newPass) && $newPass !== $confirmPass) {
            $errorMsg = 'New password and confirmation password do not match.';
        } elseif (!empty($newPass) && strlen($newPass) < 6) {
            $errorMsg = 'New password must be at least 6 characters long.';
        } else {
            $currentUsername = $admin['username'] ?? 'admin';
            if (updateAdminProfile($admin['id'], $newPass ?: null, $fullName ?: null, $email ?: null, $currentUsername)) {
                // Update session values
                $_SESSION['admin_user']['full_name'] = $fullName;
                $_SESSION['admin_user']['email'] = $email;
                $admin = $_SESSION['admin_user'];
                $successMsg = !empty($newPass) 
                    ? 'Admin profile and new password updated successfully! You can now use your new password.'
                    : 'Admin profile updated successfully!';
            } else {
                $errorMsg = 'Could not update profile. Please verify database connection.';
            }
        }
    } 
    // 2. Add New Administrator User
    elseif ($_POST['action'] === 'add_admin') {
        $username    = trim($_POST['new_admin_username'] ?? '');
        $fullName    = trim($_POST['new_admin_fullname'] ?? '');
        $email       = trim($_POST['new_admin_email'] ?? '');
        $role        = trim($_POST['new_admin_role'] ?? 'Super Admin');
        $password    = trim($_POST['new_admin_password'] ?? '');
        $confirmPass = trim($_POST['new_admin_confirm_password'] ?? '');

        if ($password !== $confirmPass) {
            $errorMsg = 'New administrator password and confirmation password do not match.';
        } else {
            $res = createAdminUser([
                'username'  => $username,
                'full_name' => $fullName,
                'email'     => $email,
                'role'      => $role,
                'password'  => $password,
            ]);
            if ($res['success']) {
                $successMsg = $res['message'];
            } else {
                $errorMsg = $res['message'];
            }
        }
    }
    // 3. Delete Administrator User
    elseif ($_POST['action'] === 'delete_admin') {
        $delId = (int)($_POST['admin_id'] ?? 0);
        $res = deleteAdminUser($delId, $admin['id']);
        if ($res['success']) {
            $successMsg = $res['message'];
        } else {
            $errorMsg = $res['message'];
        }
    }
    // 4. Update Site & Login Page Brand Logos
    elseif ($_POST['action'] === 'update_logos') {
        $currentSiteLogo  = getSiteSetting('site_logo', '');
        $currentLoginLogo = getSiteSetting('login_logo', '');

        $siteLogoVal  = $currentSiteLogo;
        $loginLogoVal = $currentLoginLogo;

        // --- Site Logo Processing ---
        if (!empty($_POST['remove_site_logo'])) {
            deleteOldLogoFile($currentSiteLogo);
            $siteLogoVal = '';
        } else {
            if (isset($_FILES['site_logo_file']) && $_FILES['site_logo_file']['error'] === UPLOAD_ERR_OK) {
                $uploadRes = processLogoUpload('site_logo_file', 'site_logo');
                if ($uploadRes['success']) {
                    deleteOldLogoFile($currentSiteLogo);
                    $siteLogoVal = $uploadRes['path'];
                } else {
                    $errorMsg = 'Site logo upload error: ' . $uploadRes['error'];
                }
            } elseif (isset($_POST['site_logo_url']) && trim($_POST['site_logo_url']) !== '') {
                $siteLogoVal = trim($_POST['site_logo_url']);
            }
        }

        // --- Login Logo Processing ---
        if (!empty($_POST['remove_login_logo'])) {
            deleteOldLogoFile($currentLoginLogo);
            $loginLogoVal = '';
        } else {
            if (!empty($_POST['sync_login_logo'])) {
                if ($currentLoginLogo !== $currentSiteLogo && !empty($currentLoginLogo)) {
                    deleteOldLogoFile($currentLoginLogo);
                }
                $loginLogoVal = $siteLogoVal;
            } elseif (isset($_FILES['login_logo_file']) && $_FILES['login_logo_file']['error'] === UPLOAD_ERR_OK) {
                $uploadRes = processLogoUpload('login_logo_file', 'login_logo');
                if ($uploadRes['success']) {
                    deleteOldLogoFile($currentLoginLogo);
                    $loginLogoVal = $uploadRes['path'];
                } else {
                    $errorMsg = ($errorMsg ? $errorMsg . ' | ' : '') . 'Login logo upload error: ' . $uploadRes['error'];
                }
            } elseif (isset($_POST['login_logo_url']) && trim($_POST['login_logo_url']) !== '') {
                $loginLogoVal = trim($_POST['login_logo_url']);
            }
        }

        if (empty($errorMsg)) {
            $ok1 = updateSiteSetting('site_logo', $siteLogoVal, 'branding');
            $ok2 = updateSiteSetting('login_logo', $loginLogoVal, 'branding');
            if ($ok1 && $ok2) {
                $successMsg = 'Website logo and login page logo updated successfully!';
            } else {
                $errorMsg = 'Could not save logo settings. Please verify server permissions.';
            }
        }
    }
    // 5. Update Landing Page Gate Setting
    elseif ($_POST['action'] === 'update_landing_gate') {
        $gateVal = isset($_POST['landing_gate_enabled']) && $_POST['landing_gate_enabled'] === '1' ? '1' : '0';
        if (updateSiteSetting('landing_gate_enabled', $gateVal, 'general')) {
            $successMsg = 'Landing Page Gate configuration updated successfully! ' . ($gateVal === '1' ? '(Gate is ENABLED: Visitors must submit idea before viewing website)' : '(Gate is DISABLED: Website is publicly accessible without gate)');
        } else {
            $errorMsg = 'Could not update Landing Page Gate configuration.';
        }
    }
}

// Fetch current logos & gate setting
$siteLogo = getSiteSetting('site_logo', '');
$loginLogo = getSiteSetting('login_logo', '');
$siteLogoUrl = !empty($siteLogo) ? getLogoUrl($siteLogo, true) : '';
$loginLogoUrl = !empty($loginLogo) ? getLogoUrl($loginLogo, true) : '';
$isSynced = (!empty($loginLogo) && $loginLogo === $siteLogo);
$landingGateEnabled = getSiteSetting('landing_gate_enabled', '1');

// Fetch all registered admin users
$allAdmins = getAllAdminUsers();

// Fetch database stats if connected
$tableCounts = [];
if ($dbConnected) {
    $pdo = getDbConnection();
    try {
        $tables = ['admin_users', 'site_settings', 'programs', 'hub_submissions', 'newsletter_subscribers'];
        foreach ($tables as $t) {
            $count = (int)$pdo->query("SELECT COUNT(*) FROM `{$t}`")->fetchColumn();
            $tableCounts[$t] = $count;
        }
    } catch (PDOException $e) {
        // Ignore
    }
}

renderAdminHeader('settings', 'Admin Profile & System Settings', 'Settings & DB');
?>

<div class="max-w-4xl space-y-6">
    
    <!-- Page Header Bar -->
    <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold text-slate-900 tracking-tight">System Settings & Security</h1>
            <p class="text-xs text-slate-500 mt-0.5">Manage administrator credentials, team access privileges, and database diagnostics.</p>
        </div>
        <div class="flex items-center space-x-2 text-xs">
            <span class="inline-flex items-center px-2.5 py-1 rounded border font-semibold <?= $dbConnected ? 'bg-emerald-50 text-emerald-800 border-emerald-200' : 'bg-amber-50 text-amber-800 border-amber-200' ?>">
                <span class="w-2 h-2 rounded-full <?= $dbConnected ? 'bg-emerald-500 animate-pulse' : 'bg-amber-500' ?> mr-1.5"></span>
                <?= $dbConnected ? 'MySQL Connected' : 'Adaptive Cache Active' ?>
            </span>
        </div>
    </div>

    <!-- Alert Notifications -->
    <?php if ($successMsg): ?>
        <div class="alert-box p-4 rounded-lg bg-emerald-50 border border-emerald-300 text-emerald-900 text-xs flex items-center justify-between shadow-sm">
            <span class="font-bold flex items-center"><i class="fa-solid fa-circle-check text-emerald-600 mr-2 text-base"></i> <?= htmlspecialchars($successMsg) ?></span>
            <button type="button" class="alert-close-btn text-emerald-700 hover:text-emerald-950"><i class="fa-solid fa-xmark"></i></button>
        </div>
    <?php endif; ?>

    <?php if ($errorMsg): ?>
        <div class="alert-box p-4 rounded-lg bg-red-50 border border-red-300 text-red-900 text-xs flex items-center justify-between shadow-sm">
            <span class="font-bold flex items-center"><i class="fa-solid fa-circle-exclamation text-red-600 mr-2 text-base"></i> <?= htmlspecialchars($errorMsg) ?></span>
            <button type="button" class="alert-close-btn text-red-700 hover:text-red-950"><i class="fa-solid fa-xmark"></i></button>
        </div>
    <?php endif; ?>

    <!-- Landing Page Gate & Idea Intake Control -->
    <div class="bg-white p-6 rounded-xl border border-amber-200/90 shadow-sm space-y-5">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-4 border-b border-slate-200 gap-3">
            <div class="flex items-center space-x-3">
                <div class="w-9 h-9 rounded-lg bg-amber-100 text-amber-800 flex items-center justify-center text-base font-bold shadow-sm shrink-0">
                    <i class="fa-solid fa-lock"></i>
                </div>
                <div>
                    <div class="flex items-center space-x-2">
                        <h2 class="text-sm font-bold text-slate-900 uppercase tracking-wider">Landing Page Gate & Idea Intake Control</h2>
                        <span class="text-[10px] <?= $landingGateEnabled === '1' ? 'bg-emerald-100 text-emerald-800 border-emerald-300' : 'bg-slate-100 text-slate-600 border-slate-300' ?> font-black px-2 py-0.5 rounded-full border uppercase">
                            <?= $landingGateEnabled === '1' ? 'Gate Active' : 'Gate Disabled' ?>
                        </span>
                    </div>
                    <p class="text-[11px] text-slate-500 mt-0.5">Controls whether visitors are required to fill out the idea & video intake form before browsing the website.</p>
                </div>
            </div>
            <div class="flex items-center space-x-2">
                <a href="../landing.php" target="_blank" class="px-3 py-1.5 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold border border-slate-200 transition">
                    <i class="fa-solid fa-arrow-up-right-from-square mr-1 text-slate-400"></i> View Landing Page
                </a>
                <a href="ideas.php" class="px-3 py-1.5 rounded-lg bg-amber-50 hover:bg-amber-100 text-amber-800 text-xs font-bold border border-amber-200 transition">
                    <i class="fa-solid fa-lightbulb mr-1 text-amber-600"></i> View Received Ideas
                </a>
            </div>
        </div>

        <form method="POST" action="settings.php" class="space-y-4">
            <input type="hidden" name="action" value="update_landing_gate">

            <div class="p-4 rounded-xl <?= $landingGateEnabled === '1' ? 'bg-amber-50/70 border border-amber-200' : 'bg-slate-50 border border-slate-200' ?>">
                <label class="flex items-start space-x-3 cursor-pointer">
                    <input type="checkbox" name="landing_gate_enabled" value="1" <?= $landingGateEnabled === '1' ? 'checked' : '' ?> class="mt-0.5 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500 w-4 h-4">
                    <div>
                        <span class="text-xs font-bold text-slate-900 block">
                            Enforce Landing Page Intake Gate (Open landing page first for new visitors)
                        </span>
                        <span class="text-[11px] text-slate-600 block mt-0.5 leading-relaxed">
                            When checked, every visitor opening the website (index, about, programs, offerings, contact, etc.) will be automatically redirected to <code class="bg-white px-1.5 py-0.5 rounded text-[10px] font-mono border border-slate-200 text-emerald-800">landing.php</code>. Only after submitting their profile, idea description, and optional 5-minute video pitch will full website access be unlocked.
                        </span>
                    </div>
                </label>
            </div>

            <div class="flex justify-end pt-2">
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-slate-900 hover:bg-black text-white text-xs font-bold rounded-lg shadow-sm transition">
                    <i class="fa-solid fa-floppy-disk mr-1.5"></i> Save Gate Setting
                </button>
            </div>
        </form>
    </div>

    <!-- 0. Dynamic Website & Login Page Brand Logos -->
    <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-4 border-b border-slate-200 gap-3">
            <div class="flex items-center space-x-3">
                <div class="w-9 h-9 rounded-lg bg-emerald-100 text-emerald-800 flex items-center justify-center text-base font-bold shadow-sm shrink-0">
                    <i class="fa-solid fa-palette"></i>
                </div>
                <div>
                    <h2 class="text-sm font-bold text-slate-900 uppercase tracking-wider">Dynamic Brand Logos Management</h2>
                    <p class="text-[11px] text-slate-500">Maintain both public website logo (header navbar & footer) and the admin login console logo.</p>
                </div>
            </div>
            <div class="flex items-center space-x-2">
                <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-semibold <?= !empty($siteLogo) ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-slate-100 text-slate-600' ?>">
                    <i class="fa-solid fa-circle-check mr-1.5 <?= !empty($siteLogo) ? 'text-emerald-500' : 'text-slate-400' ?>"></i>
                    <?= !empty($siteLogo) ? 'Custom Site Logo Active' : 'Default Brand Mark Active' ?>
                </span>
            </div>
        </div>

        <form method="POST" action="settings.php" enctype="multipart/form-data" class="space-y-6">
            <input type="hidden" name="action" value="update_logos">

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                
                <!-- CARD 1: Public Website Logo -->
                <div class="border border-slate-200 rounded-xl p-5 bg-slate-50/50 flex flex-col justify-between space-y-4">
                    <div>
                        <div class="flex items-center justify-between mb-3">
                            <label class="text-xs font-bold uppercase tracking-wider text-slate-800 flex items-center">
                                <i class="fa-solid fa-globe text-emerald-700 mr-1.5"></i>
                                Main Website Logo
                            </label>
                            <span class="text-[10px] text-slate-400 font-medium">Header Navbar, Footer & Sidebar</span>
                        </div>

                        <!-- Preview Container with Light/Dark Mode Switch -->
                        <div class="mb-3">
                            <div class="flex items-center justify-between text-[11px] text-slate-500 mb-1.5">
                                <span class="font-semibold">Live Preview Box:</span>
                                <div class="inline-flex rounded-md shadow-xs bg-slate-200 p-0.5" role="group">
                                    <button type="button" id="site-logo-bg-light" class="px-2 py-0.5 text-[10px] font-bold rounded bg-white text-slate-800 shadow-xs transition">Navbar (Light)</button>
                                    <button type="button" id="site-logo-bg-dark" class="px-2 py-0.5 text-[10px] font-bold rounded text-slate-600 hover:text-slate-900 transition">Footer (Dark)</button>
                                </div>
                            </div>

                            <div id="site-logo-preview-box" class="h-28 rounded-lg border border-slate-200 bg-white flex flex-col items-center justify-center p-4 transition-colors duration-200 relative overflow-hidden group">
                                <div id="site-logo-preview-wrap" class="max-h-full max-w-full flex items-center justify-center">
                                    <?php if (!empty($siteLogoUrl)): ?>
                                        <img id="site-logo-preview-img" src="<?= htmlspecialchars($siteLogoUrl) ?>" alt="Site Logo Preview" class="max-h-20 max-w-[240px] w-auto object-contain">
                                        <div id="site-logo-fallback-brand" style="display: none;" class="items-center space-x-2">
                                            <div class="w-10 h-10 rounded-lg bg-emerald-700 text-white flex items-center justify-center shadow-sm"><i class="fa-solid fa-leaf text-lg text-emerald-200"></i></div>
                                            <div class="text-left">
                                                <div class="font-extrabold text-sm text-slate-900 leading-tight brand-preview-text">NextGen <span class="text-emerald-700">Farming</span></div>
                                                <span class="text-[9px] uppercase tracking-wider text-slate-500 font-semibold block">Incubation Hub</span>
                                            </div>
                                        </div>
                                    <?php else: ?>
                                        <img id="site-logo-preview-img" src="" alt="Site Logo Preview" class="max-h-20 max-w-[240px] w-auto object-contain hidden">
                                        <div id="site-logo-fallback-brand" class="flex items-center space-x-2">
                                            <div class="w-10 h-10 rounded-lg bg-emerald-700 text-white flex items-center justify-center shadow-sm"><i class="fa-solid fa-leaf text-lg text-emerald-200"></i></div>
                                            <div class="text-left">
                                                <div class="font-extrabold text-sm text-slate-900 leading-tight brand-preview-text">NextGen <span class="text-emerald-700">Farming</span></div>
                                                <span class="text-[9px] uppercase tracking-wider text-slate-500 font-semibold block">Incubation Hub</span>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <span id="site-logo-state-badge" class="absolute bottom-1.5 right-2 text-[9px] font-mono font-semibold px-1.5 py-0.5 rounded <?= !empty($siteLogo) ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-500' ?>">
                                    <?= !empty($siteLogo) ? 'Active: ' . htmlspecialchars(basename($siteLogo)) : 'System Default Icon' ?>
                                </span>
                            </div>
                        </div>

                        <!-- Upload File Input -->
                        <div class="space-y-1.5 text-xs">
                            <label class="block font-semibold text-slate-700">Upload Logo Image File</label>
                            <input type="file" name="site_logo_file" id="site_logo_file" accept=".png,.jpg,.jpeg,.webp,.svg,.gif" class="block w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 file:cursor-pointer border border-slate-300 rounded-lg bg-white cursor-pointer focus:outline-none">
                            <span class="text-[10px] text-slate-400 block">Accepted: PNG, JPG, WEBP, SVG, GIF (Max 5MB). Transparent background recommended.</span>
                        </div>

                        <!-- Direct URL Input -->
                        <div class="mt-3 text-xs">
                            <label class="block font-semibold text-slate-700 mb-1">Or Logo Image URL / CDN Link</label>
                            <input type="text" name="site_logo_url" id="site_logo_url" value="<?= htmlspecialchars($siteLogo) ?>" placeholder="e.g. assets/uploads/logos/brand.png or https://..." class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-slate-900 font-mono text-xs focus:outline-none focus:border-emerald-600">
                        </div>
                    </div>

                    <?php if (!empty($siteLogo)): ?>
                        <div class="pt-3 border-t border-slate-200 flex items-center justify-between">
                            <label class="inline-flex items-center text-xs text-red-600 font-semibold cursor-pointer">
                                <input type="checkbox" name="remove_site_logo" value="1" class="rounded border-red-300 text-red-600 focus:ring-red-500 mr-1.5">
                                Remove Logo & Reset to Default
                            </label>
                            <a href="../<?= htmlspecialchars(ltrim($siteLogo, '/')) ?>" target="_blank" class="text-[11px] text-slate-500 hover:text-emerald-700 underline flex items-center">
                                View File <i class="fa-solid fa-arrow-up-right-from-square ml-1 text-[9px]"></i>
                            </a>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- CARD 2: Admin Login Console Logo -->
                <div class="border border-slate-700/80 rounded-xl p-5 bg-slate-900 text-white flex flex-col justify-between space-y-4">
                    <div>
                        <div class="flex items-center justify-between mb-3">
                            <label class="text-xs font-bold uppercase tracking-wider text-emerald-400 flex items-center">
                                <i class="fa-solid fa-lock text-emerald-400 mr-1.5"></i>
                                Admin Login Console Logo
                            </label>
                            <span class="text-[10px] text-slate-400 font-medium">admin/login.php</span>
                        </div>

                        <!-- Preview Container styled exactly like Login Card header -->
                        <div class="mb-3">
                            <div class="flex items-center justify-between text-[11px] text-slate-400 mb-1.5">
                                <span class="font-semibold">Login Console Preview:</span>
                                <span class="text-[10px] font-mono text-slate-400">Dark Console Theme</span>
                            </div>

                            <div id="login-logo-preview-box" class="h-28 rounded-lg border border-slate-700/80 bg-slate-950 flex flex-col items-center justify-center p-4 relative overflow-hidden">
                                <div id="login-logo-preview-wrap" class="max-h-full max-w-full flex items-center justify-center">
                                    <?php if (!empty($loginLogoUrl)): ?>
                                        <img id="login-logo-preview-img" src="<?= htmlspecialchars($loginLogoUrl) ?>" alt="Login Logo Preview" class="max-h-20 max-w-[240px] w-auto object-contain">
                                        <div id="login-logo-fallback-brand" style="display: none;" class="w-12 h-12 rounded-xl bg-emerald-600 flex items-center justify-center text-white shadow-md">
                                            <i class="fa-solid fa-leaf text-xl text-emerald-100"></i>
                                        </div>
                                    <?php else: ?>
                                        <img id="login-logo-preview-img" src="" alt="Login Logo Preview" class="max-h-20 max-w-[240px] w-auto object-contain hidden">
                                        <div id="login-logo-fallback-brand" class="w-12 h-12 rounded-xl bg-emerald-600 flex items-center justify-center text-white shadow-md">
                                            <i class="fa-solid fa-leaf text-xl text-emerald-100"></i>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <span id="login-logo-state-badge" class="absolute bottom-1.5 right-2 text-[9px] font-mono font-semibold px-1.5 py-0.5 rounded <?= !empty($loginLogo) ? 'bg-emerald-950 text-emerald-300 border border-emerald-800' : 'bg-slate-800 text-slate-400' ?>">
                                    <?= !empty($loginLogo) ? ($isSynced ? 'Synced with Site Logo' : 'Active: ' . htmlspecialchars(basename($loginLogo))) : 'Default Emerald Icon' ?>
                                </span>
                            </div>
                        </div>

                        <!-- Sync Toggle Checkbox -->
                        <div class="mb-3 p-2.5 rounded-lg bg-slate-800/80 border border-slate-700/60">
                            <label class="flex items-center text-xs font-semibold text-slate-200 cursor-pointer">
                                <input type="checkbox" name="sync_login_logo" id="sync_login_logo" value="1" <?= $isSynced ? 'checked' : '' ?> class="rounded border-slate-600 text-emerald-500 focus:ring-emerald-400 mr-2">
                                <span>Use Main Site Logo for Login Page (Synchronize)</span>
                            </label>
                            <p class="text-[10px] text-slate-400 mt-1 pl-5">Automatically copies your main website logo to the login screen so you don't have to upload twice.</p>
                        </div>

                        <div id="login-logo-custom-inputs" class="space-y-3 <?= $isSynced ? 'opacity-50 pointer-events-none' : '' ?>">
                            <!-- Upload File Input -->
                            <div class="space-y-1.5 text-xs">
                                <label class="block font-semibold text-slate-300">Upload Distinct Login Logo</label>
                                <input type="file" name="login_logo_file" id="login_logo_file" accept=".png,.jpg,.jpeg,.webp,.svg,.gif" class="block w-full text-xs text-slate-400 file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-slate-800 file:text-emerald-300 hover:file:bg-slate-700 file:cursor-pointer border border-slate-700 rounded-lg bg-slate-800/50 cursor-pointer focus:outline-none">
                            </div>

                            <!-- Direct URL Input -->
                            <div class="text-xs">
                                <label class="block font-semibold text-slate-300 mb-1">Or Login Logo URL / Path</label>
                                <input type="text" name="login_logo_url" id="login_logo_url" value="<?= htmlspecialchars($loginLogo) ?>" placeholder="e.g. assets/uploads/logos/login_brand.png" class="w-full px-3 py-2 bg-slate-800 border border-slate-700 rounded-lg text-white font-mono text-xs focus:outline-none focus:border-emerald-500">
                            </div>
                        </div>
                    </div>

                    <?php if (!empty($loginLogo)): ?>
                        <div class="pt-3 border-t border-slate-800 flex items-center justify-between">
                            <label class="inline-flex items-center text-xs text-red-400 font-semibold cursor-pointer">
                                <input type="checkbox" name="remove_login_logo" value="1" class="rounded border-red-500 text-red-500 focus:ring-red-400 mr-1.5">
                                Reset Login Logo to Default
                            </label>
                            <a href="login.php" target="_blank" class="text-[11px] text-emerald-400 hover:text-emerald-300 underline flex items-center">
                                Preview Login Page <i class="fa-solid fa-arrow-up-right-from-square ml-1 text-[9px]"></i>
                            </a>
                        </div>
                    <?php endif; ?>
                </div>

            </div>

            <!-- Bottom Action Footer -->
            <div class="pt-4 border-t border-slate-200 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div class="text-[11px] text-slate-500 flex items-center">
                    <i class="fa-solid fa-circle-info text-emerald-600 mr-2 text-sm"></i>
                    <span>Logos are saved with dual-persistence (MySQL Database + Fallback Cache) and update across all public pages immediately.</span>
                </div>
                <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-emerald-700 to-agro-800 hover:from-emerald-800 hover:to-agro-900 text-white font-bold text-xs rounded-lg shadow-md hover:shadow-lg transition flex items-center justify-center">
                    <i class="fa-solid fa-floppy-disk mr-2"></i> Save & Apply Brand Logos
                </button>
            </div>
        </form>
    </div>

    <!-- 1. Administrator Profile & Security Form (Self) -->
    <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm space-y-4">
        <div class="flex items-center space-x-2.5 pb-3 border-b border-slate-200">
            <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-700 flex items-center justify-center text-sm font-bold">
                <i class="fa-solid fa-user-shield"></i>
            </div>
            <div>
                <h2 class="text-sm font-bold text-slate-900 uppercase tracking-wider">Your Profile & Password</h2>
                <p class="text-[11px] text-slate-500">Update your account name, contact email, and secure password.</p>
            </div>
        </div>

        <form method="POST" action="settings.php" class="space-y-4 text-xs">
            <input type="hidden" name="action" value="update_profile">

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Username (Login ID)</label>
                    <input type="text" disabled value="<?= htmlspecialchars($admin['username']) ?>" class="w-full px-3 py-2 bg-slate-100 border border-slate-300 rounded-lg text-slate-500 font-mono cursor-not-allowed">
                    <span class="text-[10px] text-slate-400 mt-1 block">Username is fixed for primary security.</span>
                </div>

                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Full Name</label>
                    <input type="text" name="full_name" required value="<?= htmlspecialchars($admin['full_name']) ?>" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-slate-900 focus:outline-none focus:border-emerald-600">
                </div>

                <div class="sm:col-span-2">
                    <label class="block font-semibold text-slate-700 mb-1">Administrator Email Address</label>
                    <input type="email" name="email" required value="<?= htmlspecialchars($admin['email']) ?>" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-slate-900 focus:outline-none focus:border-emerald-600">
                </div>

                <div>
                    <label class="block font-semibold text-slate-700 mb-1">New Password (Leave blank to keep current)</label>
                    <div class="relative">
                        <input type="password" name="new_password" minlength="6" placeholder="Min. 6 characters" class="w-full pl-3 pr-10 py-2 bg-white border border-slate-300 rounded-lg text-slate-900 focus:outline-none focus:border-emerald-600 transition">
                        <button type="button" class="password-toggle-btn absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600 focus:outline-none transition" aria-label="Toggle password visibility">
                            <i class="fa-solid fa-eye text-xs"></i>
                        </button>
                    </div>
                </div>

                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Confirm New Password</label>
                    <div class="relative">
                        <input type="password" name="confirm_password" minlength="6" placeholder="Re-type new password" class="w-full pl-3 pr-10 py-2 bg-white border border-slate-300 rounded-lg text-slate-900 focus:outline-none focus:border-emerald-600 transition">
                        <button type="button" class="password-toggle-btn absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600 focus:outline-none transition" aria-label="Toggle password visibility">
                            <i class="fa-solid fa-eye text-xs"></i>
                        </button>
                    </div>
                </div>
            </div>

            <div class="pt-3 border-t border-slate-200 flex justify-end">
                <button type="submit" class="px-5 py-2 bg-emerald-700 hover:bg-emerald-800 text-white font-semibold rounded-lg shadow-sm transition">
                    Save Profile & Credentials
                </button>
            </div>
        </form>
    </div>

    <!-- 2. Add New Administrator Section -->
    <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm space-y-4">
        <div class="flex items-center space-x-2.5 pb-3 border-b border-slate-200">
            <div class="w-8 h-8 rounded-lg bg-purple-50 text-purple-700 flex items-center justify-center text-sm font-bold">
                <i class="fa-solid fa-user-plus"></i>
            </div>
            <div>
                <h2 class="text-sm font-bold text-slate-900 uppercase tracking-wider">Add New Administrator</h2>
                <p class="text-[11px] text-slate-500">Create login credentials and grant portal access to new team members or editors.</p>
            </div>
        </div>

        <form method="POST" action="settings.php" class="space-y-4 text-xs">
            <input type="hidden" name="action" value="add_admin">

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block font-semibold text-slate-700 mb-1">
                        Username (Login ID) <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="new_admin_username" required placeholder="e.g. rafiqul_hub" pattern="[a-zA-Z0-9_\-\.]{3,30}" title="3-30 letters, numbers, dots, dashes, or underscores" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-slate-900 font-mono focus:outline-none focus:border-purple-600">
                    <span class="text-[10px] text-slate-400 mt-1 block">Used for sign-in (3-30 letters, numbers, dots or underscores).</span>
                </div>

                <div>
                    <label class="block font-semibold text-slate-700 mb-1">
                        Full Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="new_admin_fullname" required placeholder="e.g. Dr. Rafiqul Alam" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-slate-900 focus:outline-none focus:border-purple-600">
                </div>

                <div>
                    <label class="block font-semibold text-slate-700 mb-1">
                        Administrator Email Address <span class="text-red-500">*</span>
                    </label>
                    <input type="email" name="new_admin_email" required placeholder="e.g. rafiqul@nextgenfarminghub.bd" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-slate-900 focus:outline-none focus:border-purple-600">
                </div>

                <div>
                    <label class="block font-semibold text-slate-700 mb-1">
                        Dashboard Role & Access Level <span class="text-red-500">*</span>
                    </label>
                    <select name="new_admin_role" required class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-slate-900 focus:outline-none focus:border-purple-600">
                        <option value="Super Admin">Super Admin (Full Access: CMS, Submissions, Database & Settings)</option>
                        <option value="Editor">Editor (CMS Content & Submissions Management)</option>
                    </select>
                </div>

                <div>
                    <label class="block font-semibold text-slate-700 mb-1">
                        Initial Password <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <input type="password" name="new_admin_password" required minlength="6" placeholder="Min. 6 characters" class="w-full pl-3 pr-10 py-2 bg-white border border-slate-300 rounded-lg text-slate-900 focus:outline-none focus:border-purple-600 transition">
                        <button type="button" class="password-toggle-btn absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600 focus:outline-none transition" aria-label="Toggle password visibility">
                            <i class="fa-solid fa-eye text-xs"></i>
                        </button>
                    </div>
                </div>

                <div>
                    <label class="block font-semibold text-slate-700 mb-1">
                        Confirm Initial Password <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <input type="password" name="new_admin_confirm_password" required minlength="6" placeholder="Re-type password" class="w-full pl-3 pr-10 py-2 bg-white border border-slate-300 rounded-lg text-slate-900 focus:outline-none focus:border-purple-600 transition">
                        <button type="button" class="password-toggle-btn absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600 focus:outline-none transition" aria-label="Toggle password visibility">
                            <i class="fa-solid fa-eye text-xs"></i>
                        </button>
                    </div>
                </div>
            </div>

            <div class="pt-3 border-t border-slate-200 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <span class="text-[11px] text-slate-500">
                    <i class="fa-solid fa-shield-halved text-purple-600 mr-1"></i> Passwords are encrypted with standard BCRYPT hashing.
                </span>
                <button type="submit" class="px-5 py-2 bg-purple-700 hover:bg-purple-800 text-white font-semibold rounded-lg shadow-sm transition flex items-center justify-center">
                    <i class="fa-solid fa-user-plus mr-1.5"></i> Create Administrator Account
                </button>
            </div>
        </form>
    </div>

    <!-- 3. Registered Administrators Directory Table -->
    <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm space-y-4">
        <div class="flex items-center justify-between pb-3 border-b border-slate-200">
            <div class="flex items-center space-x-2.5">
                <div class="w-8 h-8 rounded-lg bg-slate-100 text-slate-700 flex items-center justify-center text-sm font-bold">
                    <i class="fa-solid fa-users-gear"></i>
                </div>
                <div>
                    <h2 class="text-sm font-bold text-slate-900 uppercase tracking-wider">Registered Administrators (<?= count($allAdmins) ?>)</h2>
                    <p class="text-[11px] text-slate-500">Accounts authorized to access and manage the NextGen Admin Portal.</p>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto rounded-lg border border-slate-200">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-600 font-bold border-b border-slate-200">
                    <tr>
                        <th class="px-4 py-2.5">Administrator</th>
                        <th class="px-4 py-2.5">Email Address</th>
                        <th class="px-4 py-2.5">Assigned Role</th>
                        <th class="px-4 py-2.5">Last Sign In</th>
                        <th class="px-4 py-2.5 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    <?php foreach ($allAdmins as $u): 
                        $isSelf = ((int)$u['id'] === (int)$admin['id'] || $u['username'] === $admin['username']);
                        $initials = '';
                        $parts = preg_split('/\s+/', trim($u['full_name'] ?? ''));
                        if (count($parts) >= 2) {
                            $initials = strtoupper(substr($parts[0], 0, 1) . substr(end($parts), 0, 1));
                        } else {
                            $initials = strtoupper(substr($u['username'] ?? 'AD', 0, 2));
                        }
                    ?>
                    <tr class="hover:bg-slate-50/70 transition">
                        <td class="px-4 py-3">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 rounded-full bg-slate-800 text-white font-bold flex items-center justify-center text-xs shrink-0">
                                    <?= htmlspecialchars($initials) ?>
                                </div>
                                <div>
                                    <span class="font-bold text-slate-900 block"><?= htmlspecialchars($u['full_name'] ?? $u['username']) ?></span>
                                    <span class="font-mono text-[11px] text-slate-500">@<?= htmlspecialchars($u['username']) ?></span>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-slate-600 font-medium">
                            <a href="mailto:<?= htmlspecialchars($u['email']) ?>" class="hover:text-emerald-700 underline decoration-slate-300">
                                <?= htmlspecialchars($u['email']) ?>
                            </a>
                        </td>
                        <td class="px-4 py-3">
                            <?php if (($u['role'] ?? '') === 'Super Admin'): ?>
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-purple-50 text-purple-700 border border-purple-200">
                                    <i class="fa-solid fa-crown mr-1 text-[9px]"></i> Super Admin
                                </span>
                            <?php else: ?>
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-blue-50 text-blue-700 border border-blue-200">
                                    <i class="fa-solid fa-user-pen mr-1 text-[9px]"></i> Editor
                                </span>
                            <?php endif; ?>
                        </td>
                        <td class="px-4 py-3 text-slate-500 text-[11px]">
                            <?php if (!empty($u['last_login'])): ?>
                                <span title="<?= htmlspecialchars($u['last_login']) ?>">
                                    <i class="fa-regular fa-clock text-slate-400 mr-1"></i>
                                    <?= date('M j, Y g:i A', strtotime($u['last_login'])) ?>
                                </span>
                            <?php else: ?>
                                <span class="text-slate-400 italic">Never signed in</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-4 py-3 text-right">
                            <?php if ($isSelf): ?>
                                <span class="inline-flex items-center px-2.5 py-1 rounded-md text-[10px] font-bold bg-emerald-50 text-emerald-800 border border-emerald-200">
                                    <i class="fa-solid fa-check-circle mr-1"></i> You (Active)
                                </span>
                            <?php else: ?>
                                <form method="POST" action="settings.php" class="inline" onsubmit="return confirm('Are you sure you want to delete administrator \'<?= htmlspecialchars(addslashes($u['full_name'] ?? $u['username'])) ?>\' (@<?= htmlspecialchars(addslashes($u['username'])) ?>)? This action cannot be reversed.');">
                                    <input type="hidden" name="action" value="delete_admin">
                                    <input type="hidden" name="admin_id" value="<?= (int)$u['id'] ?>">
                                    <button type="submit" class="px-2.5 py-1 bg-red-50 hover:bg-red-100 text-red-700 font-semibold rounded text-[11px] border border-red-200 transition">
                                        <i class="fa-solid fa-trash-can mr-1"></i> Delete
                                    </button>
                                </form>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- 4. Database Connectivity & Diagnostics -->
    <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm space-y-4">
        <div class="flex items-center space-x-2.5 pb-3 border-b border-slate-200">
            <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-700 flex items-center justify-center text-sm font-bold">
                <i class="fa-solid fa-server"></i>
            </div>
            <div>
                <h2 class="text-sm font-bold text-slate-900 uppercase tracking-wider">Database Diagnostics & Table Registry</h2>
                <p class="text-[11px] text-slate-500">Live configuration and row counts from MySQL schema.</p>
            </div>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 text-xs">
            <div class="p-3 rounded-lg bg-slate-50 border border-slate-200">
                <span class="text-slate-400 block font-semibold text-[10px] uppercase">Host & Port</span>
                <span class="font-mono font-bold text-slate-800"><?= htmlspecialchars(DB_HOST) ?>:<?= htmlspecialchars(DB_PORT) ?></span>
            </div>
            <div class="p-3 rounded-lg bg-slate-50 border border-slate-200">
                <span class="text-slate-400 block font-semibold text-[10px] uppercase">Database Name</span>
                <span class="font-mono font-bold text-slate-800"><?= htmlspecialchars(DB_NAME) ?></span>
            </div>
            <div class="p-3 rounded-lg bg-slate-50 border border-slate-200">
                <span class="text-slate-400 block font-semibold text-[10px] uppercase">Database User</span>
                <span class="font-mono font-bold text-slate-800"><?= htmlspecialchars(DB_USER) ?></span>
            </div>
            <div class="p-3 rounded-lg bg-slate-50 border border-slate-200">
                <span class="text-slate-400 block font-semibold text-[10px] uppercase">Connection State</span>
                <span class="font-bold <?= $dbConnected ? 'text-emerald-700' : 'text-amber-700' ?>">
                    <?= $dbConnected ? 'Online (Active)' : 'Fallback Cache' ?>
                </span>
            </div>
        </div>

        <?php if ($dbConnected && !empty($tableCounts)): ?>
            <div class="pt-2">
                <h3 class="text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Live Table Row Registry</h3>
                <div class="overflow-x-auto rounded-lg border border-slate-200">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-slate-50 text-slate-600 font-bold border-b border-slate-200">
                            <tr>
                                <th class="px-4 py-2">Table Name</th>
                                <th class="px-4 py-2">Purpose / Scope</th>
                                <th class="px-4 py-2 text-right">Row Count</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 bg-white">
                            <tr>
                                <td class="px-4 py-2 font-mono font-semibold text-slate-800">admin_users</td>
                                <td class="px-4 py-2 text-slate-500">Administrator authentication credentials</td>
                                <td class="px-4 py-2 text-right font-mono font-bold text-slate-900"><?= $tableCounts['admin_users'] ?? 0 ?></td>
                            </tr>
                            <tr>
                                <td class="px-4 py-2 font-mono font-semibold text-slate-800">site_settings</td>
                                <td class="px-4 py-2 text-slate-500">Dynamic website content, headlines & metrics</td>
                                <td class="px-4 py-2 text-right font-mono font-bold text-slate-900"><?= $tableCounts['site_settings'] ?? 0 ?></td>
                            </tr>
                            <tr>
                                <td class="px-4 py-2 font-mono font-semibold text-slate-800">programs</td>
                                <td class="px-4 py-2 text-slate-500">Incubation tracks, accelerator cohorts & funding</td>
                                <td class="px-4 py-2 text-right font-mono font-bold text-slate-900"><?= $tableCounts['programs'] ?? 0 ?></td>
                            </tr>
                            <tr>
                                <td class="px-4 py-2 font-mono font-semibold text-slate-800">hub_submissions</td>
                                <td class="px-4 py-2 text-slate-500">Incoming applicants, investor inquiries & proposals</td>
                                <td class="px-4 py-2 text-right font-mono font-bold text-slate-900"><?= $tableCounts['hub_submissions'] ?? 0 ?></td>
                            </tr>
                            <tr>
                                <td class="px-4 py-2 font-mono font-semibold text-slate-800">newsletter_subscribers</td>
                                <td class="px-4 py-2 text-slate-500">Agri-dispatch monthly newsletter subscribers</td>
                                <td class="px-4 py-2 text-right font-mono font-bold text-slate-900"><?= $tableCounts['newsletter_subscribers'] ?? 0 ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php endif; ?>

    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.alert-close-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const box = this.closest('.alert-box');
            if (box) box.style.display = 'none';
        });
    });

    document.querySelectorAll('.password-toggle-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const input = this.parentElement.querySelector('input');
            const icon = this.querySelector('i');
            if (!input) return;
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });
    });

    // -------------------------------------------------------------
    // Dynamic Brand Logos Live Preview & Interactions
    // -------------------------------------------------------------
    const siteFileInput     = document.getElementById('site_logo_file');
    const sitePreviewImg    = document.getElementById('site-logo-preview-img');
    const siteFallbackBrand = document.getElementById('site-logo-fallback-brand');
    const siteStateBadge    = document.getElementById('site-logo-state-badge');
    const siteUrlInput      = document.getElementById('site_logo_url');

    if (siteFileInput) {
        siteFileInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(evt) {
                    if (sitePreviewImg) {
                        sitePreviewImg.src = evt.target.result;
                        sitePreviewImg.classList.remove('hidden');
                        sitePreviewImg.style.display = 'block';
                    }
                    if (siteFallbackBrand) siteFallbackBrand.style.display = 'none';
                    if (siteStateBadge) {
                        siteStateBadge.textContent = 'Selected: ' + file.name + ' (' + (file.size / 1024).toFixed(1) + ' KB)';
                        siteStateBadge.className = 'absolute bottom-1.5 right-2 text-[9px] font-mono font-semibold px-1.5 py-0.5 rounded bg-amber-100 text-amber-800 border border-amber-300';
                    }
                    // If login logo is synced, also mirror to login preview
                    const syncCheckbox = document.getElementById('sync_login_logo');
                    const loginPreviewImg = document.getElementById('login-logo-preview-img');
                    const loginFallbackBrand = document.getElementById('login-logo-fallback-brand');
                    if (syncCheckbox && syncCheckbox.checked && loginPreviewImg) {
                        loginPreviewImg.src = evt.target.result;
                        loginPreviewImg.classList.remove('hidden');
                        loginPreviewImg.style.display = 'block';
                        if (loginFallbackBrand) loginFallbackBrand.style.display = 'none';
                    }
                };
                reader.readAsDataURL(file);
            }
        });
    }

    if (siteUrlInput) {
        siteUrlInput.addEventListener('input', function() {
            const val = this.value.trim();
            if (val && sitePreviewImg) {
                sitePreviewImg.src = val;
                sitePreviewImg.classList.remove('hidden');
                sitePreviewImg.style.display = 'block';
                if (siteFallbackBrand) siteFallbackBrand.style.display = 'none';
                if (siteStateBadge) {
                    siteStateBadge.textContent = 'URL Specified';
                    siteStateBadge.className = 'absolute bottom-1.5 right-2 text-[9px] font-mono font-semibold px-1.5 py-0.5 rounded bg-blue-100 text-blue-800 border border-blue-300';
                }
            }
        });
    }

    // Light / Dark mode switcher for Site Logo Preview
    const btnLight   = document.getElementById('site-logo-bg-light');
    const btnDark    = document.getElementById('site-logo-bg-dark');
    const previewBox = document.getElementById('site-logo-preview-box');

    if (btnLight && btnDark && previewBox) {
        btnLight.addEventListener('click', function() {
            previewBox.classList.remove('bg-slate-900', 'border-slate-800');
            previewBox.classList.add('bg-white', 'border-slate-200');
            btnLight.classList.add('bg-white', 'text-slate-800', 'shadow-xs');
            btnLight.classList.remove('text-slate-600');
            btnDark.classList.remove('bg-slate-800', 'text-white', 'shadow-xs');
            btnDark.classList.add('text-slate-600');
            const brandTexts = previewBox.querySelectorAll('.brand-preview-text');
            brandTexts.forEach(el => el.classList.remove('text-white'));
        });
        btnDark.addEventListener('click', function() {
            previewBox.classList.remove('bg-white', 'border-slate-200');
            previewBox.classList.add('bg-slate-900', 'border-slate-800');
            btnDark.classList.add('bg-slate-800', 'text-white', 'shadow-xs');
            btnDark.classList.remove('text-slate-600');
            btnLight.classList.remove('bg-white', 'text-slate-800', 'shadow-xs');
            btnLight.classList.add('text-slate-600');
            const brandTexts = previewBox.querySelectorAll('.brand-preview-text');
            brandTexts.forEach(el => el.classList.add('text-white'));
        });
    }

    // Login Logo Sync toggle and file upload preview
    const syncCheckbox      = document.getElementById('sync_login_logo');
    const customLoginInputs = document.getElementById('login-logo-custom-inputs');
    const loginPreviewImg   = document.getElementById('login-logo-preview-img');
    const loginFallbackBrand= document.getElementById('login-logo-fallback-brand');
    const loginStateBadge   = document.getElementById('login-logo-state-badge');
    const loginFileInput    = document.getElementById('login_logo_file');
    const loginUrlInput     = document.getElementById('login_logo_url');

    if (syncCheckbox && customLoginInputs) {
        syncCheckbox.addEventListener('change', function() {
            if (this.checked) {
                customLoginInputs.classList.add('opacity-50', 'pointer-events-none');
                if (sitePreviewImg && sitePreviewImg.src && !sitePreviewImg.classList.contains('hidden') && sitePreviewImg.style.display !== 'none') {
                    loginPreviewImg.src = sitePreviewImg.src;
                    loginPreviewImg.classList.remove('hidden');
                    loginPreviewImg.style.display = 'block';
                    if (loginFallbackBrand) loginFallbackBrand.style.display = 'none';
                }
                if (loginStateBadge) {
                    loginStateBadge.textContent = 'Synced with Site Logo';
                    loginStateBadge.className = 'absolute bottom-1.5 right-2 text-[9px] font-mono font-semibold px-1.5 py-0.5 rounded bg-emerald-950 text-emerald-300 border border-emerald-800';
                }
            } else {
                customLoginInputs.classList.remove('opacity-50', 'pointer-events-none');
                if (loginStateBadge) {
                    loginStateBadge.textContent = 'Custom Login Logo Active';
                }
            }
        });
    }

    if (loginFileInput) {
        loginFileInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(evt) {
                    if (loginPreviewImg) {
                        loginPreviewImg.src = evt.target.result;
                        loginPreviewImg.classList.remove('hidden');
                        loginPreviewImg.style.display = 'block';
                    }
                    if (loginFallbackBrand) loginFallbackBrand.style.display = 'none';
                    if (loginStateBadge) {
                        loginStateBadge.textContent = 'Selected: ' + file.name + ' (' + (file.size / 1024).toFixed(1) + ' KB)';
                        loginStateBadge.className = 'absolute bottom-1.5 right-2 text-[9px] font-mono font-semibold px-1.5 py-0.5 rounded bg-amber-950 text-amber-300 border border-amber-800';
                    }
                };
                reader.readAsDataURL(file);
            }
        });
    }

    if (loginUrlInput) {
        loginUrlInput.addEventListener('input', function() {
            const val = this.value.trim();
            if (val && loginPreviewImg) {
                loginPreviewImg.src = val;
                loginPreviewImg.classList.remove('hidden');
                loginPreviewImg.style.display = 'block';
                if (loginFallbackBrand) loginFallbackBrand.style.display = 'none';
                if (loginStateBadge) {
                    loginStateBadge.textContent = 'URL Specified';
                    loginStateBadge.className = 'absolute bottom-1.5 right-2 text-[9px] font-mono font-semibold px-1.5 py-0.5 rounded bg-blue-950 text-blue-300 border border-blue-800';
                }
            }
        });
    }
});
</script>

<?php renderAdminFooter(); ?>
