<?php
$currentPage = 'contact';
$pageTitle = 'Apply & Connect | NextGen Farming Incubation Hub';
$pageDescription = 'Apply for incubation, partner as an investor, collaborate as an organization, or mentor agri-founders with NextGen Farming Incubation Hub.';
require_once __DIR__ . '/includes/header.php';
?>

<!-- Header Banner -->
<section class="relative bg-gradient-to-b from-agro-50/70 to-white pt-16 pb-16 border-b border-agro-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="max-w-3xl">
            <span class="inline-flex items-center px-3.5 py-1.5 rounded-full text-xs font-bold bg-agro-100 text-agro-800 border border-agro-200 mb-4">
                <i class="fa-solid fa-paper-plane mr-1.5 text-agro-700"></i> Direct Ecosystem Intake
            </span>
            <h1 class="text-4xl sm:text-5xl font-extrabold text-agro-950 tracking-tight leading-tight">
                Connect & Apply to NextGen Hub
            </h1>
            <p class="mt-4 text-base sm:text-lg text-slate-600 leading-relaxed">
                Whether you are an ambitious AgriTech founder, venture capitalist, institutional stakeholder, or domain expert — choose your track below to connect directly with our incubation team.
            </p>
        </div>
    </div>
</section>

<!-- Form & Contact Details Section -->
<section class="py-16 bg-[#fbfdfc]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12">
            
            <!-- Left Column: Interactive Form (8 Cols) -->
            <div class="lg:col-span-8">
                
                <div class="bg-white rounded-3xl p-6 sm:p-10 shadow-xl border border-gray-200 relative">
                    
                    <!-- Alert Message Container for AJAX feedback -->
                    <div id="form-alert-container"></div>

                    <!-- Category Selector Pills -->
                    <div class="mb-8">
                        <label class="block text-xs font-extrabold text-slate-700 uppercase tracking-wider mb-3">
                            Step 1: Select Your Stakeholder Category <span class="text-red-500">*</span>
                        </label>
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                            
                            <button type="button" data-category="Entrepreneur" class="category-pill-btn p-3.5 rounded-2xl border text-center transition flex flex-col items-center justify-center space-y-1.5 border-emerald-600 bg-emerald-50 text-emerald-800 ring-2 ring-emerald-500">
                                <i class="fa-solid fa-seedling text-lg"></i>
                                <span class="text-xs font-bold">Entrepreneur</span>
                            </button>

                            <button type="button" data-category="Investor" class="category-pill-btn p-3.5 rounded-2xl border text-center transition flex flex-col items-center justify-center space-y-1.5 border-gray-200 bg-white text-gray-700 hover:border-emerald-300">
                                <i class="fa-solid fa-chart-line text-lg"></i>
                                <span class="text-xs font-bold">Investor</span>
                            </button>

                            <button type="button" data-category="Support Organisation" class="category-pill-btn p-3.5 rounded-2xl border text-center transition flex flex-col items-center justify-center space-y-1.5 border-gray-200 bg-white text-gray-700 hover:border-emerald-300">
                                <i class="fa-solid fa-building-columns text-lg"></i>
                                <span class="text-xs font-bold">Organisation</span>
                            </button>

                            <button type="button" data-category="Mentor" class="category-pill-btn p-3.5 rounded-2xl border text-center transition flex flex-col items-center justify-center space-y-1.5 border-gray-200 bg-white text-gray-700 hover:border-emerald-300">
                                <i class="fa-solid fa-user-graduate text-lg"></i>
                                <span class="text-xs font-bold">Mentor</span>
                            </button>

                        </div>
                    </div>

                    <!-- Main Form (Handled via jQuery AJAX to api/submit.php) -->
                    <form id="hub-contact-form" class="space-y-6">
                        
                        <!-- Hidden category input bound to pills -->
                        <input type="hidden" name="category" id="selected-category-input" value="Entrepreneur">
                        
                        <!-- Anti-spam Honeypot -->
                        <div class="hidden">
                            <label>Leave this empty</label>
                            <input type="text" name="website_trap" tabindex="-1" autocomplete="off">
                        </div>

                        <!-- Step 2: Information Fields -->
                        <div class="border-t border-gray-100 pt-6">
                            <span class="block text-xs font-extrabold text-slate-700 uppercase tracking-wider mb-4">
                                Step 2: Primary Contact & Organization Details
                            </span>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                                
                                <div>
                                    <label for="full_name" class="block text-xs font-bold text-slate-700 mb-1">
                                        Full Name <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" id="full_name" name="full_name" required placeholder="e.g. Tanvir Ahmed" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm text-slate-900 form-input-focus transition">
                                </div>

                                <div>
                                    <label for="email" class="block text-xs font-bold text-slate-700 mb-1">
                                        Email Address <span class="text-red-500">*</span>
                                    </label>
                                    <input type="email" id="email" name="email" required placeholder="e.g. tanvir@krishisense.bd" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm text-slate-900 form-input-focus transition">
                                </div>

                                <div>
                                    <label for="phone" class="block text-xs font-bold text-slate-700 mb-1">
                                        Phone / WhatsApp Number
                                    </label>
                                    <input type="tel" id="phone" name="phone" placeholder="+880 1712-000000" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm text-slate-900 form-input-focus transition">
                                </div>

                                <div>
                                    <label for="organization" class="block text-xs font-bold text-slate-700 mb-1">
                                        Venture / Firm / Institution Name
                                    </label>
                                    <input type="text" id="organization" name="organization" placeholder="e.g. KrishiSense Technologies Ltd." class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm text-slate-900 form-input-focus transition">
                                </div>

                            </div>
                        </div>

                        <!-- Step 3: Track Specific Details -->
                        <div class="border-t border-gray-100 pt-6 space-y-5">
                            <span class="block text-xs font-extrabold text-slate-700 uppercase tracking-wider mb-2">
                                Step 3: Application Scope & Details
                            </span>

                            <div>
                                <label for="sub_interest" class="block text-xs font-bold text-slate-700 mb-1">
                                    Specific Program or Engagement Track
                                </label>
                                <select id="sub_interest" name="sub_interest" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm text-slate-900 form-input-focus transition">
                                    <!-- Options populated dynamically by jQuery -->
                                    <option value="AgriTech Ignite Accelerator (Early Stage)">AgriTech Ignite Accelerator (Early Stage)</option>
                                    <option value="HarvestScale Growth Cohort (Series Seed/A)">HarvestScale Growth Cohort (Series Seed/A)</option>
                                    <option value="Seed Fundraising & Investor Matching">Seed Fundraising & Investor Matching</option>
                                    <option value="Farmland Pilot Testing & Lab Access">Farmland Pilot Testing & Lab Access</option>
                                    <option value="Export Market & Value Chain Linkages">Export Market & Value Chain Linkages</option>
                                </select>
                            </div>

                            <div>
                                <label id="extra-field-label" for="pitch_url" class="block text-xs font-bold text-slate-700 mb-1">
                                    Pitch Deck URL or Venture Website
                                </label>
                                <input type="url" id="pitch_url" name="pitch_url" placeholder="https://krishisense.bd or Google Drive link" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm text-slate-900 form-input-focus transition">
                                <span id="extra-field-help" class="text-[11px] text-slate-400 mt-1 block">Link to your pitch deck, MVP demo video, or company overview</span>
                            </div>

                            <div>
                                <label for="message" class="block text-xs font-bold text-slate-700 mb-1">
                                    Brief Overview & Objectives <span class="text-red-500">*</span>
                                </label>
                                <textarea id="message" name="message" rows="5" required placeholder="Please tell us about your agribusiness model, the farming challenge you solve in Bangladesh or emerging markets, your traction, and how the hub can support you..." class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm text-slate-900 form-input-focus transition leading-relaxed"></textarea>
                            </div>

                        </div>

                        <!-- Submission Action -->
                        <div class="pt-4">
                            <button id="btn-submit-form" type="submit" class="w-full py-4 px-6 bg-gradient-to-r from-agro-800 to-agro-700 hover:from-agro-900 hover:to-agro-800 text-white font-bold text-sm sm:text-base rounded-2xl shadow-xl shadow-agro-800/25 transition flex items-center justify-center space-x-2">
                                <i class="fa-solid fa-spinner fa-spin hidden btn-spinner"></i>
                                <span class="btn-text">Submit Application / Inquiry</span>
                                <i class="fa-solid fa-paper-plane text-emerald-300 text-xs"></i>
                            </button>
                            <p class="text-center text-xs text-slate-400 mt-3 flex items-center justify-center">
                                <i class="fa-solid fa-lock text-emerald-600 mr-1.5"></i>
                                Strictly confidential. We respect founder IP under standard incubation NDAs.
                            </p>
                        </div>

                    </form>

                </div>

            </div>

            <!-- Right Column: Hub Contact Info & SLA (4 Cols) -->
            <div class="lg:col-span-4 space-y-6">
                
                <!-- Response SLA Box -->
                <div class="bg-agro-900 text-white p-6 rounded-3xl shadow-lg border border-agro-800 relative overflow-hidden">
                    <div class="absolute -right-6 -bottom-6 w-32 h-32 bg-emerald-500/10 rounded-full blur-xl"></div>
                    <div class="flex items-center space-x-3 mb-4">
                        <div class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center text-emerald-400">
                            <i class="fa-solid fa-bolt text-lg"></i>
                        </div>
                        <div>
                            <h3 class="font-extrabold text-sm">Rapid Evaluation SLA</h3>
                            <span class="text-xs text-emerald-300">Reviewed within 48 Hours</span>
                        </div>
                    </div>
                    <p class="text-xs text-emerald-100/80 leading-relaxed">
                        Every submission is logged directly to our MySQL pipeline and routed to the corresponding sector manager (Admissions, Angel Network, or Partnerships).
                    </p>
                </div>

                <!-- Office Contact Card -->
                <div class="bg-white p-6 rounded-3xl border border-gray-200 shadow-sm space-y-4">
                    <h3 class="font-bold text-slate-900 text-sm uppercase tracking-wider border-l-2 border-agro-700 pl-2">
                        Dhaka Operations Hub
                    </h3>

                    <div class="space-y-3 text-xs text-slate-600">
                        <div class="flex items-start space-x-3">
                            <i class="fa-solid fa-location-dot text-agro-700 mt-1"></i>
                            <div>
                                <strong class="text-slate-800 block">Headquarters:</strong>
                                <span>Level 7, Venture Tower, Road 11, Gulshan-2, Dhaka 1212, Bangladesh</span>
                            </div>
                        </div>

                        <div class="flex items-start space-x-3">
                            <i class="fa-solid fa-envelope text-agro-700 mt-1"></i>
                            <div>
                                <strong class="text-slate-800 block">General Inquiries:</strong>
                                <span>connect@nextgenfarminghub.bd</span>
                            </div>
                        </div>

                        <div class="flex items-start space-x-3">
                            <i class="fa-solid fa-phone text-agro-700 mt-1"></i>
                            <div>
                                <strong class="text-slate-800 block">Telephone:</strong>
                                <span>+880 2 988-5544 / +880 1711-002233</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Dedicated Inboxes -->
                <div class="bg-white p-6 rounded-3xl border border-gray-200 shadow-sm space-y-3 text-xs">
                    <h4 class="font-bold text-slate-900 uppercase tracking-wider text-[11px]">Direct Sector Inboxes</h4>
                    
                    <div class="p-3 rounded-xl bg-gray-50 flex items-center justify-between">
                        <div>
                            <span class="font-bold text-slate-800 block">Startup Admissions</span>
                            <span class="text-slate-500">accelerate@nextgenfarminghub.bd</span>
                        </div>
                        <i class="fa-solid fa-seedling text-emerald-600"></i>
                    </div>

                    <div class="p-3 rounded-xl bg-gray-50 flex items-center justify-between">
                        <div>
                            <span class="font-bold text-slate-800 block">Investor Relations</span>
                            <span class="text-slate-500">investors@nextgenfarminghub.bd</span>
                        </div>
                        <i class="fa-solid fa-chart-line text-amber-600"></i>
                    </div>

                    <div class="p-3 rounded-xl bg-gray-50 flex items-center justify-between">
                        <div>
                            <span class="font-bold text-slate-800 block">Institutional Alliances</span>
                            <span class="text-slate-500">partners@nextgenfarminghub.bd</span>
                        </div>
                        <i class="fa-solid fa-handshake text-blue-600"></i>
                    </div>
                </div>


            </div>

        </div>

    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
