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

renderAdminHeader('settings', 'Admin Profile & System Settings', 'Settings & DB');
?>

<div class="max-w-4xl space-y-6">
    
    <!-- Page Header Bar -->
    <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold text-slate-900 tracking-tight">System Settings & Security</h1>
            <p class="text-xs text-slate-500 mt-0.5">Manage administrator credentials, security access, and database diagnostics.</p>
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

    <!-- 1. Administrator Profile & Security Form -->
    <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm space-y-4">
        <div class="flex items-center space-x-2.5 pb-3 border-b border-slate-200">
            <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-700 flex items-center justify-center text-sm font-bold">
                <i class="fa-solid fa-user-shield"></i>
            </div>
            <div>
                <h2 class="text-sm font-bold text-slate-900 uppercase tracking-wider">Administrator Profile & Password</h2>
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
                    <input type="password" name="new_password" placeholder="••••••••" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-slate-900 focus:outline-none focus:border-emerald-600">
                </div>

                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Confirm New Password</label>
                    <input type="password" name="confirm_password" placeholder="••••••••" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-slate-900 focus:outline-none focus:border-emerald-600">
                </div>
            </div>

            <div class="pt-3 border-t border-slate-200 flex justify-end">
                <button type="submit" class="px-5 py-2 bg-emerald-700 hover:bg-emerald-800 text-white font-semibold rounded-lg shadow-sm transition">
                    Save Profile & Credentials
                </button>
            </div>
        </form>
    </div>

    <!-- 2. Database Connectivity & Diagnostics -->
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

<?php renderAdminFooter(); ?>
