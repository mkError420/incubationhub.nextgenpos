<?php
$currentPage = 'about';
$pageTitle = 'About Us | NextGen Farming Incubation Hub';
$pageDescription = 'Learn about NextGen Farming Incubation Hub, our mission in Bangladesh, and our commitment to scaling agricultural technology across emerging frontier markets.';
require_once __DIR__ . '/includes/header.php';
?>

<!-- Header Hero Banner -->
<section class="relative bg-gradient-to-b from-agro-50/70 to-white pt-16 pb-20 border-b border-agro-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="max-w-3xl">
            <span class="inline-flex items-center px-3.5 py-1.5 rounded-full text-xs font-bold bg-agro-100 text-agro-800 border border-agro-200 mb-4">
                <i class="fa-solid fa-leaf mr-1.5 text-agro-700"></i> <?= htmlspecialchars(getSiteSetting('about_hero_badge', 'Our Mission & Purpose')) ?>
            </span>
            <h1 class="text-4xl sm:text-5xl font-extrabold text-agro-950 tracking-tight leading-tight">
                <?= htmlspecialchars(getSiteSetting('about_hero_title_main', 'Catalyzing Frontier AgriTech Across')) ?> <span class="text-gradient-emerald"><?= htmlspecialchars(getSiteSetting('about_hero_title_highlight', 'Bangladesh & Emerging Markets')) ?></span>
            </h1>
            <p class="mt-4 text-base sm:text-lg text-slate-600 leading-relaxed">
                <?= htmlspecialchars(getSiteSetting('about_hero_desc', 'NextGen Farming Incubation Hub was founded to transform fragmented agricultural value chains into resilient, high-productivity, and venture-fundable economic engines.')) ?>
            </p>
        </div>
    </div>
</section>

