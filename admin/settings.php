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
}

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
});
</script>

<?php renderAdminFooter(); ?>
