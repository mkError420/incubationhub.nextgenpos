<?php
if (session_status() === PHP_SESSION_NONE) {
    @session_start();
}
require_once __DIR__ . '/includes/db.php';

// If visitor has not submitted the form, directly open the landing page
if (!isVisitorUnlocked()) {
    require_once __DIR__ . '/landing.php';
    exit;
}

$currentPage = 'index';
$pageTitle = 'NextGen Farming Incubation Hub | Connecting Ecosystem Builders in Bangladesh & Emerging Markets';
$pageDescription = 'NextGen Farming Incubation Hub connects entrepreneurs, investors, support organisations, and mentors to accelerate agricultural innovation in Bangladesh and emerging markets.';
require_once __DIR__ . '/includes/header.php';
?>

<!-- ============================================================
     HERO SECTION
     Title: NextGen Farming Incubation Hub
     Subtitle: Connecting Entrepreneur Ecosystem builders in Bangladesh and Emerging Markets.
     ============================================================ -->
<section class="relative hero-gradient pt-16 pb-20 lg:pt-24 lg:pb-32 overflow-hidden border-b border-agro-100">
    <!-- Background Decor Elements -->
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full max-w-7xl h-full pointer-events-none overflow-hidden">
        <div class="absolute -top-24 -left-20 w-96 h-96 bg-emerald-300/20 rounded-full blur-3xl"></div>
        <div class="absolute top-1/3 -right-20 w-96 h-96 bg-amber-200/25 rounded-full blur-3xl"></div>
        <div class="absolute bottom-10 left-1/4 w-72 h-72 bg-agro-400/15 rounded-full blur-2xl"></div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-8 items-center">
            
            <!-- Left Hero Content -->
            <div class="lg:col-span-7 text-center lg:text-left space-y-6">
                
                <!-- Tagline Badge -->
                <div class="inline-flex items-center space-x-2 px-4 py-2 rounded-full bg-white/80 border border-agro-200/80 shadow-sm backdrop-blur-md">
                    <span class="flex h-2.5 w-2.5 relative">
                        <span class="pulse-circle absolute inline-flex h-full w-full rounded-full bg-emerald-500 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-agro-700"></span>
                    </span>
                    <span class="text-xs sm:text-sm font-bold text-agro-900 tracking-wide">
                        <?= htmlspecialchars(getSiteSetting('hero_tagline', 'AgriTech Innovation & Venture Studio')) ?>
                    </span>
                    <span class="text-xs bg-agro-100 text-agro-800 px-2 py-0.5 rounded-full font-semibold">Cohort 04 Open</span>
                </div>

                <!-- Main H1 Headline -->
                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-agro-950 tracking-tight leading-[1.12]">
                    <?= htmlspecialchars(getSiteSetting('hero_title_main', 'NextGen Farming')) ?> <br class="hidden sm:inline">
                    <span class="text-gradient-emerald"><?= htmlspecialchars(getSiteSetting('hero_title_highlight', 'Incubation Hub')) ?></span>
                </h1>

                <!-- Subtitle (Exact User Specification) -->
                <p class="text-lg sm:text-xl text-slate-600 max-w-2xl font-normal leading-relaxed mx-auto lg:mx-0">
                    <?= htmlspecialchars(getSiteSetting('hero_subtitle', 'Connecting Entrepreneur Ecosystem builders in Bangladesh and Emerging Markets.')) ?>
                </p>

                <!-- Value Proposition Highlights -->
                <p class="text-sm sm:text-base text-slate-500 max-w-xl leading-relaxed mx-auto lg:mx-0">
                    <?= htmlspecialchars(getSiteSetting('hero_description', 'We nurture transformative agricultural startups, unlock catalytic capital, and connect cross-border knowledge to build resilient, high-yield, and climate-adaptive food systems.')) ?>
                </p>

                <!-- CTA Action Buttons -->
                <div class="pt-4 flex flex-col sm:flex-row items-center justify-center lg:justify-start gap-4">
                    <a href="contact.php" class="w-full sm:w-auto px-8 py-4 text-base font-bold text-white bg-gradient-to-r from-agro-800 to-agro-700 hover:from-agro-900 hover:to-agro-800 rounded-xl shadow-xl shadow-agro-800/25 hover:shadow-2xl transition transform hover:-translate-y-0.5 flex items-center justify-center">
                        <i class="fa-solid fa-paper-plane mr-2.5 text-emerald-300"></i> Join the Ecosystem
                    </a>
                    <a href="programs.php" class="w-full sm:w-auto px-7 py-4 text-base font-bold text-agro-900 bg-white hover:bg-agro-50/80 border border-agro-200 rounded-xl shadow-sm transition flex items-center justify-center">
                        <i class="fa-solid fa-compass mr-2 text-agro-700"></i> Explore Programs
                    </a>
                </div>

                <!-- Trust Micro-Bar -->
                <div class="pt-6 border-t border-agro-100/80 flex flex-wrap items-center justify-center lg:justify-start gap-6 text-xs text-slate-500 font-medium">
                    <div class="flex items-center space-x-2">
                        <i class="fa-solid fa-check-circle text-agro-600 text-sm"></i>
                        <span>Pre-Seed to Series A Mentorship</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <i class="fa-solid fa-check-circle text-agro-600 text-sm"></i>
                        <span>Living Farmland Testbeds</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <i class="fa-solid fa-check-circle text-agro-600 text-sm"></i>
                        <span>Syndicated Angel Deal Flow</span>
                    </div>
                </div>

            </div>

            <!-- Right Hero Visual Card -->
            <div class="lg:col-span-5 relative">
                <div class="relative mx-auto max-w-md lg:max-w-none">
                    
                    <!-- Main Card -->
                    <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-2xl border border-agro-100 relative z-20">
                        <div class="flex items-center justify-between pb-6 border-b border-gray-100">
                            <div class="flex items-center space-x-3">
                                <div class="w-12 h-12 rounded-2xl bg-agro-50 border border-agro-100 flex items-center justify-center text-agro-800">
                                    <i class="fa-solid fa-dna text-2xl text-agro-700"></i>
                                </div>
                                <div>
                                    <h3 class="font-extrabold text-slate-900 text-base">Ag-Bio & Precision Hub</h3>
                                    <span class="text-xs text-agro-600 font-semibold flex items-center">
                                        <span class="w-2 h-2 rounded-full bg-emerald-500 mr-1.5 animate-pulse"></span> Active Acceleration Node
                                    </span>
                                </div>
                            </div>
                            <span class="px-2.5 py-1 text-xs font-bold rounded-lg bg-amber-50 text-amber-800 border border-amber-200">
                                Dhaka • BD
                            </span>
                        </div>

                        <!-- Hub Metrics Snapshot -->
                        <div class="grid grid-cols-2 gap-4 my-6">
                            <div class="p-4 rounded-2xl bg-agro-50/70 border border-agro-100">
                                <span class="text-xs font-semibold text-slate-500 block">Total Catalyzed</span>
                                <span class="text-2xl font-black text-agro-900">$28.5M+</span>
                                <span class="text-[11px] text-agro-600 font-medium block mt-0.5">Capital & Co-Grants</span>
                            </div>
                            <div class="p-4 rounded-2xl bg-amber-50/70 border border-amber-100">
                                <span class="text-xs font-semibold text-slate-500 block">Startups Supported</span>
                                <span class="text-2xl font-black text-amber-900">128+</span>
                                <span class="text-[11px] text-amber-700 font-medium block mt-0.5">Across 14 Sectors</span>
                            </div>
                        </div>

                        <!-- Focus Domains -->
                        <div class="space-y-3">
                            <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider">Active Thematic Verticals</h4>
                            <div class="flex flex-wrap gap-2 text-xs">
                                <span class="px-3 py-1.5 bg-gray-100 text-slate-700 rounded-lg font-medium hover:bg-agro-50 hover:text-agro-800 transition">
                                    <i class="fa-solid fa-droplet text-blue-500 mr-1"></i> Precision Irrigation
                                </span>
                                <span class="px-3 py-1.5 bg-gray-100 text-slate-700 rounded-lg font-medium hover:bg-agro-50 hover:text-agro-800 transition">
                                    <i class="fa-solid fa-snowflake text-cyan-500 mr-1"></i> Solar Cold Chain
                                </span>
                                <span class="px-3 py-1.5 bg-gray-100 text-slate-700 rounded-lg font-medium hover:bg-agro-50 hover:text-agro-800 transition">
                                    <i class="fa-solid fa-wheat-awn text-amber-600 mr-1"></i> Bio-Fertilizers & Seeds
                                </span>
                                <span class="px-3 py-1.5 bg-gray-100 text-slate-700 rounded-lg font-medium hover:bg-agro-50 hover:text-agro-800 transition">
                                    <i class="fa-solid fa-mobile-screen-button text-emerald-600 mr-1"></i> Farm-to-Fork ERP
                                </span>
                            </div>
                        </div>

                        <!-- Cohort Banner Link -->
                        <div class="mt-6 pt-5 border-t border-gray-100">
                            <a href="contact.php?category=Entrepreneur" class="flex items-center justify-between p-3 rounded-xl bg-gradient-to-r from-agro-900 to-agro-800 text-white group hover:shadow-lg transition">
                                <div class="flex items-center space-x-3">
                                    <div class="w-8 h-8 rounded-lg bg-white/10 flex items-center justify-center text-emerald-300">
                                        <i class="fa-solid fa-seedling"></i>
                                    </div>
                                    <div>
                                        <h5 class="text-xs font-bold">Apply as an Agri-Founder</h5>
                                        <p class="text-[10px] text-emerald-200">Pitch deck review within 48h</p>
                                    </div>
                                </div>
                                <i class="fa-solid fa-arrow-right text-xs group-hover:translate-x-1 transition text-emerald-300"></i>
                            </a>
                        </div>

                    </div>

                    <!-- Floating Decorative Badge -->
                    <div class="hidden sm:flex absolute -bottom-6 -left-6 z-30 bg-white p-4 rounded-2xl shadow-xl border border-agro-100 items-center space-x-3">
                        <div class="w-10 h-10 rounded-xl bg-amber-500 text-white flex items-center justify-center font-bold">
                            <i class="fa-solid fa-award text-lg"></i>
                        </div>
                        <div>
                            <span class="block text-xs font-extrabold text-slate-900">#1 AgriTech Hub</span>
                            <span class="block text-[11px] text-slate-500">Bangladesh & South Asia</span>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
