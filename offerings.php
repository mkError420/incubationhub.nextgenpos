<?php
$currentPage = 'offerings';
$pageTitle = 'Core Offerings & Incubation Infrastructure | NextGen Farming Hub';
$pageDescription = 'Explore the full suite of venture studio capabilities, living farmland testbeds, biosafety certifications, catalytic capital syndication, and enterprise off-take bridges at NextGen Farming Incubation Hub.';
require_once __DIR__ . '/includes/header.php';

// Dynamic CMS content
$offBadge          = getSiteSetting('offerings_badge', 'Venture Studio & Technical Infrastructure');
$offTitleMain      = getSiteSetting('offerings_title_main', 'Full-Stack Capabilities Built for');
$offTitleHighlight = getSiteSetting('offerings_title_highlight', 'Agricultural Transformation');
$offDesc           = getSiteSetting('offerings_desc', 'Generalist tech accelerators cannot solve the physical complexities of biology, volatile weather, rural logistics, and fragmented middleman chains. NextGen Farming Hub provides purpose-built technical, empirical, and commercial infrastructure tailored for emerging deltaic markets.');

$offStat1Num  = getSiteSetting('offering_stat1_num', '500+ Farmland Acres');
$offStat1Desc = getSiteSetting('offering_stat1_desc', 'Active living labs in Bogura, Khulna & Rajshahi.');
$offStat2Num  = getSiteSetting('offering_stat2_num', '48+ Off-Take Channels');
$offStat2Desc = getSiteSetting('offering_stat2_desc', 'Direct procurement bridges with leading agribusinesses.');
$offStat3Num  = getSiteSetting('offering_stat3_num', '$28.5M+ Syndicated Capital');
$offStat3Desc = getSiteSetting('offering_stat3_desc', 'Pre-seed, angel syndicate & international VC co-investment.');
$offStat4Num  = getSiteSetting('offering_stat4_num', '14-Day Regulatory Sandbox');
$offStat4Desc = getSiteSetting('offering_stat4_desc', 'Streamlined DAE, seed certification, and bio-input clearance.');

$activeOfferings = getOfferings(true);
?>

<!-- Header Hero Banner -->
<section class="relative bg-gradient-to-b from-agro-50/80 via-white to-white pt-16 pb-20 border-b border-agro-100 overflow-hidden">
    <!-- Ambient Glow Backgrounds -->
    <div class="absolute top-0 right-10 w-96 h-96 bg-emerald-400/10 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute bottom-0 left-10 w-80 h-80 bg-amber-300/15 rounded-full blur-3xl pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-center">
            
            <div class="lg:col-span-8 space-y-5">
                <span class="inline-flex items-center px-3.5 py-1.5 rounded-full text-xs font-bold bg-agro-100 text-agro-800 border border-agro-200">
                    <i class="fa-solid fa-layer-group mr-1.5 text-agro-700"></i> <?= htmlspecialchars($offBadge) ?>
                </span>
                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-agro-950 tracking-tight leading-tight">
                    <?= htmlspecialchars($offTitleMain) ?> <span class="text-gradient-emerald"><?= htmlspecialchars($offTitleHighlight) ?></span>
                </h1>
                <p class="text-base sm:text-lg text-slate-600 leading-relaxed max-w-3xl">
                    <?= htmlspecialchars($offDesc) ?>
                </p>
                <div class="pt-2 flex flex-wrap gap-4">
                    <a href="contact.php?category=Entrepreneur" class="inline-flex items-center px-6 py-3.5 rounded-xl font-bold text-white bg-agro-800 hover:bg-agro-900 shadow-lg shadow-agro-800/20 transition transform hover:-translate-y-0.5 text-sm">
                        <i class="fa-solid fa-seedling mr-2 text-emerald-300"></i> Access Hub Offerings
                    </a>
                    <a href="#matrix" class="inline-flex items-center px-6 py-3.5 rounded-xl font-bold text-slate-700 bg-white hover:bg-agro-50 border border-gray-200 shadow-sm transition text-sm">
                        <i class="fa-solid fa-table-list mr-2 text-agro-700"></i> View Capability Matrix
                    </a>
                </div>
            </div>

            <!-- Hero Highlights Stats Box (Dynamic from Admin Dashboard) -->
            <div class="lg:col-span-4">
                <div class="bg-gradient-to-br from-agro-950 to-agro-900 text-white rounded-3xl p-6 sm:p-7 shadow-xl border border-agro-800/50 space-y-5">
                    <div class="flex items-center justify-between pb-4 border-b border-agro-800">
                        <span class="text-xs uppercase font-bold tracking-widest text-emerald-400">Hub Infrastructure</span>
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">Fully Operational</span>
                    </div>

                    <div class="space-y-4">
                        <div class="flex items-start space-x-3">
                            <div class="w-8 h-8 rounded-lg bg-emerald-500/20 flex items-center justify-center text-emerald-300 shrink-0 mt-0.5">
                                <i class="fa-solid fa-map-location-dot text-sm"></i>
                            </div>
                            <div>
                                <h4 class="font-extrabold text-base text-white"><?= htmlspecialchars($offStat1Num) ?></h4>
                                <p class="text-xs text-slate-300"><?= htmlspecialchars($offStat1Desc) ?></p>
                            </div>
                        </div>

                        <div class="flex items-start space-x-3">
                            <div class="w-8 h-8 rounded-lg bg-emerald-500/20 flex items-center justify-center text-emerald-300 shrink-0 mt-0.5">
                                <i class="fa-solid fa-handshake-simple text-sm"></i>
                            </div>
                            <div>
                                <h4 class="font-extrabold text-base text-white"><?= htmlspecialchars($offStat2Num) ?></h4>
                                <p class="text-xs text-slate-300"><?= htmlspecialchars($offStat2Desc) ?></p>
                            </div>
                        </div>

                        <div class="flex items-start space-x-3">
                            <div class="w-8 h-8 rounded-lg bg-emerald-500/20 flex items-center justify-center text-emerald-300 shrink-0 mt-0.5">
                                <i class="fa-solid fa-sack-dollar text-sm"></i>
                            </div>
                            <div>
                                <h4 class="font-extrabold text-base text-white"><?= htmlspecialchars($offStat3Num) ?></h4>
                                <p class="text-xs text-slate-300"><?= htmlspecialchars($offStat3Desc) ?></p>
                            </div>
                        </div>

                        <div class="flex items-start space-x-3">
                            <div class="w-8 h-8 rounded-lg bg-emerald-500/20 flex items-center justify-center text-emerald-300 shrink-0 mt-0.5">
                                <i class="fa-solid fa-shield-halved text-sm"></i>
                            </div>
                            <div>
                                <h4 class="font-extrabold text-base text-white"><?= htmlspecialchars($offStat4Num) ?></h4>
                                <p class="text-xs text-slate-300"><?= htmlspecialchars($offStat4Desc) ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- Interactive Category Filter Tabs -->
