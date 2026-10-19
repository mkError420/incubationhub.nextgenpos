<?php
$currentPage = 'networking';
$pageTitle = 'Global AgriTech Network & Ecosystem Partners | NextGen Farming Hub';
$pageDescription = 'Connect with premier agri-founders, venture funds, agricultural research universities, and corporate off-takers across Bangladesh and emerging markets.';
require_once __DIR__ . '/includes/header.php';

// Dynamic CMS content
$netBadge          = getSiteSetting('networking_badge', 'Connecting Entrepreneur Ecosystem Builders');
$netTitleMain      = getSiteSetting('networking_title_main', 'A Connected Web of');
$netTitleHighlight = getSiteSetting('networking_title_highlight', 'Founders, Investors & Scientists');
$netDesc           = getSiteSetting('networking_desc', 'Agriculture cannot be transformed in isolation. NextGen Farming Incubation Hub weaves together Bangladesh’s rural innovators, leading agronomy universities, regional venture syndicates, and global emerging market networks.');

$netStat1Num   = getSiteSetting('network_stat1_num', '5,200+');
$netStat1Label = getSiteSetting('network_stat1_label', 'Network Members');
$netStat1Desc  = getSiteSetting('network_stat1_desc', 'Across all 64 agricultural districts');

$netStat2Num   = getSiteSetting('network_stat2_num', '128+');
$netStat2Label = getSiteSetting('network_stat2_label', 'Ventures Accelerated');
$netStat2Desc  = getSiteSetting('network_stat2_desc', 'High-impact AgriTech solutions');

$netStat3Num   = getSiteSetting('network_stat3_num', '48+');
$netStat3Label = getSiteSetting('network_stat3_label', 'Institutional Partners');
$netStat3Desc  = getSiteSetting('network_stat3_desc', 'Universities, corporates & donors');

$netStat4Num   = getSiteSetting('network_stat4_num', '18');
$netStat4Label = getSiteSetting('network_stat4_label', 'Global Corridors');
$netStat4Desc  = getSiteSetting('network_stat4_desc', 'South Asia, Netherlands & Africa');

$activePartners = getNetworkPartners(true);
?>