</section>


<!-- ============================================================
     THE TRACK RECORD SECTION
     3 columns displaying:
     1. Community Members
     2. Business Ventures
     3. Program Organizers
     ============================================================ -->
<section id="track-record" class="py-20 bg-white border-b border-gray-100 relative">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Section Header -->
        <div class="text-center max-w-3xl mx-auto mb-16">
            <span class="inline-flex items-center px-3.5 py-1.5 rounded-full text-xs font-bold bg-agro-50 text-agro-800 border border-agro-200 mb-3">
                <i class="fa-solid fa-chart-simple mr-1.5 text-agro-600"></i> Proven Impact & Momentum
            </span>
            <h2 class="text-3xl sm:text-4xl font-extrabold text-agro-950 tracking-tight">
                The Track Record
            </h2>
            <p class="mt-3 text-slate-600 text-base sm:text-lg leading-relaxed">
                Empowering frontline changemakers across Bangladesh and frontier agricultural markets with tangible, audited milestones.
            </p>
        </div>

        <!-- 3 Columns Track Record Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 lg:gap-10">
            
            <!-- Column 1: Community Members -->
            <div class="relative bg-gradient-to-b from-white to-agro-50/40 p-8 rounded-3xl border border-gray-200 card-gradient-hover flex flex-col justify-between">
                <div>
                    <!-- Icon & Accent -->
                    <div class="w-14 h-14 rounded-2xl bg-emerald-100 text-emerald-800 flex items-center justify-center text-2xl mb-6 shadow-sm">
                        <i class="fa-solid fa-users"></i>
                    </div>

                    <!-- Number Counter -->
                    <div class="flex items-baseline space-x-1 mb-2">
                        <span class="text-4xl sm:text-5xl font-black text-agro-950 counter-value" data-target="<?= (int)getSiteSetting('metric_members', 5200) ?>" data-suffix="<?= htmlspecialchars(getSiteSetting('metric_members_suffix', '+')) ?>"><?= number_format((int)getSiteSetting('metric_members', 5200)) ?><?= htmlspecialchars(getSiteSetting('metric_members_suffix', '+')) ?></span>
                    </div>

                    <h3 class="text-xl font-bold text-slate-900 mb-3">
                        Community Members
                    </h3>

                    <!-- Descriptive Text -->
                    <p class="text-slate-600 text-sm leading-relaxed">
                        <?= htmlspecialchars(getSiteSetting('metric_members_desc', 'A vibrant network of over 5,200 passionate smallholder innovators, agronomy scientists, youth climate advocates, university researchers, and rural co-op leaders spanning all 64 districts of Bangladesh and key regional farming corridors.')) ?>
                    </p>
                </div>

                <!-- Footer Feature Points -->
                <div class="mt-6 pt-6 border-t border-gray-100 text-xs text-slate-500 space-y-2">
                    <div class="flex items-center space-x-2">
                        <i class="fa-solid fa-circle-check text-agro-600"></i>
                        <span>64 Districts Grassroots Coverage</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <i class="fa-solid fa-circle-check text-agro-600"></i>
                        <span>38% Women Agri-Leaders</span>
                    </div>
                </div>
            </div>

            <!-- Column 2: Business Ventures -->
            <div class="relative bg-gradient-to-b from-white to-amber-50/40 p-8 rounded-3xl border border-gray-200 card-gradient-hover flex flex-col justify-between">
                <div>
                    <!-- Icon & Accent -->
                    <div class="w-14 h-14 rounded-2xl bg-amber-100 text-amber-800 flex items-center justify-center text-2xl mb-6 shadow-sm">
                        <i class="fa-solid fa-seedling"></i>
                    </div>

                    <!-- Number Counter -->
                    <div class="flex items-baseline space-x-1 mb-2">
                        <span class="text-4xl sm:text-5xl font-black text-amber-950 counter-value" data-target="<?= (int)getSiteSetting('metric_ventures', 128) ?>" data-suffix="<?= htmlspecialchars(getSiteSetting('metric_ventures_suffix', '+')) ?>"><?= (int)getSiteSetting('metric_ventures', 128) ?><?= htmlspecialchars(getSiteSetting('metric_ventures_suffix', '+')) ?></span>
                    </div>

                    <h3 class="text-xl font-bold text-slate-900 mb-3">
                        Business Ventures
                    </h3>

                    <!-- Descriptive Text -->
                    <p class="text-slate-600 text-sm leading-relaxed">
                        <?= htmlspecialchars(getSiteSetting('metric_ventures_desc', 'Over 128 high-growth agricultural ventures accelerated across post-harvest cold storage, biotech input formulations, sensor-driven irrigation, and digital farm-to-table supply chains, mobilizing more than $28.5M in cumulative follow-on investment.')) ?>
                    </p>
                </div>

                <!-- Footer Feature Points -->
                <div class="mt-6 pt-6 border-t border-gray-100 text-xs text-slate-500 space-y-2">
                    <div class="flex items-center space-x-2">
                        <i class="fa-solid fa-circle-check text-amber-600"></i>
                        <span><?= htmlspecialchars(getSiteSetting('metric_capital', '$28.5M+')) ?> Follow-on Venture Capital</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <i class="fa-solid fa-circle-check text-amber-600"></i>
                        <span>91% Commercial Survival Rate</span>
                    </div>
                </div>
            </div>

            <!-- Column 3: Program Organizers -->
            <div class="relative bg-gradient-to-b from-white to-blue-50/40 p-8 rounded-3xl border border-gray-200 card-gradient-hover flex flex-col justify-between">
                <div>
                    <!-- Icon & Accent -->
                    <div class="w-14 h-14 rounded-2xl bg-blue-100 text-blue-800 flex items-center justify-center text-2xl mb-6 shadow-sm">
                        <i class="fa-solid fa-sitemap"></i>
                    </div>

                    <!-- Number Counter -->
                    <div class="flex items-baseline space-x-1 mb-2">
                        <span class="text-4xl sm:text-5xl font-black text-slate-900 counter-value" data-target="<?= (int)getSiteSetting('metric_organizers', 48) ?>" data-suffix="<?= htmlspecialchars(getSiteSetting('metric_organizers_suffix', '+')) ?>"><?= (int)getSiteSetting('metric_organizers', 48) ?><?= htmlspecialchars(getSiteSetting('metric_organizers_suffix', '+')) ?></span>
                    </div>

                    <h3 class="text-xl font-bold text-slate-900 mb-3">
                        Program Organizers
                    </h3>

                    <!-- Descriptive Text -->
                    <p class="text-slate-600 text-sm leading-relaxed">
                        <?= htmlspecialchars(getSiteSetting('metric_organizers_desc', 'A robust coalition of 48+ co-design partners, including premier agricultural universities, multilateral development agencies, impact venture syndicates, and corporate agribusiness giants collaborating to execute world-class incubation cohorts.')) ?>
                    </p>
                </div>

                <!-- Footer Feature Points -->
                <div class="mt-6 pt-6 border-t border-gray-100 text-xs text-slate-500 space-y-2">
                    <div class="flex items-center space-x-2">
                        <i class="fa-solid fa-circle-check text-blue-600"></i>
                        <span>12 Global Multilateral Alliances</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <i class="fa-solid fa-circle-check text-blue-600"></i>
                        <span>Tier-1 Corporate Agribusiness Integration</span>
                    </div>
                </div>
            </div>

        </div>

    </div>