<section class="py-12 bg-white border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="flex flex-wrap items-center justify-center gap-2 sm:gap-3" id="offering-filter-buttons">
            <button type="button" data-filter="all" class="filter-btn active px-4 py-2 rounded-xl text-xs sm:text-sm font-bold bg-agro-800 text-white shadow-sm transition">
                All Core Offerings
            </button>
            <button type="button" data-filter="field" class="filter-btn px-4 py-2 rounded-xl text-xs sm:text-sm font-bold bg-gray-100 text-slate-700 hover:bg-agro-50 hover:text-agro-800 transition">
                <i class="fa-solid fa-tractor mr-1 text-agro-600"></i> Farmland Living Labs
            </button>
            <button type="button" data-filter="capital" class="filter-btn px-4 py-2 rounded-xl text-xs sm:text-sm font-bold bg-gray-100 text-slate-700 hover:bg-agro-50 hover:text-agro-800 transition">
                <i class="fa-solid fa-coins mr-1 text-agro-600"></i> Capital & Syndicates
            </button>
            <button type="button" data-filter="commercial" class="filter-btn px-4 py-2 rounded-xl text-xs sm:text-sm font-bold bg-gray-100 text-slate-700 hover:bg-agro-50 hover:text-agro-800 transition">
                <i class="fa-solid fa-building-wheat mr-1 text-agro-600"></i> Corporate Off-Take
            </button>
            <button type="button" data-filter="tech" class="filter-btn px-4 py-2 rounded-xl text-xs sm:text-sm font-bold bg-gray-100 text-slate-700 hover:bg-agro-50 hover:text-agro-800 transition">
                <i class="fa-solid fa-microchip mr-1 text-agro-600"></i> Hardware & Bio Labs
            </button>
            <button type="button" data-filter="regulatory" class="filter-btn px-4 py-2 rounded-xl text-xs sm:text-sm font-bold bg-gray-100 text-slate-700 hover:bg-agro-50 hover:text-agro-800 transition">
                <i class="fa-solid fa-certificate mr-1 text-agro-600"></i> Policy & Standards
            </button>
        </div>

    </div>
</section>

