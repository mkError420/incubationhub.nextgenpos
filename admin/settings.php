<?php
/**
 * NextGen Farming Incubation Hub - Admin Profile & Database Diagnostics
 */

require_once __DIR__ . '/auth.php';
requireAdminAuth();

$admin = getCurrentAdmin();
$successMsg = '';
$errorMsg = '';
$dbConnected = isDbConnected();

// Handle Profile & Password Update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_profile') {
    $fullName = trim($_POST['full_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $newPass = trim($_POST['new_password'] ?? '');
    $confirmPass = trim($_POST['confirm_password'] ?? '');

    if (!empty($newPass) && $newPass !== $confirmPass) {
        $errorMsg = 'New password and confirmation password do not match.';
    } else {
        if (updateAdminProfile($admin['id'], $newPass ?: null, $fullName ?: null, $email ?: null)) {
            // Update session values
            $_SESSION['admin_user']['full_name'] = $fullName;
            $_SESSION['admin_user']['email'] = $email;
            $admin = $_SESSION['admin_user'];
            $successMsg = 'Admin profile and credentials updated successfully!';
        } else {
            $errorMsg = 'Could not update profile. Please verify database connection.';
        }
    }
}

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

renderAdminHeader('settings', 'Admin Profile & System Settings');
?>

<div class="max-w-4xl space-y-8">
    
    <!-- Header -->
    <div>
        <h2 class="text-2xl font-black text-slate-900 tracking-tight">System Settings & Security</h2>
        <p class="text-xs sm:text-sm text-slate-500 mt-1">
            Manage your administrator credentials and inspect database connectivity.
        </p>
    </div>

    <?php if ($successMsg): ?>
        <div class="alert-box p-4 rounded-2xl bg-emerald-50 border border-emerald-300 text-emerald-900 text-xs flex items-center justify-between">
            <span class="font-bold flex items-center"><i class="fa-solid fa-circle-check text-emerald-600 mr-2 text-base"></i> <?= htmlspecialchars($successMsg) ?></span>
            <button type="button" class="alert-close-btn text-emerald-700 hover:text-emerald-950"><i class="fa-solid fa-xmark"></i></button>
        </div>
    <?php endif; ?>

    <?php if ($errorMsg): ?>
        <div class="alert-box p-4 rounded-2xl bg-red-50 border border-red-300 text-red-900 text-xs flex items-center justify-between">
            <span class="font-bold flex items-center"><i class="fa-solid fa-circle-exclamation text-red-600 mr-2 text-base"></i> <?= htmlspecialchars($errorMsg) ?></span>
            <button type="button" class="alert-close-btn text-red-700 hover:text-red-950"><i class="fa-solid fa-xmark"></i></button>
        </div>
    <?php endif; ?>

    <!-- 1. Administrator Profile & Security Form -->
    <div class="bg-white p-6 sm:p-8 rounded-3xl border border-gray-200 shadow-sm space-y-6">
        <div class="flex items-center space-x-3 pb-4 border-b border-gray-100">
            <div class="w-10 h-10 rounded-xl bg-agro-50 text-agro-700 flex items-center justify-center text-lg">
                <i class="fa-solid fa-user-shield"></i>
            </div>
            <div>
                <h3 class="text-base font-extrabold text-slate-900">Administrator Profile & Password</h3>
                <span class="text-xs text-slate-500">Update your access credentials and display name</span>
            </div>
        </div>

        <form method="POST" action="settings.php" class="space-y-5">
            <input type="hidden" name="action" value="update_profile">

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Username (Login ID)</label>
                    <input type="text" disabled value="<?= htmlspecialchars($admin['username']) ?>" class="w-full px-4 py-2.5 bg-gray-100 border border-gray-200 rounded-xl text-xs font-mono text-slate-500 cursor-not-allowed">
                    <span class="text-[10px] text-slate-400 mt-1 block">Username cannot be changed.</span>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Full Name</label>
                    <input type="text" name="full_name" value="<?= htmlspecialchars($admin['full_name']) ?>" required class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs text-slate-900 focus:outline-none focus:border-agro-700">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Email Address</label>
                    <input type="email" name="email" value="<?= htmlspecialchars($admin['email']) ?>" required class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs text-slate-900 focus:outline-none focus:border-agro-700">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">New Password (Leave blank to keep current)</label>
                    <input type="password" name="new_password" placeholder="Enter new password if changing" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs text-slate-900 focus:outline-none focus:border-agro-700">
                </div>
                <div class="sm:col-start-2">
                    <label class="block text-xs font-bold text-slate-700 mb-1">Confirm New Password</label>
                    <input type="password" name="confirm_password" placeholder="Re-type new password" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs text-slate-900 focus:outline-none focus:border-agro-700">
                </div>
            </div>

            <div class="pt-4 flex items-center justify-end">
                <button type="submit" class="px-6 py-2.5 bg-agro-800 hover:bg-agro-900 text-white font-bold text-xs rounded-xl shadow transition">
                    <i class="fa-solid fa-check mr-1.5"></i> Update Profile
                </button>
            </div>
        </form>
    </div>

    <!-- 2. Database Diagnostics Card -->
    <div class="bg-white p-6 sm:p-8 rounded-3xl border border-gray-200 shadow-sm space-y-6">
        <div class="flex items-center justify-between pb-4 border-b border-gray-100">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-700 flex items-center justify-center text-lg">
                    <i class="fa-solid fa-server"></i>
                </div>
                <div>
                    <h3 class="text-base font-extrabold text-slate-900">Database Connection Diagnostics</h3>
                    <span class="text-xs text-slate-500">Real-time parameters loaded from config.php</span>
                </div>
            </div>
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold <?= $dbConnected ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' ?>">
                <span class="w-2 h-2 rounded-full <?= $dbConnected ? 'bg-emerald-500 animate-pulse' : 'bg-amber-500' ?> mr-2"></span>
                <?= $dbConnected ? 'Active MySQL Live' : 'Fallback File Cache' ?>
            </span>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 text-xs">
            <div class="p-3.5 rounded-2xl bg-gray-50 border border-gray-100">
                <strong class="text-slate-400 block text-[10px] uppercase">Host:</strong>
                <span class="font-mono font-bold text-slate-800"><?= htmlspecialchars(DB_HOST) ?>:<?= htmlspecialchars(DB_PORT) ?></span>
            </div>
            <div class="p-3.5 rounded-2xl bg-gray-50 border border-gray-100">
                <strong class="text-slate-400 block text-[10px] uppercase">Database:</strong>
                <span class="font-mono font-bold text-slate-800 truncate block"><?= htmlspecialchars(DB_NAME) ?></span>
            </div>
            <div class="p-3.5 rounded-2xl bg-gray-50 border border-gray-100">
                <strong class="text-slate-400 block text-[10px] uppercase">Database User:</strong>
                <span class="font-mono font-bold text-slate-800 truncate block"><?= htmlspecialchars(DB_USER) ?></span>
            </div>
            <div class="p-3.5 rounded-2xl bg-gray-50 border border-gray-100">
                <strong class="text-slate-400 block text-[10px] uppercase">PHP Version:</strong>
                <span class="font-mono font-bold text-slate-800"><?= phpversion() ?></span>
            </div>
        </div>

        <?php if (!empty($tableCounts)): ?>
            <div class="pt-4 border-t border-gray-100">
                <h4 class="text-xs font-bold text-slate-700 mb-3 uppercase tracking-wider">Database Table Row Counts</h4>
                <div class="grid grid-cols-2 sm:grid-cols-5 gap-3">
                    <?php foreach ($tableCounts as $tbl => $cnt): ?>
                        <div class="p-3 rounded-xl bg-emerald-50/60 border border-emerald-100 text-center">
                            <span class="block text-[10px] font-mono font-bold text-emerald-900 truncate"><?= $tbl ?></span>
                            <span class="block text-base font-extrabold text-emerald-700 mt-1"><?= $cnt ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>

</div>

<?php renderAdminFooter(); ?>