<!-- Header Hero Banner -->
<section class="relative bg-gradient-to-b from-agro-50/80 via-white to-white pt-16 pb-20 border-b border-agro-100 overflow-hidden">
    <!-- Ambient Glow Backgrounds -->
    <div class="absolute top-0 right-10 w-96 h-96 bg-emerald-400/10 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute bottom-0 left-10 w-80 h-80 bg-blue-300/15 rounded-full blur-3xl pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="max-w-3xl">
            <span class="inline-flex items-center px-3.5 py-1.5 rounded-full text-xs font-bold bg-agro-100 text-agro-800 border border-agro-200 mb-4">
                <i class="fa-solid fa-network-wired mr-1.5 text-agro-700"></i> <?= htmlspecialchars($netBadge) ?>
            </span>
            <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-agro-950 tracking-tight leading-tight">
                <?= htmlspecialchars($netTitleMain) ?> <span class="text-gradient-emerald"><?= htmlspecialchars($netTitleHighlight) ?></span>
            </h1>
            <p class="mt-4 text-base sm:text-lg text-slate-600 leading-relaxed">
                <?= htmlspecialchars($netDesc) ?>
            </p>
            <div class="pt-6 flex flex-wrap gap-4">
                <a href="contact.php" class="inline-flex items-center px-6 py-3.5 rounded-xl font-bold text-white bg-agro-800 hover:bg-agro-900 shadow-lg shadow-agro-800/20 transition transform hover:-translate-y-0.5 text-sm">
                    <i class="fa-solid fa-user-plus mr-2 text-emerald-300"></i> Join the Ecosystem
                </a>
                <a href="#nodes" class="inline-flex items-center px-6 py-3.5 rounded-xl font-bold text-slate-700 bg-white hover:bg-agro-50 border border-gray-200 shadow-sm transition text-sm">
                    <i class="fa-solid fa-globe mr-2 text-agro-700"></i> Regional Living Nodes
                </a>
            </div>
        </div>

        <!-- Global Network Quick Stats Bar (Dynamic from Admin Dashboard) -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 pt-16 border-t border-gray-100 mt-16">
            <div class="p-6 rounded-2xl bg-white border border-gray-100 shadow-sm">
                <span class="text-3xl font-extrabold text-agro-900"><?= htmlspecialchars($netStat1Num) ?></span>
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mt-1"><?= htmlspecialchars($netStat1Label) ?></p>
                <p class="text-[11px] text-slate-400 mt-1"><?= htmlspecialchars($netStat1Desc) ?></p>
            </div>
            <div class="p-6 rounded-2xl bg-white border border-gray-100 shadow-sm">
                <span class="text-3xl font-extrabold text-agro-900"><?= htmlspecialchars($netStat2Num) ?></span>
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mt-1"><?= htmlspecialchars($netStat2Label) ?></p>
                <p class="text-[11px] text-slate-400 mt-1"><?= htmlspecialchars($netStat2Desc) ?></p>
            </div>
            <div class="p-6 rounded-2xl bg-white border border-gray-100 shadow-sm">
                <span class="text-3xl font-extrabold text-agro-900"><?= htmlspecialchars($netStat3Num) ?></span>
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mt-1"><?= htmlspecialchars($netStat3Label) ?></p>
                <p class="text-[11px] text-slate-400 mt-1"><?= htmlspecialchars($netStat3Desc) ?></p>
            </div>
            <div class="p-6 rounded-2xl bg-white border border-gray-100 shadow-sm">
                <span class="text-3xl font-extrabold text-agro-900"><?= htmlspecialchars($netStat4Num) ?></span>
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mt-1"><?= htmlspecialchars($netStat4Label) ?></p>
                <p class="text-[11px] text-slate-400 mt-1"><?= htmlspecialchars($netStat4Desc) ?></p>
            </div>
        </div>

    </div>
</section>

