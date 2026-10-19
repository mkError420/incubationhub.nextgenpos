<?php
http_response_code(404);
$currentPage = '404';
$pageTitle = '404 - Page Not Found | NextGen Farming Incubation Hub';
$pageDescription = 'The requested page could not be found on NextGen Farming Incubation Hub.';
require_once __DIR__ . '/includes/header.php';
?>

<section class="min-h-[70vh] flex items-center justify-center py-20 px-4 bg-gradient-to-b from-agro-50/50 to-white">
    <div class="max-w-xl mx-auto text-center">
        
        <!-- Agricultural 404 Badge -->
        <div class="w-24 h-24 mx-auto rounded-3xl bg-agro-100 text-agro-800 flex items-center justify-center text-4xl mb-6 shadow-md shadow-agro-800/10">
            <i class="fa-solid fa-wheat-awn-circle-exclamation text-agro-700"></i>
        </div>

        <span class="inline-block px-3 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-800 border border-amber-200 mb-3">
            Error 404
        </span>

        <h1 class="text-4xl sm:text-5xl font-extrabold text-slate-900 tracking-tight">
            Field Not Found
        </h1>

        <p class="mt-4 text-base text-slate-600 leading-relaxed max-w-md mx-auto">
            The page or program you are looking for may have moved, been harvested, or does not exist on this server.
        </p>

        <!-- Action Buttons -->
        <div class="mt-8 flex flex-col sm:flex-row items-center justify-center gap-3">
            <a href="index.php" class="w-full sm:w-auto px-6 py-3.5 bg-agro-800 hover:bg-agro-900 text-white font-bold text-sm rounded-xl shadow-lg shadow-agro-800/20 transition flex items-center justify-center">
                <i class="fa-solid fa-house mr-2"></i> Return to Homepage
            </a>
            <a href="programs.php" class="w-full sm:w-auto px-6 py-3.5 bg-white hover:bg-gray-50 text-slate-800 font-bold text-sm rounded-xl border border-gray-200 shadow-sm transition flex items-center justify-center">
                <i class="fa-solid fa-compass mr-2 text-agro-700"></i> Browse Programs
            </a>
            <a href="contact.php" class="w-full sm:w-auto px-6 py-3.5 bg-white hover:bg-gray-50 text-slate-800 font-bold text-sm rounded-xl border border-gray-200 shadow-sm transition flex items-center justify-center">
                <i class="fa-solid fa-envelope mr-2 text-agro-700"></i> Contact Us
            </a>
        </div>

        <div class="mt-10 pt-8 border-t border-gray-200/60 text-xs text-slate-400">
            Need urgent assistance? Email us at <a href="mailto:connect@nextgenfarminghub.bd" class="text-agro-700 underline font-semibold">connect@nextgenfarminghub.bd</a>
        </div>

    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