</section>


<!-- ============================================================
     CORE OFFERINGS: ENTREPRENEURS & INVESTORS
     2-column layout detailing offerings for Entrepreneurs and Investors
     ============================================================ -->
<section id="offerings" class="py-24 bg-[#f8fafc] border-b border-gray-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="text-center max-w-3xl mx-auto mb-16">
            <span class="inline-flex items-center px-3.5 py-1.5 rounded-full text-xs font-bold bg-agro-100 text-agro-800 border border-agro-200 mb-3">
                <i class="fa-solid fa-arrows-split-up-and-left mr-1.5 text-agro-700"></i> Tailored Ecosystem Tracks
            </span>
            <h2 class="text-3xl sm:text-4xl font-extrabold text-agro-950 tracking-tight">
                Core Offerings: Entrepreneurs & Investors
            </h2>
            <p class="mt-3 text-slate-600 text-base sm:text-lg leading-relaxed">
                Direct pipelines engineered to match high-potential agricultural founders with high-conviction risk capital and specialized acceleration.
            </p>
        </div>

        <!-- 2-Column Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-12">
            
            <!-- Column 1: For Entrepreneurs -->
            <div class="bg-white rounded-3xl p-8 sm:p-10 shadow-sm border border-gray-200 card-gradient-hover flex flex-col justify-between">
                <div>
                    <!-- Badge & Title -->
                    <div class="flex items-center justify-between mb-6">
                        <span class="px-3.5 py-1.5 rounded-full bg-emerald-50 text-emerald-800 text-xs font-extrabold border border-emerald-200 flex items-center">
                            <i class="fa-solid fa-seedling mr-1.5"></i> For Agri-Entrepreneurs
                        </span>
                        <span class="text-xs font-semibold text-slate-400">Pre-Seed to Scale</span>
                    </div>

                    <h3 class="text-2xl font-bold text-slate-900 mb-4">
                        Build, Validate & Scale Your Venture
                    </h3>

                    <p class="text-slate-600 text-sm leading-relaxed mb-6">
                        Comprehensive venture acceleration specifically architected to de-risk agricultural technology, validate product-market fit with smallholder farmers, and scale commercial distribution.
                    </p>

                    <!-- Feature List -->
                    <div class="space-y-4">
                        
                        <div class="flex items-start space-x-3.5">
                            <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-700 flex items-center justify-center shrink-0 mt-0.5">
                                <i class="fa-solid fa-hand-holding-dollar text-sm"></i>
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-slate-900">Fundraising & Seed Capital Access</h4>
                                <p class="text-xs text-slate-500 mt-0.5">Direct pitch opportunities to angel syndicates, seed funds, and catalytic non-dilutive grant facilities.</p>
                            </div>
                        </div>

                        <div class="flex items-start space-x-3.5">
                            <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-700 flex items-center justify-center shrink-0 mt-0.5">
                                <i class="fa-solid fa-rocket text-sm"></i>
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-slate-900">Cohort Accelerator Programs</h4>
                                <p class="text-xs text-slate-500 mt-0.5">Intensive 16-week structured sprints covering unit economics, rural supply chains, and pilot deployments.</p>
                            </div>
                        </div>

                        <div class="flex items-start space-x-3.5">
                            <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-700 flex items-center justify-center shrink-0 mt-0.5">
                                <i class="fa-solid fa-chalkboard-user text-sm"></i>
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-slate-900">Dedicated Ag-Bio & Commercial Mentorship</h4>
                                <p class="text-xs text-slate-500 mt-0.5">1-on-1 weekly advisory from seasoned operators, agronomists, soil scientists, and venture builders.</p>
                            </div>
                        </div>

                        <div class="flex items-start space-x-3.5">
                            <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-700 flex items-center justify-center shrink-0 mt-0.5">
                                <i class="fa-solid fa-flask-vial text-sm"></i>
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-slate-900">Living Farmland Testbeds & Labs</h4>
                                <p class="text-xs text-slate-500 mt-0.5">Access to over 150 hectares of cooperative pilot fields in Bogura and Dinajpur for hardware and sensor trials.</p>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="mt-8 pt-6 border-t border-gray-100 flex items-center justify-between">
                    <a href="contact.php?category=Entrepreneur" class="inline-flex items-center text-sm font-bold text-agro-800 hover:text-agro-900 group">
                        Apply as an Entrepreneur <i class="fa-solid fa-arrow-right ml-2 text-xs group-hover:translate-x-1 transition"></i>
                    </a>
                    <span class="text-xs text-emerald-600 font-semibold bg-emerald-50 px-2.5 py-1 rounded-md">Rolling Admissions</span>
                </div>
            </div>

            <!-- Column 2: For Investors -->
            <div class="bg-white rounded-3xl p-8 sm:p-10 shadow-sm border border-gray-200 card-gradient-hover flex flex-col justify-between">
                <div>
                    <!-- Badge & Title -->
                    <div class="flex items-center justify-between mb-6">
                        <span class="px-3.5 py-1.5 rounded-full bg-amber-50 text-amber-800 text-xs font-extrabold border border-amber-200 flex items-center">
                            <i class="fa-solid fa-chart-line mr-1.5"></i> For Angel & Institutional Investors
                        </span>
                        <span class="text-xs font-semibold text-slate-400">Frontier Returns</span>
                    </div>

                    <h3 class="text-2xl font-bold text-slate-900 mb-4">
                        Frontier Deal Flow with Verifiable Impact
                    </h3>

                    <p class="text-slate-600 text-sm leading-relaxed mb-6">
                        Unlocking proprietary, rigorously pre-vetted agricultural venture deal flow across Bangladesh and emerging markets with institutional-grade due diligence.
                    </p>

                    <!-- Feature List -->
                    <div class="space-y-4">
                        
                        <div class="flex items-start space-x-3.5">
                            <div class="w-8 h-8 rounded-lg bg-amber-50 text-amber-700 flex items-center justify-center shrink-0 mt-0.5">
                                <i class="fa-solid fa-filter-circle-dollar text-sm"></i>
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-slate-900">Curated & Audited Deal Flow</h4>
                                <p class="text-xs text-slate-500 mt-0.5">Exclusive early access to top-quartile startups that have survived field pilots and customer diligence.</p>
                            </div>
                        </div>

                        <div class="flex items-start space-x-3.5">
                            <div class="w-8 h-8 rounded-lg bg-amber-50 text-amber-700 flex items-center justify-center shrink-0 mt-0.5">
                                <i class="fa-solid fa-diagram-project text-sm"></i>
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-slate-900">Co-Investment Syndicates</h4>
                                <p class="text-xs text-slate-500 mt-0.5">Participate in SPVs and syndicates with matched catalytic grant co-investors to reduce downside risk.</p>
                            </div>
                        </div>

                        <div class="flex items-start space-x-3.5">
                            <div class="w-8 h-8 rounded-lg bg-amber-50 text-amber-700 flex items-center justify-center shrink-0 mt-0.5">
                                <i class="fa-solid fa-shield-halved text-sm"></i>
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-slate-900">Emerging Market Risk Due Diligence</h4>
                                <p class="text-xs text-slate-500 mt-0.5">Deep on-the-ground technical validation, unit economics assessment, and farmer adoption metrics.</p>
                            </div>
                        </div>

                        <div class="flex items-start space-x-3.5">
                            <div class="w-8 h-8 rounded-lg bg-amber-50 text-amber-700 flex items-center justify-center shrink-0 mt-0.5">
                                <i class="fa-solid fa-leaf text-sm"></i>
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-slate-900">ESG & Carbon Impact Reporting</h4>
                                <p class="text-xs text-slate-500 mt-0.5">Audited metrics tracking water reduction, chemical abatement, and smallholder income increase.</p>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="mt-8 pt-6 border-t border-gray-100 flex items-center justify-between">
                    <a href="contact.php?category=Investor" class="inline-flex items-center text-sm font-bold text-amber-700 hover:text-amber-800 group">
                        Connect as an Investor <i class="fa-solid fa-arrow-right ml-2 text-xs group-hover:translate-x-1 transition"></i>
                    </a>
                    <span class="text-xs text-amber-700 font-semibold bg-amber-50 px-2.5 py-1 rounded-md">Syndicate Access</span>
                </div>
            </div>

        </div>

    </div>
