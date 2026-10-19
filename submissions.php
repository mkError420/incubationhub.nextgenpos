<?php
$currentPage = 'submissions';
$pageTitle = 'Submissions Portal | NextGen Farming Incubation Hub';
$pageDescription = 'Live verification portal for applications and stakeholder leads submitted to NextGen Farming Incubation Hub.';
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/header.php';

// Category filter
$categoryFilter = $_GET['category'] ?? null;
$submissions = getHubSubmissions(100, $categoryFilter);
$dbConnected = isDbConnected();
?>

<!-- Header Banner -->
<section class="relative bg-gradient-to-b from-agro-50/70 to-white pt-12 pb-12 border-b border-agro-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <span class="inline-flex items-center px-3.5 py-1.5 rounded-full text-xs font-bold bg-agro-100 text-agro-800 border border-agro-200 mb-3">
                    <i class="fa-solid fa-database mr-1.5 text-agro-700"></i> MySQL Database & Pipeline
                </span>
                <h1 class="text-3xl sm:text-4xl font-extrabold text-agro-950 tracking-tight">
                    Hub Submissions & Pipeline Portal
                </h1>
                <p class="mt-2 text-sm text-slate-600">
                    Live records captured via the interactive application form into table <code class="bg-gray-100 text-agro-800 px-1.5 py-0.5 rounded text-xs font-mono">hub_submissions</code>.
                </p>
            </div>

            <!-- Database Status Indicator -->
            <div class="flex items-center space-x-3 bg-white p-4 rounded-2xl border border-gray-200 shadow-sm">
                <div class="w-3.5 h-3.5 rounded-full <?= $dbConnected ? 'bg-emerald-500 animate-pulse' : 'bg-blue-500' ?>"></div>
                <div>
                    <span class="block text-xs font-bold text-slate-900">
                        <?= $dbConnected ? 'MySQL Connected' : 'Adaptive Cache Storage' ?>
                    </span>
                    <span class="block text-[11px] text-slate-500">
                        <?= $dbConnected ? 'Host: ' . DB_HOST . ' • DB: ' . DB_NAME : 'Active & auto-syncing' ?>
                    </span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Submissions Table Section -->