<!-- The Emerging Market Context Section -->
<section class="py-20 bg-white border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
            
            <div class="lg:col-span-6 space-y-5">
                <span class="text-xs font-bold uppercase tracking-wider text-agro-700"><?= htmlspecialchars(getSiteSetting('about_context_badge', 'The Problem & The Deltaic Promise')) ?></span>
                <h2 class="text-3xl font-extrabold text-slate-900 leading-snug">
                    <?= htmlspecialchars(getSiteSetting('about_context_title', 'Why Bangladesh is the World’s Most Vital Agricultural Sandbox')) ?>
                </h2>
                <p class="text-sm text-slate-600 leading-relaxed">
                    <?= htmlspecialchars(getSiteSetting('about_context_p1', 'With over 16.5 million smallholder farming families cultivating fertile yet climate-vulnerable alluvial soil, Bangladesh represents both an acute epicenter of climate risk and the ultimate proving ground for scalable AgriTech.')) ?>
                </p>
                <p class="text-sm text-slate-600 leading-relaxed">
                    <?= htmlspecialchars(getSiteSetting('about_context_p2', 'Technologies proven in the saline coastal zones of Khulna, the drought-prone barind tracts of Rajshahi, and the vegetable hubs of Bogura possess the immediate resilience needed across South Asia, Southeast Asia, and Sub-Saharan Africa.')) ?>
                </p>

                <!-- Key Bottlenecks We Solve -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-4">
                    <div class="p-4 rounded-2xl bg-agro-50/70 border border-agro-100">
                        <i class="<?= htmlspecialchars(getSiteSetting('about_bottle1_icon', 'fa-temperature-arrow-down')) ?> text-agro-700 text-xl mb-2"></i>
                        <h4 class="font-bold text-sm text-slate-900"><?= htmlspecialchars(getSiteSetting('about_bottle1_title', 'Post-Harvest Loss')) ?></h4>
                        <p class="text-xs text-slate-500 mt-1"><?= htmlspecialchars(getSiteSetting('about_bottle1_desc', 'Mitigating 30-35% perishable crop decay through modular solar micro-cold rooms.')) ?></p>
                    </div>
                    <div class="p-4 rounded-2xl bg-amber-50/70 border border-amber-100">
                        <i class="<?= htmlspecialchars(getSiteSetting('about_bottle2_icon', 'fa-mobile-screen')) ?> text-amber-700 text-xl mb-2"></i>
                        <h4 class="font-bold text-sm text-slate-900"><?= htmlspecialchars(getSiteSetting('about_bottle2_title', 'Digital Supply Chains')) ?></h4>
                        <p class="text-xs text-slate-500 mt-1"><?= htmlspecialchars(getSiteSetting('about_bottle2_desc', 'Disintermediating 5-tier middleman cartels via transparent mobile auction platforms.')) ?></p>
                    </div>
                    <div class="p-4 rounded-2xl bg-blue-50/70 border border-blue-100">
                        <i class="<?= htmlspecialchars(getSiteSetting('about_bottle3_icon', 'fa-satellite-dish')) ?> text-blue-700 text-xl mb-2"></i>
                        <h4 class="font-bold text-sm text-slate-900"><?= htmlspecialchars(getSiteSetting('about_bottle3_title', 'Precision Agronomy')) ?></h4>
                        <p class="text-xs text-slate-500 mt-1"><?= htmlspecialchars(getSiteSetting('about_bottle3_desc', 'Affordable IoT sensors & satellite NDVI imagery for targeted nitrogen and water delivery.')) ?></p>
                    </div>
                    <div class="p-4 rounded-2xl bg-emerald-50/70 border border-emerald-100">
                        <i class="<?= htmlspecialchars(getSiteSetting('about_bottle4_icon', 'fa-credit-card')) ?> text-emerald-700 text-xl mb-2"></i>
                        <h4 class="font-bold text-sm text-slate-900"><?= htmlspecialchars(getSiteSetting('about_bottle4_title', 'Agri-Fintech & Credit')) ?></h4>
                        <p class="text-xs text-slate-500 mt-1"><?= htmlspecialchars(getSiteSetting('about_bottle4_desc', 'Alternative algorithmic credit scoring unlocking working capital for unbanked tenant farmers.')) ?></p>
                    </div>
                </div>

            </div>

            <div class="lg:col-span-6">
                <!-- Visual Infographic Block -->
                <div class="bg-gradient-to-br from-agro-900 via-agro-950 to-slate-900 rounded-3xl p-8 sm:p-10 text-white shadow-2xl relative overflow-hidden">
                    <div class="absolute -right-10 -bottom-10 w-72 h-72 bg-emerald-500/10 rounded-full blur-2xl"></div>
                    <span class="text-xs font-bold text-emerald-400 uppercase tracking-widest block mb-2"><?= htmlspecialchars(getSiteSetting('about_flywheel_badge', 'Our Operating Architecture')) ?></span>
                    <h3 class="text-2xl font-black mb-6"><?= htmlspecialchars(getSiteSetting('about_flywheel_title', 'The NextGen Incubation Flywheel')) ?></h3>

                    <div class="space-y-6 relative z-10">
                        
                        <div class="flex items-start space-x-4">
                            <span class="w-8 h-8 rounded-full bg-emerald-500/20 text-emerald-300 border border-emerald-500/40 flex items-center justify-center font-bold text-sm shrink-0">1</span>
                            <div>
                                <h4 class="font-bold text-sm text-white"><?= htmlspecialchars(getSiteSetting('about_flywheel1_title', 'Grassroots Scouting & Problem Discovery')) ?></h4>
                                <p class="text-xs text-emerald-100/70 mt-0.5"><?= htmlspecialchars(getSiteSetting('about_flywheel1_desc', 'We work with agricultural universities and rural extension officers to identify pain-points directly from farm gates.')) ?></p>
                            </div>
                        </div>

                        <div class="flex items-start space-x-4">
                            <span class="w-8 h-8 rounded-full bg-emerald-500/20 text-emerald-300 border border-emerald-500/40 flex items-center justify-center font-bold text-sm shrink-0">2</span>
                            <div>
                                <h4 class="font-bold text-sm text-white"><?= htmlspecialchars(getSiteSetting('about_flywheel2_title', 'Living Farmland Validation')) ?></h4>
                                <p class="text-xs text-emerald-100/70 mt-0.5"><?= htmlspecialchars(getSiteSetting('about_flywheel2_desc', 'Startups test prototypes on our 150+ hectares of living testbeds before scaling commercially.')) ?></p>
                            </div>
                        </div>

                        <div class="flex items-start space-x-4">
                            <span class="w-8 h-8 rounded-full bg-emerald-500/20 text-emerald-300 border border-emerald-500/40 flex items-center justify-center font-bold text-sm shrink-0">3</span>
                            <div>
                                <h4 class="font-bold text-sm text-white"><?= htmlspecialchars(getSiteSetting('about_flywheel3_title', 'Blended Capital Syndication')) ?></h4>
                                <p class="text-xs text-emerald-100/70 mt-0.5"><?= htmlspecialchars(getSiteSetting('about_flywheel3_desc', 'We pair philanthropic grant de-risking with commercial equity from angel syndicates and venture funds.')) ?></p>
                            </div>
                        </div>

                        <div class="flex items-start space-x-4">
                            <span class="w-8 h-8 rounded-full bg-emerald-500/20 text-emerald-300 border border-emerald-500/40 flex items-center justify-center font-bold text-sm shrink-0">4</span>
                            <div>
                                <h4 class="font-bold text-sm text-white"><?= htmlspecialchars(getSiteSetting('about_flywheel4_title', 'Cross-Border Market Expansion')) ?></h4>
                                <p class="text-xs text-emerald-100/70 mt-0.5"><?= htmlspecialchars(getSiteSetting('about_flywheel4_desc', 'Validated ventures receive commercial bridge advisory to expand across India, Nepal, Kenya, and Vietnam.')) ?></p>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- Strategic Pillars Section -->