</section>


<!-- ============================================================
     CORE OFFERINGS: ORGANISATION & MENTORS
     2-column layout detailing offerings for Support Organisations and Mentors
     ============================================================ -->
<section class="py-24 bg-white border-b border-gray-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="text-center max-w-3xl mx-auto mb-16">
            <span class="inline-flex items-center px-3.5 py-1.5 rounded-full text-xs font-bold bg-blue-50 text-blue-800 border border-blue-200 mb-3">
                <i class="fa-solid fa-handshake-angle mr-1.5 text-blue-600"></i> Institutional & Advisory Partnerships
            </span>
            <h2 class="text-3xl sm:text-4xl font-extrabold text-agro-950 tracking-tight">
                Core Offerings: Organisation & Mentors
            </h2>
            <p class="mt-3 text-slate-600 text-base sm:text-lg leading-relaxed">
                Co-creating scalable incubation frameworks for developmental institutions while mobilizing world-class agronomy and venture mentors.
            </p>
        </div>

        <!-- 2-Column Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-12">
            
            <!-- Column 1: For Support Organisations -->
            <div class="bg-gradient-to-br from-white to-blue-50/30 rounded-3xl p-8 sm:p-10 shadow-sm border border-gray-200 card-gradient-hover flex flex-col justify-between">
                <div>
                    <!-- Badge & Title -->
                    <div class="flex items-center justify-between mb-6">
                        <span class="px-3.5 py-1.5 rounded-full bg-blue-50 text-blue-800 text-xs font-extrabold border border-blue-200 flex items-center">
                            <i class="fa-solid fa-building-columns mr-1.5"></i> For Support Organisations
                        </span>
                        <span class="text-xs font-semibold text-slate-400">NGOs, Gov, Universities</span>
                    </div>

                    <h3 class="text-2xl font-bold text-slate-900 mb-4">
                        Catalyze Ecosystems at National Scale
                    </h3>

                    <p class="text-slate-600 text-sm leading-relaxed mb-6">
                        We partner with ministries, university research labs, bilateral agencies, and corporate foundations to implement proven incubation frameworks and achieve long-term food security.
                    </p>

                    <!-- Feature List -->
                    <div class="space-y-4">
                        
                        <div class="flex items-start space-x-3.5">
                            <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-700 flex items-center justify-center shrink-0 mt-0.5">
                                <i class="fa-solid fa-cubes-stacked text-sm"></i>
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-slate-900">Ecosystem Development Frameworks</h4>
                                <p class="text-xs text-slate-500 mt-0.5">Turnkey incubator blueprints, startup policy advocacy, and university tech transfer infrastructure.</p>
                            </div>
                        </div>

                        <div class="flex items-start space-x-3.5">
                            <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-700 flex items-center justify-center shrink-0 mt-0.5">
                                <i class="fa-solid fa-people-roof text-sm"></i>
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-slate-900">Capacity Building & Rural Bootcamps</h4>
                                <p class="text-xs text-slate-500 mt-0.5">Grassroots training modules for youth and women farmer cooperatives on climate-smart techniques.</p>
                            </div>
                        </div>

                        <div class="flex items-start space-x-3.5">
                            <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-700 flex items-center justify-center shrink-0 mt-0.5">
                                <i class="fa-solid fa-file-contract text-sm"></i>
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-slate-900">CSR & Grant Project Delivery</h4>
                                <p class="text-xs text-slate-500 mt-0.5">High-governance execution of funded agricultural development mandates with verifiable audit trails.</p>
                            </div>
                        </div>

                        <div class="flex items-start space-x-3.5">
                            <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-700 flex items-center justify-center shrink-0 mt-0.5">
                                <i class="fa-solid fa-link text-sm"></i>
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-slate-900">Value-Chain & Commercial Linkages</h4>
                                <p class="text-xs text-slate-500 mt-0.5">Direct integration of innovative farmer clusters into national retail, export, and food manufacturing chains.</p>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="mt-8 pt-6 border-t border-gray-100 flex items-center justify-between">
                    <a href="contact.php?category=Support%20Organisation" class="inline-flex items-center text-sm font-bold text-blue-700 hover:text-blue-800 group">
                        Partner as an Organisation <i class="fa-solid fa-arrow-right ml-2 text-xs group-hover:translate-x-1 transition"></i>
                    </a>
                    <span class="text-xs text-blue-700 font-semibold bg-blue-50 px-2.5 py-1 rounded-md">Strategic Alliances</span>
                </div>
            </div>

            <!-- Column 2: For Mentors -->
            <div class="bg-gradient-to-br from-white to-purple-50/30 rounded-3xl p-8 sm:p-10 shadow-sm border border-gray-200 card-gradient-hover flex flex-col justify-between">
                <div>
                    <!-- Badge & Title -->
                    <div class="flex items-center justify-between mb-6">
                        <span class="px-3.5 py-1.5 rounded-full bg-purple-50 text-purple-800 text-xs font-extrabold border border-purple-200 flex items-center">
                            <i class="fa-solid fa-user-graduate mr-1.5"></i> For Domain Mentors & Experts
                        </span>
                        <span class="text-xs font-semibold text-slate-400">Knowledge Exchange</span>
                    </div>

                    <h3 class="text-2xl font-bold text-slate-900 mb-4">
                        Inspire & Guide Tomorrow's Agri-Founders
                    </h3>

                    <p class="text-slate-600 text-sm leading-relaxed mb-6">
                        Share your specialized mastery in agronomy, cold chain logistics, bio-engineering, or venture capital to shape high-impact ventures solving food security.
                    </p>

                    <!-- Feature List -->
                    <div class="space-y-4">
                        
                        <div class="flex items-start space-x-3.5">
                            <div class="w-8 h-8 rounded-lg bg-purple-50 text-purple-700 flex items-center justify-center shrink-0 mt-0.5">
                                <i class="fa-solid fa-globe text-sm"></i>
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-slate-900">Global Advisory Network</h4>
                                <p class="text-xs text-slate-500 mt-0.5">Collaborate with international peer mentors from Wageningen, UC Davis, and leading agritech accelerators.</p>
                            </div>
                        </div>

                        <div class="flex items-start space-x-3.5">
                            <div class="w-8 h-8 rounded-lg bg-purple-50 text-purple-700 flex items-center justify-center shrink-0 mt-0.5">
                                <i class="fa-solid fa-laptop-code text-sm"></i>
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-slate-900">Domain Expert Residencies</h4>
                                <p class="text-xs text-slate-500 mt-0.5">Lead dedicated 4-week thematic masterclasses in soil science, sensor IoT, and ag-fintech distribution.</p>
                            </div>
                        </div>

                        <div class="flex items-start space-x-3.5">
                            <div class="w-8 h-8 rounded-lg bg-purple-50 text-purple-700 flex items-center justify-center shrink-0 mt-0.5">
                                <i class="fa-solid fa-chess-knight text-sm"></i>
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-slate-900">Direct Board & Advisory Placement</h4>
                                <p class="text-xs text-slate-500 mt-0.5">Opportunities for advisory shares or formal board representation in high-growth cohort alumni ventures.</p>
                            </div>
                        </div>

                        <div class="flex items-start space-x-3.5">
                            <div class="w-8 h-8 rounded-lg bg-purple-50 text-purple-700 flex items-center justify-center shrink-0 mt-0.5">
                                <i class="fa-solid fa-lightbulb text-sm"></i>
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-slate-900">Knowledge-Exchange Workshops</h4>
                                <p class="text-xs text-slate-500 mt-0.5">Publish co-authored sector whitepapers and deliver high-impact keynote sessions across South Asia.</p>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="mt-8 pt-6 border-t border-gray-100 flex items-center justify-between">
                    <a href="contact.php?category=Mentor" class="inline-flex items-center text-sm font-bold text-purple-700 hover:text-purple-800 group">
                        Join as a Mentor <i class="fa-solid fa-arrow-right ml-2 text-xs group-hover:translate-x-1 transition"></i>
                    </a>
                    <span class="text-xs text-purple-700 font-semibold bg-purple-50 px-2.5 py-1 rounded-md">Selective Community</span>
                </div>
            </div>

        </div>

    </div>