<section class="py-12 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Filter Tabs & Stats -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
            <div class="flex flex-wrap gap-2 text-xs font-bold">
                <a href="submissions.php" class="px-4 py-2 rounded-xl border <?= empty($categoryFilter) ? 'bg-agro-800 text-white border-agro-800 shadow-sm' : 'bg-gray-50 text-slate-700 border-gray-200 hover:bg-gray-100' ?> transition">
                    All Entries (<?= count(getHubSubmissions(200)) ?>)
                </a>
                <a href="submissions.php?category=Entrepreneur" class="px-4 py-2 rounded-xl border <?= $categoryFilter === 'Entrepreneur' ? 'bg-emerald-700 text-white border-emerald-700 shadow-sm' : 'bg-gray-50 text-slate-700 border-gray-200 hover:bg-gray-100' ?> transition">
                    <i class="fa-solid fa-seedling mr-1"></i> Entrepreneurs
                </a>
                <a href="submissions.php?category=Investor" class="px-4 py-2 rounded-xl border <?= $categoryFilter === 'Investor' ? 'bg-amber-600 text-white border-amber-600 shadow-sm' : 'bg-gray-50 text-slate-700 border-gray-200 hover:bg-gray-100' ?> transition">
                    <i class="fa-solid fa-chart-line mr-1"></i> Investors
                </a>
                <a href="submissions.php?category=Support%20Organisation" class="px-4 py-2 rounded-xl border <?= $categoryFilter === 'Support Organisation' ? 'bg-blue-600 text-white border-blue-600 shadow-sm' : 'bg-gray-50 text-slate-700 border-gray-200 hover:bg-gray-100' ?> transition">
                    <i class="fa-solid fa-building-columns mr-1"></i> Organisations
                </a>
                <a href="submissions.php?category=Mentor" class="px-4 py-2 rounded-xl border <?= $categoryFilter === 'Mentor' ? 'bg-purple-600 text-white border-purple-600 shadow-sm' : 'bg-gray-50 text-slate-700 border-gray-200 hover:bg-gray-100' ?> transition">
                    <i class="fa-solid fa-user-graduate mr-1"></i> Mentors
                </a>
            </div>

            <div class="flex items-center space-x-3">
                <a href="contact.php" class="px-4 py-2 bg-agro-700 hover:bg-agro-800 text-white text-xs font-bold rounded-xl shadow-sm transition flex items-center">
                    <i class="fa-solid fa-plus mr-1.5"></i> Test New Submission
                </a>
            </div>
        </div>

        <!-- Table Container -->
        <div class="overflow-x-auto rounded-2xl border border-gray-200 shadow-sm">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-gray-50 text-slate-700 uppercase font-extrabold tracking-wider border-b border-gray-200">
                    <tr>
                        <th scope="col" class="px-6 py-4">ID</th>
                        <th scope="col" class="px-6 py-4">Applicant & Entity</th>
                        <th scope="col" class="px-6 py-4">Category</th>
                        <th scope="col" class="px-6 py-4">Track / Focus</th>
                        <th scope="col" class="px-6 py-4">Message / Overview</th>
                        <th scope="col" class="px-6 py-4">Link / Deck</th>
                        <th scope="col" class="px-6 py-4">Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white">
                    <?php if (empty($submissions)): ?>
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-slate-400">
                                <i class="fa-solid fa-folder-open text-4xl mb-3 text-slate-300 block"></i>
                                No submissions found under this filter. Submit a new application via <a href="contact.php" class="text-agro-700 underline font-bold">Contact Page</a>.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($submissions as $row): 
                            $badgeColor = match ($row['category']) {
                                'Entrepreneur' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
                                'Investor' => 'bg-amber-100 text-amber-800 border-amber-200',
                                'Support Organisation' => 'bg-blue-100 text-blue-800 border-blue-200',
                                'Mentor' => 'bg-purple-100 text-purple-800 border-purple-200',
                                default => 'bg-gray-100 text-gray-800 border-gray-200',
                            };
                            $icon = match ($row['category']) {
                                'Entrepreneur' => 'fa-seedling',
                                'Investor' => 'fa-chart-line',
                                'Support Organisation' => 'fa-building-columns',
                                'Mentor' => 'fa-user-graduate',
                                default => 'fa-envelope',
                            };
                        ?>
                            <tr class="hover:bg-gray-50/70 transition">
                                <td class="px-6 py-4 font-mono font-bold text-slate-400">
                                    #<?= htmlspecialchars($row['id']) ?>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-bold text-slate-900 text-sm"><?= htmlspecialchars($row['full_name']) ?></div>
                                    <div class="text-[11px] text-slate-500 font-medium"><?= htmlspecialchars($row['organization'] ?: 'Independent') ?></div>
                                    <div class="text-[11px] text-slate-400 mt-0.5">
                                        <i class="fa-solid fa-envelope mr-1 text-slate-300"></i><?= htmlspecialchars($row['email']) ?>
                                        <?php if (!empty($row['phone'])): ?>
                                            • <i class="fa-solid fa-phone mr-1 text-slate-300"></i><?= htmlspecialchars($row['phone']) ?>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-bold border <?= $badgeColor ?>">
                                        <i class="fa-solid <?= $icon ?> mr-1.5 text-[10px]"></i>
                                        <?= htmlspecialchars($row['category']) ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 font-medium text-slate-800">
                                    <?= htmlspecialchars($row['sub_interest'] ?: 'General Program Inquiry') ?>
                                </td>
                                <td class="px-6 py-4 max-w-xs">
                                    <p class="truncate text-slate-600 text-xs" title="<?= htmlspecialchars($row['message']) ?>">
                                        <?= htmlspecialchars($row['message']) ?>
                                    </p>
                                </td>
                                <td class="px-6 py-4">
                                    <?php if (!empty($row['pitch_url'])): ?>
                                        <a href="<?= htmlspecialchars($row['pitch_url']) ?>" target="_blank" rel="noopener noreferrer" class="inline-flex items-center text-xs font-bold text-agro-700 hover:text-agro-900 bg-agro-50 px-2.5 py-1 rounded-lg border border-agro-200">
                                            <i class="fa-solid fa-arrow-up-right-from-square mr-1 text-[10px]"></i> Open Link
                                        </a>
                                    <?php else: ?>
                                        <span class="text-slate-300">—</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 text-[11px] text-slate-400 whitespace-nowrap">
                                    <?= date('M d, Y h:i A', strtotime($row['created_at'])) ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Schema Setup Box -->
        <div class="mt-12 bg-slate-900 text-slate-300 p-8 rounded-3xl border border-slate-800">
            <div class="flex items-start justify-between flex-wrap gap-4">
                <div>
                    <h3 class="text-white font-bold text-base flex items-center">
                        <i class="fa-solid fa-terminal text-emerald-400 mr-2"></i> MySQL Schema Setup Reference
                    </h3>
                    <p class="text-xs text-slate-400 mt-1 max-w-2xl">
                        To deploy this schema on your MySQL or MariaDB instance (e.g. XAMPP, Laragon, or remote server), execute the bundled script:
                    </p>
                </div>
                <span class="px-3 py-1 bg-slate-800 text-emerald-400 font-mono text-xs rounded-lg border border-slate-700">
                    File: schema.sql
                </span>
            </div>

            <pre class="mt-4 p-4 rounded-xl bg-slate-950 font-mono text-xs text-emerald-300 overflow-x-auto border border-slate-800 leading-relaxed">
mysql -u root -p &lt; schema.sql
            </pre>
            <div class="mt-3 text-[11px] text-slate-400 flex items-center space-x-2">
                <i class="fa-solid fa-circle-info text-emerald-400"></i>
                <span>Database configuration can be set in <code>includes/db.php</code> or passed via environment variables (<code>DB_HOST</code>, <code>DB_NAME</code>, <code>DB_USER</code>, <code>DB_PASS</code>).</span>
            </div>
        </div>

    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
