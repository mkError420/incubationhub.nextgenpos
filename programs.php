<?php
$currentPage = 'programs';
$pageTitle = 'Accelerator Programs & Residencies | NextGen Farming Incubation Hub';
$pageDescription = 'Discover NextGen Farming Incubation Hub accelerator cohorts, deep-tech founder residencies, seed capital, and farmland living labs in Bangladesh and emerging markets.';
require_once __DIR__ . '/includes/header.php';
?>

<!-- Header Banner -->
<section class="relative bg-gradient-to-b from-agro-50/70 to-white pt-16 pb-20 border-b border-agro-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="max-w-3xl">
            <span class="inline-flex items-center px-3.5 py-1.5 rounded-full text-xs font-bold bg-agro-100 text-agro-800 border border-agro-200 mb-4">
                <i class="fa-solid fa-rocket mr-1.5 text-agro-700"></i> Structured Incubation Tracks
            </span>
            <h1 class="text-4xl sm:text-5xl font-extrabold text-agro-950 tracking-tight leading-tight">
                Programs & Founder Residencies
            </h1>
            <p class="mt-4 text-base sm:text-lg text-slate-600 leading-relaxed">
                From early lab prototypes to multi-district commercial scale, our specialized tracks provide catalytic risk capital, empirical farmland living labs, and global venture guidance.
            </p>
        </div>
    </div>
</section>

<!-- Active Accelerator Programs Section -->
<section class="py-20 bg-white border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="text-center max-w-2xl mx-auto mb-16">
            <span class="text-xs font-bold uppercase tracking-wider text-agro-700">Flagship Tracks</span>
            <h2 class="text-3xl font-extrabold text-slate-900 mt-1">Accelerating Every Stage of Growth</h2>
            <p class="mt-2 text-sm text-slate-500">Structured programs built specifically for agricultural founders in emerging economies.</p>
        </div>

        <?php $activePrograms = getPrograms(true); ?>
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-10">
            <?php foreach ($activePrograms as $idx => $prog): 
                $borderStyle = $idx === 0 ? 'border-2 border-emerald-500/30 shadow-lg' : 'border border-gray-200 shadow-sm card-gradient-hover';
                $btnColor = match ($idx % 4) {
                    0 => 'bg-agro-800 hover:bg-agro-900',
                    1 => 'bg-slate-900 hover:bg-black',
                    2 => 'bg-blue-700 hover:bg-blue-800',
                    default => 'bg-purple-700 hover:bg-purple-800'
                };
            ?>
            <div class="p-8 sm:p-10 rounded-3xl bg-white <?= $borderStyle ?> relative flex flex-col justify-between">
                <div class="absolute top-6 right-6">
                    <span class="px-3 py-1 bg-emerald-100 text-emerald-800 font-bold text-xs rounded-full">
                        <?= htmlspecialchars($prog['badge_status']) ?>
                    </span>
                </div>

                <div>
                    <div class="w-14 h-14 rounded-2xl bg-agro-50 text-agro-800 flex items-center justify-center text-2xl mb-6">
                        <i class="fa-solid <?= htmlspecialchars($prog['icon']) ?>"></i>
                    </div>

                    <h3 class="text-2xl font-black text-slate-900 mb-2"><?= htmlspecialchars($prog['title']) ?></h3>
                    <span class="text-xs font-bold text-agro-700 uppercase tracking-wider block mb-4"><?= htmlspecialchars($prog['stage_tag']) ?></span>

                    <p class="text-sm text-slate-600 leading-relaxed mb-6">
                        <?= htmlspecialchars($prog['description']) ?>
                    </p>

                    <div class="space-y-3 pt-2 pb-6 border-t border-gray-100 text-xs">
                        <?php if (!empty($prog['funding_text'])): ?>
                            <div class="flex items-center justify-between text-slate-700">
                                <span class="font-semibold flex items-center"><i class="fa-solid fa-coins text-amber-500 mr-2"></i> Catalytic Funding:</span>
                                <span class="font-bold text-agro-900"><?= htmlspecialchars($prog['funding_text']) ?></span>
                            </div>
                        <?php endif; ?>
                        <?php if (!empty($prog['duration'])): ?>
                            <div class="flex items-center justify-between text-slate-700">
                                <span class="font-semibold flex items-center"><i class="fa-solid fa-clock text-blue-500 mr-2"></i> Duration:</span>
                                <span class="font-bold text-slate-900"><?= htmlspecialchars($prog['duration']) ?></span>
                            </div>
                        <?php endif; ?>
                        <?php if (!empty($prog['field_access'])): ?>
                            <div class="flex items-center justify-between text-slate-700">
                                <span class="font-semibold flex items-center"><i class="fa-solid fa-vial text-emerald-500 mr-2"></i> Field Access:</span>
                                <span class="font-bold text-slate-900"><?= htmlspecialchars($prog['field_access']) ?></span>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="pt-6 border-t border-gray-100 flex items-center justify-between">
                    <a href="contact.php?category=Entrepreneur" class="inline-flex items-center px-5 py-2.5 <?= $btnColor ?> text-white font-bold text-xs rounded-xl shadow transition">
                        Apply for <?= htmlspecialchars($prog['title']) ?> <i class="fa-solid fa-arrow-right ml-2 text-[10px]"></i>
                    </a>
                    <span class="text-[11px] text-slate-400">Selective Intake</span>
                </div>
            </div>
            <?php endforeach; ?>
        </div>    </div>

    </div>