</section>


<!-- ============================================================
     TRUSTED NATIONALLY & GLOBALLY ACROSS EMERGING MARKET
     Partner network overview covering:
     - Development & Corporate Leaders
     - International Cooperation
     - Finance & Sustainability
     ============================================================ -->
<section id="partners" class="py-24 bg-gradient-to-b from-white to-agro-50/50 border-b border-gray-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="text-center max-w-3xl mx-auto mb-16">
            <span class="inline-flex items-center px-3.5 py-1.5 rounded-full text-xs font-bold bg-agro-100 text-agro-800 border border-agro-200 mb-3">
                <i class="fa-solid fa-earth-americas mr-1.5 text-agro-700"></i> Strategic Coalition
            </span>
            <h2 class="text-3xl sm:text-4xl font-extrabold text-agro-950 tracking-tight">
                Trusted Nationally & Globally Across Emerging Market
            </h2>
            <p class="mt-3 text-slate-600 text-base sm:text-lg leading-relaxed">
                Our collaborative network unites leading corporate agribusinesses, international development agencies, and pioneering sustainability funds.
            </p>
        </div>

        <!-- 3 Partner Network Pillars -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            
            <!-- Pillar 1: Development & Corporate Leaders -->
            <div class="bg-white rounded-3xl p-8 border border-gray-200 shadow-sm flex flex-col justify-between">
                <div>
                    <div class="w-12 h-12 rounded-xl bg-agro-50 text-agro-800 flex items-center justify-center text-xl mb-4">
                        <i class="fa-solid fa-building-flag"></i>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900 mb-2">Development & Corporate Leaders</h3>
                    <p class="text-xs text-slate-500 leading-relaxed mb-6">
                        Pioneering national stakeholders embedding startup technologies into commercial food distribution, seed banks, and agro-processing plants.
                    </p>

                    <!-- Partner List / Badges -->
                    <div class="space-y-3">
                        <div class="flex items-center space-x-3 p-3 rounded-xl bg-gray-50 border border-gray-100">
                            <div class="w-8 h-8 rounded-lg bg-emerald-600 text-white flex items-center justify-center font-black text-xs">
                                MOA
                            </div>
                            <div>
                                <h5 class="text-xs font-bold text-slate-800">Department of Agricultural Extension</h5>
                                <span class="text-[10px] text-slate-500">Ministry of Agriculture, Bangladesh</span>
                            </div>
                        </div>

                        <div class="flex items-center space-x-3 p-3 rounded-xl bg-gray-50 border border-gray-100">
                            <div class="w-8 h-8 rounded-lg bg-red-600 text-white flex items-center justify-center font-black text-xs">
                                BRAC
                            </div>
                            <div>
                                <h5 class="text-xs font-bold text-slate-800">BRAC Enterprises & Agriculture</h5>
                                <span class="text-[10px] text-slate-500">Rural Supply Chain Integration</span>
                            </div>
                        </div>

                        <div class="flex items-center space-x-3 p-3 rounded-xl bg-gray-50 border border-gray-100">
                            <div class="w-8 h-8 rounded-lg bg-amber-600 text-white flex items-center justify-center font-black text-xs">
                                PRAN
                            </div>
                            <div>
                                <h5 class="text-xs font-bold text-slate-800">PRAN-RFL Agribusiness Group</h5>
                                <span class="text-[10px] text-slate-500">Commercial Offtake & Processing</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-6 pt-4 border-t border-gray-100 text-[11px] text-agro-700 font-semibold flex items-center">
                    <i class="fa-solid fa-check-double mr-1.5"></i> 14+ Tier-1 National Enterprises
                </div>
            </div>

            <!-- Pillar 2: International Cooperation -->
            <div class="bg-white rounded-3xl p-8 border border-gray-200 shadow-sm flex flex-col justify-between">
                <div>
                    <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-800 flex items-center justify-center text-xl mb-4">
                        <i class="fa-solid fa-handshake-angle"></i>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900 mb-2">International Cooperation</h3>
                    <p class="text-xs text-slate-500 leading-relaxed mb-6">
                        Cross-border research alliances and bilateral innovation exchanges bringing European and Asian precision tech into deltaic farming.
                    </p>

                    <!-- Partner List / Badges -->
                    <div class="space-y-3">
                        <div class="flex items-center space-x-3 p-3 rounded-xl bg-gray-50 border border-gray-100">
                            <div class="w-8 h-8 rounded-lg bg-blue-700 text-white flex items-center justify-center font-black text-xs">
                                WUR
                            </div>
                            <div>
                                <h5 class="text-xs font-bold text-slate-800">Dutch AgriTech Consortium</h5>
                                <span class="text-[10px] text-slate-500">Greenhouse & Water Optimization</span>
                            </div>
                        </div>

                        <div class="flex items-center space-x-3 p-3 rounded-xl bg-gray-50 border border-gray-100">
                            <div class="w-8 h-8 rounded-lg bg-cyan-600 text-white flex items-center justify-center font-black text-xs">
                                UNDP
                            </div>
                            <div>
                                <h5 class="text-xs font-bold text-slate-800">UNDP Accelerator Labs</h5>
                                <span class="text-[10px] text-slate-500">Frontier Grassroots Innovation</span>
                            </div>
                        </div>

                        <div class="flex items-center space-x-3 p-3 rounded-xl bg-gray-50 border border-gray-100">
                            <div class="w-8 h-8 rounded-lg bg-indigo-700 text-white flex items-center justify-center font-black text-xs">
                                JICA
                            </div>
                            <div>
                                <h5 class="text-xs font-bold text-slate-800">Japan International Cooperation</h5>
                                <span class="text-[10px] text-slate-500">Post-Harvest Automation Grants</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-6 pt-4 border-t border-gray-100 text-[11px] text-blue-700 font-semibold flex items-center">
                    <i class="fa-solid fa-check-double mr-1.5"></i> 18+ Multilateral Agreements
                </div>
            </div>

            <!-- Pillar 3: Finance & Sustainability -->
            <div class="bg-white rounded-3xl p-8 border border-gray-200 shadow-sm flex flex-col justify-between">
                <div>
                    <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-800 flex items-center justify-center text-xl mb-4">
                        <i class="fa-solid fa-coins"></i>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900 mb-2">Finance & Sustainability</h3>
                    <p class="text-xs text-slate-500 leading-relaxed mb-6">
                        Venture capital funds, ESG syndicates, and green finance institutions mobilizing blended growth capital for our incubated ventures.
                    </p>

                    <!-- Partner List / Badges -->
                    <div class="space-y-3">
                        <div class="flex items-center space-x-3 p-3 rounded-xl bg-gray-50 border border-gray-100">
                            <div class="w-8 h-8 rounded-lg bg-emerald-700 text-white flex items-center justify-center font-black text-xs">
                                ACU
                            </div>
                            <div>
                                <h5 class="text-xs font-bold text-slate-800">Acumen Resilient Agriculture Fund</h5>
                                <span class="text-[10px] text-slate-500">Smallholder Farmer Equity</span>
                            </div>
                        </div>

                        <div class="flex items-center space-x-3 p-3 rounded-xl bg-gray-50 border border-gray-100">
                            <div class="w-8 h-8 rounded-lg bg-teal-700 text-white flex items-center justify-center font-black text-xs">
                                IDCOL
                            </div>
                            <div>
                                <h5 class="text-xs font-bold text-slate-800">Infrastructure Dev. Co. Ltd. (IDCOL)</h5>
                                <span class="text-[10px] text-slate-500">Renewable Solar Ag-Financing</span>
                            </div>
                        </div>

                        <div class="flex items-center space-x-3 p-3 rounded-xl bg-gray-50 border border-gray-100">
                            <div class="w-8 h-8 rounded-lg bg-purple-700 text-white flex items-center justify-center font-black text-xs">
                                BAN
                            </div>
                            <div>
                                <h5 class="text-xs font-bold text-slate-800">Bangladesh Angels Network</h5>
                                <span class="text-[10px] text-slate-500">Early-Stage Venture Syndicate</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-6 pt-4 border-t border-gray-100 text-[11px] text-amber-700 font-semibold flex items-center">
                    <i class="fa-solid fa-check-double mr-1.5"></i> $25M+ Dedicated Capital Pipeline
                </div>
            </div>

        </div>

    </div>
