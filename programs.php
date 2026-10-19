<?php
$currentPage = 'programs';
$pageTitle = 'Accelerator Programs & Residencies | NextGen Farming Incubation Hub';
$pageDescription = 'Discover NextGen Farming Incubation Hub accelerator cohorts, deep-tech founder residencies, seed capital, and farmland living labs in Bangladesh and emerging markets.';
require_once __DIR__ . '/includes/header.php';

// Dynamic CMS content
$progBadge = getSiteSetting('programs_badge', 'Structured Incubation Tracks');
$progTitle = getSiteSetting('programs_title', 'Programs & Founder Residencies');
$progDesc  = getSiteSetting('programs_desc', 'From early lab prototypes to multi-district commercial scale, our specialized tracks provide catalytic risk capital, empirical farmland living labs, and global venture guidance.');
?>

<!-- Header Banner -->
<section class="relative bg-gradient-to-b from-agro-50/70 to-white pt-16 pb-20 border-b border-agro-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="max-w-3xl">
            <span class="inline-flex items-center px-3.5 py-1.5 rounded-full text-xs font-bold bg-agro-100 text-agro-800 border border-agro-200 mb-4">
                <i class="fa-solid fa-rocket mr-1.5 text-agro-700"></i> <?= htmlspecialchars($progBadge) ?>
            </span>
            <h1 class="text-4xl sm:text-5xl font-extrabold text-agro-950 tracking-tight leading-tight">
                <?= htmlspecialchars($progTitle) ?>
            </h1>
            <p class="mt-4 text-base sm:text-lg text-slate-600 leading-relaxed">
                <?= htmlspecialchars($progDesc) ?>
            </p>
        </div>
    </div>
</section>

<!-- Active Accelerator Programs Section (Dynamic from Database & Admin) -->
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
        </div>

    </div>
</section>

<!-- Curriculum Timeline Breakdown (Dynamic from Admin Dashboard) -->
<section id="curriculum" class="py-20 bg-[#f8fafc] border-b border-gray-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="text-center max-w-2xl mx-auto mb-16">
            <span class="text-xs font-bold uppercase tracking-wider text-agro-700">The 16-Week Journey</span>
            <h2 class="text-3xl font-extrabold text-slate-900 mt-1">Incubation Curriculum Architecture</h2>
            <p class="mt-2 text-sm text-slate-500">How we turn early ideas into investable, high-impact ventures.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <?php for ($i = 1; $i <= 4; $i++): 
                $weeks = getSiteSetting("curriculum_phase{$i}_weeks", "Weeks " . (($i-1)*4 + 1) . " - " . ($i*4));
                $title = getSiteSetting("curriculum_phase{$i}_title", "Phase {$i}");
                $desc  = getSiteSetting("curriculum_phase{$i}_desc", "");
            ?>
            <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm relative">
                <span class="text-xs font-black text-agro-700 bg-agro-50 px-2.5 py-1 rounded-md inline-block mb-3"><?= htmlspecialchars($weeks) ?></span>
                <h4 class="font-bold text-base text-slate-900 mb-2"><?= htmlspecialchars($title) ?></h4>
                <p class="text-xs text-slate-500 leading-relaxed">
                    <?= htmlspecialchars($desc) ?>
                </p>
            </div>
            <?php endfor; ?>
        </div>

    </div>
</section>

<!-- Frequently Asked Questions Accordion (Dynamic from Admin Dashboard) -->
<section class="py-20 bg-white">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="text-center mb-12">
            <span class="text-xs font-bold uppercase tracking-wider text-agro-700">Clarifications</span>
            <h2 class="text-3xl font-extrabold text-slate-900 mt-1">Frequently Asked Questions</h2>
        </div>

        <div class="space-y-4">
            <?php for ($f = 1; $f <= 4; $f++): 
                $faqQ = getSiteSetting("faq{$f}_q", "");
                $faqA = getSiteSetting("faq{$f}_a", "");
                if (empty($faqQ)) continue;
            ?>
            <div class="border border-gray-200 rounded-2xl overflow-hidden">
                <button type="button" class="w-full text-left px-6 py-4.5 bg-gray-50/70 hover:bg-gray-100 flex items-center justify-between font-bold text-slate-900 text-sm faq-question-btn">
                    <span><?= htmlspecialchars($faqQ) ?></span>
                    <i class="fa-solid fa-chevron-down text-xs text-slate-400 transition-transform duration-200 faq-chevron"></i>
                </button>
                <div class="px-6 py-4 text-xs text-slate-600 leading-relaxed hidden faq-answer-content bg-white border-t border-gray-100">
                    <?= nl2br(htmlspecialchars($faqA)) ?>
                </div>
            </div>
            <?php endfor; ?>
        </div>

    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