<!-- Core Offerings Grid (Dynamic from Database & Admin Dashboard) -->
<section class="py-16 sm:py-20 bg-[#fbfdfc]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8" id="offerings-grid">
            <?php foreach ($activeOfferings as $off): 
                $cat = htmlspecialchars($off['category'] ?? 'field');
                $features = array_filter(array_map('trim', explode("\n", $off['features'] ?? '')));
                
                // Color palette according to category
                $badgeBg = match ($off['category'] ?? 'field') {
                    'capital' => 'bg-amber-100 text-amber-900',
                    'commercial' => 'bg-blue-100 text-blue-900',
                    'tech' => 'bg-purple-100 text-purple-900',
                    'regulatory' => 'bg-teal-100 text-teal-900',
                    default => 'bg-emerald-100 text-emerald-800'
                };
                $iconBox = match ($off['category'] ?? 'field') {
                    'capital' => 'bg-amber-50 text-amber-600 border-amber-100',
                    'commercial' => 'bg-blue-50 text-blue-600 border-blue-100',
                    'tech' => 'bg-purple-50 text-purple-600 border-purple-100',
                    'regulatory' => 'bg-teal-50 text-teal-600 border-teal-100',
                    default => 'bg-agro-50 text-agro-700 border-agro-100'
                };
                $btnColor = match ($off['category'] ?? 'field') {
                    'capital' => 'text-amber-800 bg-amber-50 hover:bg-amber-100 border-amber-200',
                    'commercial' => 'text-blue-800 bg-blue-50 hover:bg-blue-100 border-blue-200',
                    'tech' => 'text-purple-800 bg-purple-50 hover:bg-purple-100 border-purple-200',
                    'regulatory' => 'text-teal-800 bg-teal-50 hover:bg-teal-100 border-teal-200',
                    default => 'text-agro-800 bg-agro-50 hover:bg-agro-100 border-agro-200'
                };
            ?>
            <div class="offering-card bg-white rounded-3xl p-8 border border-gray-200/80 shadow-sm hover:shadow-xl hover:border-agro-300 transition duration-300 flex flex-col justify-between" data-category="<?= $cat ?>">
                <div>
                    <div class="flex items-center justify-between mb-6">
                        <div class="w-14 h-14 rounded-2xl border flex items-center justify-center text-2xl <?= $iconBox ?>">
                            <i class="fa-solid <?= htmlspecialchars($off['icon'] ?? 'fa-wheat-awn') ?>"></i>
                        </div>
                        <span class="px-3 py-1 font-bold text-xs rounded-full <?= $badgeBg ?>">
                            <?= htmlspecialchars($off['badge'] ?? 'Testbeds') ?>
                        </span>
                    </div>

                    <h3 class="text-2xl font-extrabold text-slate-900 mb-3"><?= htmlspecialchars($off['title']) ?></h3>
                    <p class="text-sm text-slate-600 leading-relaxed mb-6">
                        <?= htmlspecialchars($off['description']) ?>
                    </p>

                    <?php if (!empty($features)): ?>
                        <ul class="space-y-2.5 text-xs text-slate-600 border-t border-gray-100 pt-4 mb-6">
                            <?php foreach ($features as $f): ?>
                                <li class="flex items-center">
                                    <i class="fa-solid fa-circle-check text-emerald-600 mr-2 shrink-0"></i>
                                    <span><?= htmlspecialchars($f) ?></span>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </div>

                <a href="<?= htmlspecialchars($off['button_url'] ?? 'contact.php') ?>" class="inline-flex items-center justify-center w-full px-4 py-2.5 rounded-xl font-bold text-xs border transition <?= $btnColor ?>">
                    <?= htmlspecialchars($off['button_text'] ?? 'Learn More →') ?>
                </a>
            </div>
            <?php endforeach; ?>
        </div>

    </div>
</section>