</section>


<!-- ============================================================
     VOICES OF THE COHORT / TESTIMONIAL HIGHLIGHTS
     ============================================================ -->
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="text-center max-w-2xl mx-auto mb-14">
            <span class="text-xs font-bold uppercase tracking-wider text-agro-700">Founder Perspectives</span>
            <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 mt-1">Accelerating Frontier Agri-Founders</h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            
            <div class="p-6 sm:p-8 rounded-3xl bg-agro-50/50 border border-agro-100 relative">
                <div class="text-amber-500 text-sm mb-3">
                    <i class="fa-solid fa-star"></i>
                    <i class="fa-solid fa-star"></i>
                    <i class="fa-solid fa-star"></i>
                    <i class="fa-solid fa-star"></i>
                    <i class="fa-solid fa-star"></i>
                </div>
                <p class="text-slate-700 text-sm italic leading-relaxed mb-6">
                    "NextGen Hub provided us not just seed capital, but direct access to 400 potato farmers in Bogura to pilot our soil IoT moisture sensor. That real-world validation sealed our Series Pre-Seed round."
                </p>
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-full bg-agro-700 text-white flex items-center justify-center font-bold text-sm">
                        TA
                    </div>
                    <div>
                        <h4 class="text-xs font-bold text-slate-900">Tanvir Ahmed</h4>
                        <span class="text-[11px] text-slate-500">Founder, KrishiSense IoT (Cohort 02)</span>
                    </div>
                </div>
            </div>

            <div class="p-6 sm:p-8 rounded-3xl bg-agro-50/50 border border-agro-100 relative">
                <div class="text-amber-500 text-sm mb-3">
                    <i class="fa-solid fa-star"></i>
                    <i class="fa-solid fa-star"></i>
                    <i class="fa-solid fa-star"></i>
                    <i class="fa-solid fa-star"></i>
                    <i class="fa-solid fa-star"></i>
                </div>
                <p class="text-slate-700 text-sm italic leading-relaxed mb-6">
                    "As an angel investor, finding verified AgriTech startups with solid unit economics is challenging. NextGen Hub’s due diligence and mentorship rigor is by far the highest caliber in South Asia."
                </p>
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-full bg-amber-600 text-white flex items-center justify-center font-bold text-sm">
                        FR
                    </div>
                    <div>
                        <h4 class="text-xs font-bold text-slate-900">Farhana Rahman</h4>
                        <span class="text-[11px] text-slate-500">Partner, Bengal Delta Capital</span>
                    </div>
                </div>
            </div>

            <div class="p-6 sm:p-8 rounded-3xl bg-agro-50/50 border border-agro-100 relative">
                <div class="text-amber-500 text-sm mb-3">
                    <i class="fa-solid fa-star"></i>
                    <i class="fa-solid fa-star"></i>
                    <i class="fa-solid fa-star"></i>
                    <i class="fa-solid fa-star"></i>
                    <i class="fa-solid fa-star"></i>
                </div>
                <p class="text-slate-700 text-sm italic leading-relaxed mb-6">
                    "The bridge between Dutch precision horticulture technology and Bangladesh’s deltaic smallholders was made seamless through the Incubation Hub’s field testing infrastructure."
                </p>
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-full bg-blue-700 text-white flex items-center justify-center font-bold text-sm">
                        MJ
                    </div>
                    <div>
                        <h4 class="text-xs font-bold text-slate-900">Dr. Michael Jansen</h4>
                        <span class="text-[11px] text-slate-500">Director, Wageningen Agro Partners</span>
                    </div>
                </div>
            </div>

        </div>

    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