<!-- Interactive Network Directory Section -->
<section class="py-16 bg-white border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="text-center max-w-2xl mx-auto mb-10">
            <span class="text-xs font-bold uppercase tracking-wider text-agro-700">Ecosystem Directory</span>
            <h2 class="text-3xl font-extrabold text-slate-900 mt-1">Our Partner & Stakeholder Alliances</h2>
            <p class="mt-2 text-sm text-slate-500">Filter through our multi-stakeholder ecosystem spanning entrepreneurs, capital, research, and industry.</p>
        </div>

        <!-- Filter Tab Buttons -->
        <div class="flex flex-wrap items-center justify-center gap-2 sm:gap-3 mb-12" id="network-filter-buttons">
            <button type="button" data-filter="all" class="net-btn active px-4 py-2 rounded-xl text-xs sm:text-sm font-bold bg-agro-800 text-white shadow-sm transition">
                All Partners
            </button>
            <button type="button" data-filter="founder" class="net-btn px-4 py-2 rounded-xl text-xs sm:text-sm font-bold bg-gray-100 text-slate-700 hover:bg-agro-50 hover:text-agro-800 transition">
                <i class="fa-solid fa-rocket mr-1 text-agro-600"></i> Agri-Founders
            </button>
            <button type="button" data-filter="investor" class="net-btn px-4 py-2 rounded-xl text-xs sm:text-sm font-bold bg-gray-100 text-slate-700 hover:bg-agro-50 hover:text-agro-800 transition">
                <i class="fa-solid fa-coins mr-1 text-agro-600"></i> Venture Capital
            </button>
            <button type="button" data-filter="academic" class="net-btn px-4 py-2 rounded-xl text-xs sm:text-sm font-bold bg-gray-100 text-slate-700 hover:bg-agro-50 hover:text-agro-800 transition">
                <i class="fa-solid fa-graduation-cap mr-1 text-agro-600"></i> Academic & R&D
            </button>
            <button type="button" data-filter="corporate" class="net-btn px-4 py-2 rounded-xl text-xs sm:text-sm font-bold bg-gray-100 text-slate-700 hover:bg-agro-50 hover:text-agro-800 transition">
                <i class="fa-solid fa-building mr-1 text-agro-600"></i> Corporate Agribusiness
            </button>
            <button type="button" data-filter="impact" class="net-btn px-4 py-2 rounded-xl text-xs sm:text-sm font-bold bg-gray-100 text-slate-700 hover:bg-agro-50 hover:text-agro-800 transition">
                <i class="fa-solid fa-hand-holding-heart mr-1 text-agro-600"></i> Multilateral & Donors
            </button>
        </div>

        <!-- Directory Cards Grid (Dynamic from Database & Admin Dashboard) -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8" id="network-cards-grid">
            <?php foreach ($activePartners as $p): 
                $cat = htmlspecialchars($p['category'] ?? 'founder');

                $badgeColor = match ($p['category'] ?? 'founder') {
                    'investor' => 'bg-amber-100 text-amber-900',
                    'academic' => 'bg-blue-100 text-blue-900',
                    'corporate' => 'bg-purple-100 text-purple-900',
                    'impact' => 'bg-red-100 text-red-900',
                    default => 'bg-emerald-100 text-emerald-800'
                };
                $iconBg = match ($p['category'] ?? 'founder') {
                    'investor' => 'bg-amber-600 text-white',
                    'academic' => 'bg-blue-700 text-white',
                    'corporate' => 'bg-purple-700 text-white',
                    'impact' => 'bg-red-700 text-white',
                    default => 'bg-agro-700 text-white'
                };
                $tagColor = match ($p['category'] ?? 'founder') {
                    'investor' => 'text-amber-700',
                    'academic' => 'text-blue-700',
                    'corporate' => 'text-purple-700',
                    'impact' => 'text-red-700',
                    default => 'text-agro-700'
                };
                $linkHover = match ($p['category'] ?? 'founder') {
                    'investor' => 'text-amber-800',
                    'academic' => 'text-blue-800',
                    'corporate' => 'text-purple-800',
                    'impact' => 'text-red-800',
                    default => 'text-agro-800'
                };
            ?>
            <div class="net-card bg-[#fbfdfc] rounded-3xl p-7 border border-gray-200/80 shadow-sm hover:shadow-xl hover:border-agro-300 transition duration-300 flex flex-col justify-between" data-category="<?= $cat ?>">
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <span class="px-2.5 py-1 font-bold text-[11px] rounded-full <?= $badgeColor ?>">
                            <?= htmlspecialchars($p['badge'] ?? 'Portfolio Startup') ?>
                        </span>
                        <span class="text-xs text-slate-400">
                            <i class="fa-solid fa-location-dot mr-1"></i> <?= htmlspecialchars($p['location'] ?? 'Dhaka') ?>
                        </span>
                    </div>

                    <div class="flex items-center space-x-3 mb-4">
                        <div class="w-12 h-12 rounded-2xl flex items-center justify-center text-xl font-black shrink-0 <?= $iconBg ?>">
                            <i class="fa-solid <?= htmlspecialchars($p['icon'] ?? 'fa-seedling') ?>"></i>
                        </div>
                        <div>
                            <h3 class="font-extrabold text-slate-900 text-lg"><?= htmlspecialchars($p['name']) ?></h3>
                            <span class="text-xs font-semibold <?= $tagColor ?>"><?= htmlspecialchars($p['tagline'] ?? '') ?></span>
                        </div>
                    </div>

                    <p class="text-xs text-slate-600 leading-relaxed mb-4">
                        <?= htmlspecialchars($p['description']) ?>
                    </p>
                </div>

                <div class="pt-4 border-t border-gray-100 flex items-center justify-between text-xs">
                    <span class="text-slate-500 font-medium"><?= htmlspecialchars($p['meta_label'] ?? 'Partner') ?></span>
                    <a href="<?= htmlspecialchars($p['link_url'] ?? 'contact.php') ?>" class="font-bold hover:underline <?= $linkHover ?>">
                        <?= htmlspecialchars($p['link_text'] ?? 'Connect →') ?>
                    </a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

    </div>