<!-- Capability Access Matrix Section -->
<section id="matrix" class="py-20 bg-white border-t border-gray-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="text-center max-w-3xl mx-auto mb-16">
            <span class="text-xs font-bold uppercase tracking-wider text-agro-700"><?= htmlspecialchars(getSiteSetting('offerings_matrix_badge', 'Tailored For Every Stage')) ?></span>
            <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900 mt-2"><?= htmlspecialchars(getSiteSetting('offerings_matrix_title', 'Stakeholder Capability Matrix')) ?></h2>
            <p class="mt-3 text-slate-600 text-sm sm:text-base">
                <?= htmlspecialchars(getSiteSetting('offerings_matrix_subtitle', 'How founders, research spin-offs, corporate partners, and institutional investors engage with NextGen Farming Hub’s core infrastructure.')) ?>
            </p>
        </div>

        <div class="overflow-x-auto rounded-3xl border border-gray-200 shadow-sm">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="bg-slate-900 text-white">
                        <th class="p-5 font-bold"><?= htmlspecialchars(getSiteSetting('offerings_matrix_col0', 'Capabilities & Resources')) ?></th>
                        <th class="p-5 font-bold text-emerald-400"><?= htmlspecialchars(getSiteSetting('offerings_matrix_col1', 'Pre-Seed Startups')) ?></th>
                        <th class="p-5 font-bold text-amber-300"><?= htmlspecialchars(getSiteSetting('offerings_matrix_col2', 'Scaling Growth Ventures')) ?></th>
                        <th class="p-5 font-bold text-sky-300"><?= htmlspecialchars(getSiteSetting('offerings_matrix_col3', 'Academic / Science Spin-offs')) ?></th>
                        <th class="p-5 font-bold text-slate-300"><?= htmlspecialchars(getSiteSetting('offerings_matrix_col4', 'Corporate Agribusiness')) ?></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white">
                    <?php 
                    $matrixRows = getOfferingsMatrixRows();
                    foreach ($matrixRows as $row): 
                        $iconClass = !empty($row['icon']) ? $row['icon'] : 'fa-solid fa-cube text-agro-600';
                        if (!str_starts_with($iconClass, 'fa-') && !str_starts_with($iconClass, 'fa ')) {
                            $iconClass = 'fa-solid ' . $iconClass;
                        }
                    ?>
                    <tr class="hover:bg-agro-50/50 transition">
                        <td class="p-5 font-semibold text-slate-900 flex items-center">
                            <i class="<?= htmlspecialchars($iconClass) ?> mr-2"></i> <?= htmlspecialchars($row['title'] ?? '') ?>
                        </td>
                        <td class="p-5 <?= !empty($row['col1_check']) ? 'text-agro-800 font-bold' : 'text-slate-500 font-medium' ?>">
                            <?php if (!empty($row['col1_check'])): ?>
                                <i class="fa-solid fa-check text-emerald-600 mr-1.5"></i>
                            <?php endif; ?>
                            <?= htmlspecialchars($row['col1'] ?? '') ?>
                        </td>
                        <td class="p-5 <?= !empty($row['col2_check']) ? 'text-agro-800 font-bold' : 'text-slate-500 font-medium' ?>">
                            <?php if (!empty($row['col2_check'])): ?>
                                <i class="fa-solid fa-check text-emerald-600 mr-1.5"></i>
                            <?php endif; ?>
                            <?= htmlspecialchars($row['col2'] ?? '') ?>
                        </td>
                        <td class="p-5 <?= !empty($row['col3_check']) ? 'text-agro-800 font-bold' : 'text-slate-500 font-medium' ?>">
                            <?php if (!empty($row['col3_check'])): ?>
                                <i class="fa-solid fa-check text-emerald-600 mr-1.5"></i>
                            <?php endif; ?>
                            <?= htmlspecialchars($row['col3'] ?? '') ?>
                        </td>
                        <td class="p-5 <?= !empty($row['col4_check']) ? 'text-agro-800 font-bold' : 'text-slate-500 font-medium' ?>">
                            <?php if (!empty($row['col4_check'])): ?>
                                <i class="fa-solid fa-check text-emerald-600 mr-1.5"></i>
                            <?php endif; ?>
                            <?= htmlspecialchars($row['col4'] ?? '') ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

    </div>
</section>

<!-- Frequently Asked Questions -->
<section class="py-20 bg-agro-50/60 border-t border-agro-100">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="text-center mb-14">
            <span class="text-xs font-bold uppercase tracking-wider text-agro-700">Have Questions?</span>
            <h2 class="text-3xl font-extrabold text-slate-900 mt-2">Offerings & Infrastructure FAQ</h2>
        </div>

        <div class="space-y-4">
            <?php for ($f = 1; $f <= 3; $f++): 
                $faqQ = getSiteSetting("offering_faq{$f}_q", "");
                $faqA = getSiteSetting("offering_faq{$f}_a", "");
                if (empty($faqQ)) continue;
            ?>
            <div class="bg-white rounded-2xl p-6 border border-gray-200 shadow-sm">
                <h4 class="font-extrabold text-slate-900 text-base mb-2"><?= htmlspecialchars($faqQ) ?></h4>
                <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                    <?= nl2br(htmlspecialchars($faqA)) ?>
                </p>
            </div>
            <?php endfor; ?>
        </div>

    </div>
</section>

<script>
// Filter Tab Interactivity for Core Offerings
document.addEventListener('DOMContentLoaded', function() {
    const buttons = document.querySelectorAll('#offering-filter-buttons .filter-btn');
    const cards = document.querySelectorAll('#offerings-grid .offering-card');

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