<section id="pillars" class="py-20 bg-[#f8fafc] border-b border-gray-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="text-center max-w-3xl mx-auto mb-16">
            <span class="text-xs font-bold uppercase tracking-wider text-agro-700"><?= htmlspecialchars(getSiteSetting('about_pillars_badge', 'Core Foundations')) ?></span>
            <h2 class="text-3xl font-extrabold text-slate-900 mt-1"><?= htmlspecialchars(getSiteSetting('about_pillars_title', 'Our Four Strategic Pillars')) ?></h2>
            <p class="mt-2 text-sm sm:text-base text-slate-500"><?= htmlspecialchars(getSiteSetting('about_pillars_subtitle', 'How we engineer lasting agricultural transformation through discipline and science.')) ?></p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            
            <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm card-gradient-hover">
                <div class="w-12 h-12 rounded-xl bg-emerald-100 text-emerald-800 flex items-center justify-center text-xl mb-4">
                    <i class="<?= htmlspecialchars(getSiteSetting('about_pillar1_icon', 'fa-dna')) ?>"></i>
                </div>
                <h3 class="font-bold text-base text-slate-900 mb-2"><?= htmlspecialchars(getSiteSetting('about_pillar1_title', 'Deep-Tech & Agronomy Fusion')) ?></h3>
                <p class="text-xs text-slate-500 leading-relaxed">
                    <?= htmlspecialchars(getSiteSetting('about_pillar1_desc', 'Fusing computational modeling, soil chemistry, and drone-based multispectral sensing with traditional agronomic practices.')) ?>
                </p>
            </div>

            <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm card-gradient-hover">
                <div class="w-12 h-12 rounded-xl bg-amber-100 text-amber-800 flex items-center justify-center text-xl mb-4">
                    <i class="<?= htmlspecialchars(getSiteSetting('about_pillar2_icon', 'fa-sack-dollar')) ?>"></i>
                </div>
                <h3 class="font-bold text-base text-slate-900 mb-2"><?= htmlspecialchars(getSiteSetting('about_pillar2_title', 'Catalytic Blended Finance')) ?></h3>
                <p class="text-xs text-slate-500 leading-relaxed">
                    <?= htmlspecialchars(getSiteSetting('about_pillar2_desc', 'Structuring non-dilutive innovation grants alongside equity venture capital to de-risk high-capex hardware and biotech ventures.')) ?>
                </p>
            </div>

            <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm card-gradient-hover">
                <div class="w-12 h-12 rounded-xl bg-blue-100 text-blue-800 flex items-center justify-center text-xl mb-4">
                    <i class="<?= htmlspecialchars(getSiteSetting('about_pillar3_icon', 'fa-wheat-awn-circle-exclamation')) ?>"></i>
                </div>
                <h3 class="font-bold text-base text-slate-900 mb-2"><?= htmlspecialchars(getSiteSetting('about_pillar3_title', 'Empirical Farmland Testbeds')) ?></h3>
                <p class="text-xs text-slate-500 leading-relaxed">
                    <?= htmlspecialchars(getSiteSetting('about_pillar3_desc', 'Real-world trials conducted side-by-side with commercial farmer cooperatives across diverse climatic micro-zones.')) ?>
                </p>
            </div>

            <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm card-gradient-hover">
                <div class="w-12 h-12 rounded-xl bg-purple-100 text-purple-800 flex items-center justify-center text-xl mb-4">
                    <i class="<?= htmlspecialchars(getSiteSetting('about_pillar4_icon', 'fa-globe')) ?>"></i>
                </div>
                <h3 class="font-bold text-base text-slate-900 mb-2"><?= htmlspecialchars(getSiteSetting('about_pillar4_title', 'South-South Knowledge Exchange')) ?></h3>
                <p class="text-xs text-slate-500 leading-relaxed">
                    <?= htmlspecialchars(getSiteSetting('about_pillar4_desc', 'Facilitating technology and policy linkages between South Asia, the Mekong Delta, and East African agricultural corridors.')) ?>
                </p>
            </div>

        </div>

    </div>