</section>

<!-- Curriculum Timeline Breakdown -->
<section id="curriculum" class="py-20 bg-[#f8fafc] border-b border-gray-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="text-center max-w-2xl mx-auto mb-16">
            <span class="text-xs font-bold uppercase tracking-wider text-agro-700">The 16-Week Journey</span>
            <h2 class="text-3xl font-extrabold text-slate-900 mt-1">Incubation Curriculum Architecture</h2>
            <p class="mt-2 text-sm text-slate-500">How we turn early ideas into investable, high-impact ventures.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            
            <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm relative">
                <span class="text-xs font-black text-agro-700 bg-agro-50 px-2.5 py-1 rounded-md inline-block mb-3">Weeks 1 - 4</span>
                <h4 class="font-bold text-base text-slate-900 mb-2">Phase 1: Problem & Agronomy Grounding</h4>
                <p class="text-xs text-slate-500 leading-relaxed">
                    Farmer stakeholder immersion, soil & climate mapping, customer pain validation, and technical architecture review.
                </p>
            </div>

            <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm relative">
                <span class="text-xs font-black text-agro-700 bg-agro-50 px-2.5 py-1 rounded-md inline-block mb-3">Weeks 5 - 8</span>
                <h4 class="font-bold text-base text-slate-900 mb-2">Phase 2: Living Lab Farmland Trials</h4>
                <p class="text-xs text-slate-500 leading-relaxed">
                    Deploying sensors, micro-drip prototypes, or biological formulations on 150-hectare pilot fields with cooperative farmers.
                </p>
            </div>

            <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm relative">
                <span class="text-xs font-black text-agro-700 bg-agro-50 px-2.5 py-1 rounded-md inline-block mb-3">Weeks 9 - 12</span>
                <h4 class="font-bold text-base text-slate-900 mb-2">Phase 3: Unit Economics & Distribution</h4>
                <p class="text-xs text-slate-500 leading-relaxed">
                    Refining CAC/LTV, pricing models suited for smallholders, cold-chain logistics agreements, and supply partner contracts.
                </p>
            </div>

            <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm relative">
                <span class="text-xs font-black text-agro-700 bg-agro-50 px-2.5 py-1 rounded-md inline-block mb-3">Weeks 13 - 16</span>
                <h4 class="font-bold text-base text-slate-900 mb-2">Phase 4: Demo Day & Capital Syndicate</h4>
                <p class="text-xs text-slate-500 leading-relaxed">
                    Pitching live to 40+ regional and international VC funds, angel syndicates, and corporate venture capital executives.
                </p>
            </div>

        </div>

    </div>