</section>

<!-- Regional Living Nodes Section -->
<section id="nodes" class="py-20 bg-agro-50/50 border-b border-agro-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="text-center max-w-3xl mx-auto mb-16">
            <span class="text-xs font-bold uppercase tracking-wider text-agro-700">Geographic Footprint</span>
            <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900 mt-2">Regional Living Nodes & Farmland Stations</h2>
            <p class="mt-3 text-slate-600 text-sm sm:text-base">
                Our physical infrastructure is intentionally distributed across distinct agro-ecological zones to rigorously test technologies where challenges are most acute.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            
            <!-- Node 1: Dhaka HQ -->
            <div class="bg-white rounded-3xl p-6 border border-agro-200/80 shadow-sm space-y-4">
                <div class="w-12 h-12 rounded-2xl bg-agro-800 text-white flex items-center justify-center text-xl">
                    <i class="fa-solid fa-building"></i>
                </div>
                <div>
                    <h4 class="font-extrabold text-lg text-slate-900">Dhaka Central Hub</h4>
                    <span class="text-xs text-agro-700 font-semibold">Gulshan Venture Studio</span>
                </div>
                <p class="text-xs text-slate-600 leading-relaxed">
                    Headquarters housing venture acceleration, mentor clinics, IP legal desks, and corporate syndicate pitch suites.
                </p>
                <div class="pt-2 text-xs text-slate-400 flex items-center">
                    <i class="fa-solid fa-location-dot mr-1 text-agro-600"></i> Level 7, Venture Tower
                </div>
            </div>

            <!-- Node 2: Bogura Station -->
            <div class="bg-white rounded-3xl p-6 border border-agro-200/80 shadow-sm space-y-4">
                <div class="w-12 h-12 rounded-2xl bg-emerald-700 text-white flex items-center justify-center text-xl">
                    <i class="fa-solid fa-tractor"></i>
                </div>
                <div>
                    <h4 class="font-extrabold text-lg text-slate-900">Bogura Agro-Node</h4>
                    <span class="text-xs text-emerald-700 font-semibold">Horticulture Living Lab</span>
                </div>
                <p class="text-xs text-slate-600 leading-relaxed">
                    250+ acres of high-density potato, chili, and vegetable testbeds with direct link to 500+ farmer cooperatives.
                </p>
                <div class="pt-2 text-xs text-slate-400 flex items-center">
                    <i class="fa-solid fa-location-dot mr-1 text-emerald-600"></i> Sherpur Highway Corridor
                </div>
            </div>

            <!-- Node 3: Rajshahi Station -->
            <div class="bg-white rounded-3xl p-6 border border-agro-200/80 shadow-sm space-y-4">
                <div class="w-12 h-12 rounded-2xl bg-amber-600 text-white flex items-center justify-center text-xl">
                    <i class="fa-solid fa-sun"></i>
                </div>
                <div>
                    <h4 class="font-extrabold text-lg text-slate-900">Barind Tract Node</h4>
                    <span class="text-xs text-amber-700 font-semibold">Drought & Precision Irrigation</span>
                </div>
                <p class="text-xs text-slate-600 leading-relaxed">
                    Testing solar drip irrigation, IoT water meters, and drought-tolerant seed genomics across arid clay topography.
                </p>
                <div class="pt-2 text-xs text-slate-400 flex items-center">
                    <i class="fa-solid fa-location-dot mr-1 text-amber-600"></i> Godagari Regional Lab
                </div>
            </div>

            <!-- Node 4: Khulna Coastal Node -->
            <div class="bg-white rounded-3xl p-6 border border-agro-200/80 shadow-sm space-y-4">
                <div class="w-12 h-12 rounded-2xl bg-teal-700 text-white flex items-center justify-center text-xl">
                    <i class="fa-solid fa-water"></i>
                </div>
                <div>
                    <h4 class="font-extrabold text-lg text-slate-900">Coastal Saline Node</h4>
                    <span class="text-xs text-teal-700 font-semibold">Delta & Aqua Resilience</span>
                </div>
                <p class="text-xs text-slate-600 leading-relaxed">
                    Pioneering saline-adapted aquaculture, mangrove bio-fertilizers, and cyclone-resilient greenhouse prototypes.
                </p>
                <div class="pt-2 text-xs text-slate-400 flex items-center">
                    <i class="fa-solid fa-location-dot mr-1 text-teal-600"></i> Dumuria Delta Testbed
                </div>
            </div>

        </div>

    </div>