</section>

<!-- Leadership & Advisory Board -->
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="text-center max-w-2xl mx-auto mb-16">
            <span class="text-xs font-bold uppercase tracking-wider text-agro-700"><?= htmlspecialchars(getSiteSetting('about_team_badge', 'Governance & Operators')) ?></span>
            <h2 class="text-3xl font-extrabold text-slate-900 mt-1"><?= htmlspecialchars(getSiteSetting('about_team_title', 'Leadership & Advisory Board')) ?></h2>
            <p class="mt-2 text-sm text-slate-500"><?= htmlspecialchars(getSiteSetting('about_team_subtitle', 'Combining decades of venture capital, ag-science, and rural market scaling.')) ?></p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
            <?php 
            $advisors = getAboutAdvisors();
            $colors = [
                'from-agro-700 to-agro-900 text-white',
                'from-amber-600 to-amber-800 text-white',
                'from-blue-600 to-blue-800 text-white',
                'from-purple-600 to-purple-800 text-white',
                'from-emerald-600 to-emerald-800 text-white',
                'from-rose-600 to-rose-800 text-white',
            ];
            foreach ($advisors as $i => $adv): 
                $color = !empty($adv['color']) ? $adv['color'] : $colors[$i % count($colors)];
                $initials = !empty($adv['initials']) ? $adv['initials'] : strtoupper(substr($adv['name'] ?? 'AD', 0, 2));
                $hasImage = !empty($adv['image']) && file_exists(__DIR__ . '/' . $adv['image']);
            ?>
            <div class="text-center group">
                <?php if ($hasImage): ?>
                    <div class="w-32 h-32 mx-auto rounded-3xl overflow-hidden shadow-lg mb-4 group-hover:scale-105 transition border-2 border-agro-100 bg-slate-100">
                        <img src="<?= htmlspecialchars($adv['image']) ?>" alt="<?= htmlspecialchars($adv['name'] ?? '') ?>" class="w-full h-full object-cover">
                    </div>
                <?php else: ?>
                    <div class="w-32 h-32 mx-auto rounded-3xl bg-gradient-to-br <?= htmlspecialchars($color) ?> flex items-center justify-center text-3xl font-bold shadow-lg mb-4 group-hover:scale-105 transition">
                        <?= htmlspecialchars($initials) ?>
                    </div>
                <?php endif; ?>
                <h3 class="font-bold text-base text-slate-900"><?= htmlspecialchars($adv['name'] ?? '') ?></h3>
                <span class="text-xs text-agro-700 font-semibold block"><?= htmlspecialchars($adv['role'] ?? '') ?></span>
                <p class="text-xs text-slate-500 mt-2"><?= htmlspecialchars($adv['bio'] ?? '') ?></p>
            </div>
            <?php endforeach; ?>
        </div>

    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