</section>

<!-- Frequently Asked Questions Accordion -->
<section class="py-20 bg-white">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="text-center mb-12">
            <span class="text-xs font-bold uppercase tracking-wider text-agro-700">Clarifications</span>
            <h2 class="text-3xl font-extrabold text-slate-900 mt-1">Frequently Asked Questions</h2>
        </div>

        <div class="space-y-4">
            
            <div class="border border-gray-200 rounded-2xl overflow-hidden">
                <button type="button" class="w-full text-left px-6 py-4.5 bg-gray-50/70 hover:bg-gray-100 flex items-center justify-between font-bold text-slate-900 text-sm faq-question-btn">
                    <span>What stage of startup is eligible for the AgriTech Ignite Accelerator?</span>
                    <i class="fa-solid fa-chevron-down text-xs text-slate-400 transition-transform duration-200 faq-chevron"></i>
                </button>
                <div class="px-6 py-4 text-xs text-slate-600 leading-relaxed hidden faq-answer-content bg-white border-t border-gray-100">
                    We accept early-stage teams with at least a functioning prototype, biological bench trial, software MVP, or deep-rooted domain insight. Full commercial revenue is not mandatory at entry; our goal is to help you achieve your first paid farmer or B2B enterprise pilot during the cohort.
                </div>
            </div>

            <div class="border border-gray-200 rounded-2xl overflow-hidden">
                <button type="button" class="w-full text-left px-6 py-4.5 bg-gray-50/70 hover:bg-gray-100 flex items-center justify-between font-bold text-slate-900 text-sm faq-question-btn">
                    <span>Do founders need to relocate to Dhaka during the program?</span>
                    <i class="fa-solid fa-chevron-down text-xs text-slate-400 transition-transform duration-200 faq-chevron"></i>
                </button>
                <div class="px-6 py-4 text-xs text-slate-600 leading-relaxed hidden faq-answer-content bg-white border-t border-gray-100">
                    No. Our program is hybrid. Core masterclasses and pitch clinics occur virtually, while intensive pilot trials take place in regional agricultural corridors (e.g. Bogura, Dinajpur, and Mymensingh) where our living testbeds and cooperative partner hubs are situated.
                </div>
            </div>

            <div class="border border-gray-200 rounded-2xl overflow-hidden">
                <button type="button" class="w-full text-left px-6 py-4.5 bg-gray-50/70 hover:bg-gray-100 flex items-center justify-between font-bold text-slate-900 text-sm faq-question-btn">
                    <span>Can international startups outside Bangladesh apply?</span>
                    <i class="fa-solid fa-chevron-down text-xs text-slate-400 transition-transform duration-200 faq-chevron"></i>
                </button>
                <div class="px-6 py-4 text-xs text-slate-600 leading-relaxed hidden faq-answer-content bg-white border-t border-gray-100">
                    Yes. We actively encourage startups from South Asia, Southeast Asia, the Netherlands, and across emerging markets who wish to pilot their technology or establish a joint venture in Bangladesh's high-density agricultural ecosystem.
                </div>
            </div>

            <div class="border border-gray-200 rounded-2xl overflow-hidden">
                <button type="button" class="w-full text-left px-6 py-4.5 bg-gray-50/70 hover:bg-gray-100 flex items-center justify-between font-bold text-slate-900 text-sm faq-question-btn">
                    <span>How does the hub protect our proprietary intellectual property (IP)?</span>
                    <i class="fa-solid fa-chevron-down text-xs text-slate-400 transition-transform duration-200 faq-chevron"></i>
                </button>
                <div class="px-6 py-4 text-xs text-slate-600 leading-relaxed hidden faq-answer-content bg-white border-t border-gray-100">
                    All mentor engagements, advisory reviews, and living lab testing protocols are conducted under strict mutual non-disclosure agreements (NDAs). Founders retain 100% ownership of their IP, hardware designs, algorithms, and biological formulas.
                </div>
            </div>

        </div>

    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