</section>

<!-- Call to Action Banner -->
<section class="py-20 bg-gradient-to-br from-agro-950 via-slate-900 to-agro-900 text-white">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 text-center space-y-6">
        <span class="inline-flex items-center px-3.5 py-1.5 rounded-full text-xs font-bold bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">
            <i class="fa-solid fa-handshake-angle mr-1.5"></i> Multi-Stakeholder Coalition
        </span>
        <h2 class="text-3xl sm:text-4xl font-extrabold tracking-tight">
            Ready to Connect with NextGen Farming Hub?
        </h2>
        <p class="text-slate-300 text-sm sm:text-base max-w-2xl mx-auto leading-relaxed">
            Whether you are an ambitious AgriTech founder, venture capital investor, research institute, or corporate buyer, there is a designated integration track for you.
        </p>
        <div class="pt-4 flex flex-wrap justify-center gap-4">
            <a href="contact.php?category=Entrepreneur" class="inline-flex items-center px-6 py-3.5 rounded-xl font-bold text-agro-950 bg-emerald-400 hover:bg-emerald-300 transition text-sm shadow-lg">
                <i class="fa-solid fa-seedling mr-2"></i> Apply as a Startup
            </a>
            <a href="contact.php?category=Investor" class="inline-flex items-center px-6 py-3.5 rounded-xl font-bold text-white bg-slate-800 hover:bg-slate-700 border border-slate-700 transition text-sm">
                <i class="fa-solid fa-coins mr-2 text-amber-400"></i> Co-Invest with Syndicate
            </a>
            <a href="contact.php?category=Support%20Organisation" class="inline-flex items-center px-6 py-3.5 rounded-xl font-bold text-white bg-slate-800 hover:bg-slate-700 border border-slate-700 transition text-sm">
                <i class="fa-solid fa-building mr-2 text-blue-400"></i> Corporate Partnership
            </a>
        </div>
    </div>
</section>

<script>
// Filter Tab Interactivity for Network Directory
document.addEventListener('DOMContentLoaded', function() {
    const buttons = document.querySelectorAll('#network-filter-buttons .net-btn');
    const cards = document.querySelectorAll('#network-cards-grid .net-card');

    buttons.forEach(btn => {
        btn.addEventListener('click', function() {
            buttons.forEach(b => {
                b.classList.remove('bg-agro-800', 'text-white');
                b.classList.add('bg-gray-100', 'text-slate-700');
            });
            this.classList.remove('bg-gray-100', 'text-slate-700');
            this.classList.add('bg-agro-800', 'text-white');

            const filter = this.getAttribute('data-filter');

            cards.forEach(card => {
                const category = card.getAttribute('data-category');
                if (filter === 'all' || category === filter) {
                    card.classList.remove('hidden');
                } else {
                    card.classList.add('hidden');
                }
            });
        });
    });
});
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
