<?php
$currentPage = 'networking';
$pageTitle = 'Global AgriTech Network & Ecosystem Partners | NextGen Farming Hub';
$pageDescription = 'Connect with premier agri-founders, venture funds, agricultural research universities, and corporate off-takers across Bangladesh and emerging markets.';
require_once __DIR__ . '/includes/header.php';
?>

<!-- Header Hero Banner -->
<section class="relative bg-gradient-to-b from-agro-50/80 via-white to-white pt-16 pb-20 border-b border-agro-100 overflow-hidden">
    <!-- Ambient Glow Backgrounds -->
    <div class="absolute top-0 right-10 w-96 h-96 bg-emerald-400/10 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute bottom-0 left-10 w-80 h-80 bg-blue-300/15 rounded-full blur-3xl pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="max-w-3xl">
            <span class="inline-flex items-center px-3.5 py-1.5 rounded-full text-xs font-bold bg-agro-100 text-agro-800 border border-agro-200 mb-4">
                <i class="fa-solid fa-network-wired mr-1.5 text-agro-700"></i> Connecting Entrepreneur Ecosystem Builders
            </span>
            <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-agro-950 tracking-tight leading-tight">
                A Connected Web of <span class="text-gradient-emerald">Founders, Investors & Scientists</span>
            </h1>
            <p class="mt-4 text-base sm:text-lg text-slate-600 leading-relaxed">
                Agriculture cannot be transformed in isolation. NextGen Farming Incubation Hub weaves together Bangladesh’s rural innovators, leading agronomy universities, regional venture syndicates, and global emerging market networks.
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

        <!-- Global Network Quick Stats Bar -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 pt-16 border-t border-gray-100 mt-16">
            <div class="p-6 rounded-2xl bg-white border border-gray-100 shadow-sm">
                <span class="text-3xl font-extrabold text-agro-900">5,200+</span>
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mt-1">Network Members</p>
                <p class="text-[11px] text-slate-400 mt-1">Across all 64 agricultural districts</p>
            </div>
            <div class="p-6 rounded-2xl bg-white border border-gray-100 shadow-sm">
                <span class="text-3xl font-extrabold text-agro-900">128+</span>
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mt-1">Ventures Accelerated</p>
                <p class="text-[11px] text-slate-400 mt-1">High-impact AgriTech solutions</p>
            </div>
            <div class="p-6 rounded-2xl bg-white border border-gray-100 shadow-sm">
                <span class="text-3xl font-extrabold text-agro-900">48+</span>
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mt-1">Institutional Partners</p>
                <p class="text-[11px] text-slate-400 mt-1">Universities, corporates & donors</p>
            </div>
            <div class="p-6 rounded-2xl bg-white border border-gray-100 shadow-sm">
                <span class="text-3xl font-extrabold text-agro-900">18</span>
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mt-1">Global Corridors</p>
                <p class="text-[11px] text-slate-400 mt-1">South Asia, Netherlands & Africa</p>
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

        <!-- Directory Cards Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8" id="network-cards-grid">
            
            <!-- Card 1: KrishiSense IoT -->
            <div class="net-card bg-[#fbfdfc] rounded-3xl p-7 border border-gray-200/80 shadow-sm hover:shadow-xl hover:border-agro-300 transition duration-300 flex flex-col justify-between" data-category="founder">
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <span class="px-2.5 py-1 bg-emerald-100 text-emerald-800 font-bold text-[11px] rounded-full">Portfolio Startup</span>
                        <span class="text-xs text-slate-400"><i class="fa-solid fa-location-dot mr-1"></i> Bogura & Dhaka</span>
                    </div>
                    <div class="flex items-center space-x-3 mb-4">
                        <div class="w-12 h-12 rounded-2xl bg-agro-700 text-white flex items-center justify-center text-xl font-black">
                            <i class="fa-solid fa-seedling"></i>
                        </div>
                        <div>
                            <h3 class="font-extrabold text-slate-900 text-lg">KrishiSense IoT</h3>
                            <span class="text-xs text-agro-700 font-semibold">Precision Soil & Irrigation Tech</span>
                        </div>
                    </div>
                    <p class="text-xs text-slate-600 leading-relaxed mb-4">
                        Deployed solar IoT sensors across 4,200 acres in Bogura and Rajshahi, reducing smallholder nitrogen fertilizer overuse by 28% and water waste by 35%.
                    </p>
                </div>
                <div class="pt-4 border-t border-gray-100 flex items-center justify-between text-xs">
                    <span class="text-slate-500 font-medium">Cohort 02 Alum</span>
                    <a href="contact.php?category=Entrepreneur" class="text-agro-800 font-bold hover:underline">Connect &rarr;</a>
                </div>
            </div>

            <!-- Card 2: Bengal Delta Capital -->
            <div class="net-card bg-[#fbfdfc] rounded-3xl p-7 border border-gray-200/80 shadow-sm hover:shadow-xl hover:border-agro-300 transition duration-300 flex flex-col justify-between" data-category="investor">
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <span class="px-2.5 py-1 bg-amber-100 text-amber-900 font-bold text-[11px] rounded-full">Syndicate Partner</span>
                        <span class="text-xs text-slate-400"><i class="fa-solid fa-location-dot mr-1"></i> Dhaka & Singapore</span>
                    </div>
                    <div class="flex items-center space-x-3 mb-4">
                        <div class="w-12 h-12 rounded-2xl bg-amber-600 text-white flex items-center justify-center text-xl font-black">
                            <i class="fa-solid fa-landmark"></i>
                        </div>
                        <div>
                            <h3 class="font-extrabold text-slate-900 text-lg">Bengal Delta Capital</h3>
                            <span class="text-xs text-amber-700 font-semibold">Seed & Series A Venture Fund</span>
                        </div>
                    </div>
                    <p class="text-xs text-slate-600 leading-relaxed mb-4">
                        A $20M early-stage fund backing climate-smart agriculture, digital input distribution marketplaces, and cold chain logistics across Bangladesh.
                    </p>
                </div>
                <div class="pt-4 border-t border-gray-100 flex items-center justify-between text-xs">
                    <span class="text-slate-500 font-medium">Co-Investment Lead</span>
                    <a href="contact.php?category=Investor" class="text-amber-800 font-bold hover:underline">Syndicate Deals &rarr;</a>
                </div>
            </div>

            <!-- Card 3: Bangladesh Agricultural University -->
            <div class="net-card bg-[#fbfdfc] rounded-3xl p-7 border border-gray-200/80 shadow-sm hover:shadow-xl hover:border-agro-300 transition duration-300 flex flex-col justify-between" data-category="academic">
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <span class="px-2.5 py-1 bg-blue-100 text-blue-900 font-bold text-[11px] rounded-full">Research Anchor</span>
                        <span class="text-xs text-slate-400"><i class="fa-solid fa-location-dot mr-1"></i> Mymensingh</span>
                    </div>
                    <div class="flex items-center space-x-3 mb-4">
                        <div class="w-12 h-12 rounded-2xl bg-blue-700 text-white flex items-center justify-center text-xl font-black">
                            <i class="fa-solid fa-microscope"></i>
                        </div>
                        <div>
                            <h3 class="font-extrabold text-slate-900 text-lg">BAU Research Institute</h3>
                            <span class="text-xs text-blue-700 font-semibold">Agronomy & Soil Sciences</span>
                        </div>
                    </div>
                    <p class="text-xs text-slate-600 leading-relaxed mb-4">
                        Collaborating on academic lab commercialization, saline-tolerant rice germplasm trials, and doctoral mentor residencies for deep-tech agri founders.
                    </p>
                </div>
                <div class="pt-4 border-t border-gray-100 flex items-center justify-between text-xs">
                    <span class="text-slate-500 font-medium">Founding Science Partner</span>
                    <a href="contact.php?category=Mentor" class="text-blue-800 font-bold hover:underline">Academic R&D &rarr;</a>
                </div>
            </div>

            <!-- Card 4: PRAN-RFL Agribusiness Group -->
            <div class="net-card bg-[#fbfdfc] rounded-3xl p-7 border border-gray-200/80 shadow-sm hover:shadow-xl hover:border-agro-300 transition duration-300 flex flex-col justify-between" data-category="corporate">
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <span class="px-2.5 py-1 bg-purple-100 text-purple-900 font-bold text-[11px] rounded-full">Corporate Partner</span>
                        <span class="text-xs text-slate-400"><i class="fa-solid fa-location-dot mr-1"></i> Nationwide</span>
                    </div>
                    <div class="flex items-center space-x-3 mb-4">
                        <div class="w-12 h-12 rounded-2xl bg-purple-700 text-white flex items-center justify-center text-xl font-black">
                            <i class="fa-solid fa-truck-fast"></i>
                        </div>
                        <div>
                            <h3 class="font-extrabold text-slate-900 text-lg">PRAN-RFL Agro</h3>
                            <span class="text-xs text-purple-700 font-semibold">Processing & Rural Procurement</span>
                        </div>
                    </div>
                    <p class="text-xs text-slate-600 leading-relaxed mb-4">
                        Providing our accelerated startups with contracted corporate off-take agreements, raw produce sourcing channels, and nationwide distribution integration.
                    </p>
                </div>
                <div class="pt-4 border-t border-gray-100 flex items-center justify-between text-xs">
                    <span class="text-slate-500 font-medium">Off-Take Offerer</span>
                    <a href="contact.php?category=Support%20Organisation" class="text-purple-800 font-bold hover:underline">Partner Terms &rarr;</a>
                </div>
            </div>

            <!-- Card 5: Wageningen University & Research -->
            <div class="net-card bg-[#fbfdfc] rounded-3xl p-7 border border-gray-200/80 shadow-sm hover:shadow-xl hover:border-agro-300 transition duration-300 flex flex-col justify-between" data-category="academic">
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <span class="px-2.5 py-1 bg-teal-100 text-teal-900 font-bold text-[11px] rounded-full">Global Knowledge</span>
                        <span class="text-xs text-slate-400"><i class="fa-solid fa-location-dot mr-1"></i> Netherlands</span>
                    </div>
                    <div class="flex items-center space-x-3 mb-4">
                        <div class="w-12 h-12 rounded-2xl bg-teal-700 text-white flex items-center justify-center text-xl font-black">
                            <i class="fa-solid fa-dna"></i>
                        </div>
                        <div>
                            <h3 class="font-extrabold text-slate-900 text-lg">Wageningen Agri Partners</h3>
                            <span class="text-xs text-teal-700 font-semibold">Controlled Environment & Post-Harvest</span>
                        </div>
                    </div>
                    <p class="text-xs text-slate-600 leading-relaxed mb-4">
                        Global knowledge exchange bringing Dutch protected greenhouse engineering and biological pest management to Bangladesh horticulture clusters.
                    </p>
                </div>
                <div class="pt-4 border-t border-gray-100 flex items-center justify-between text-xs">
                    <span class="text-slate-500 font-medium">Bilateral Knowledge Link</span>
                    <a href="contact.php?category=Support%20Organisation" class="text-teal-800 font-bold hover:underline">Exchange Info &rarr;</a>
                </div>
            </div>

            <!-- Card 6: Startup Bangladesh / ICT Division -->
            <div class="net-card bg-[#fbfdfc] rounded-3xl p-7 border border-gray-200/80 shadow-sm hover:shadow-xl hover:border-agro-300 transition duration-300 flex flex-col justify-between" data-category="impact">
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <span class="px-2.5 py-1 bg-red-100 text-red-900 font-bold text-[11px] rounded-full">Sovereign VC</span>
                        <span class="text-xs text-slate-400"><i class="fa-solid fa-location-dot mr-1"></i> Agargaon, Dhaka</span>
                    </div>
                    <div class="flex items-center space-x-3 mb-4">
                        <div class="w-12 h-12 rounded-2xl bg-red-700 text-white flex items-center justify-center text-xl font-black">
                            <i class="fa-solid fa-building-columns"></i>
                        </div>
                        <div>
                            <h3 class="font-extrabold text-slate-900 text-lg">Startup Bangladesh Ltd.</h3>
                            <span class="text-xs text-red-700 font-semibold">ICT Division Sovereign Fund</span>
                        </div>
                    </div>
                    <p class="text-xs text-slate-600 leading-relaxed mb-4">
                        Co-sponsoring catalytic seed rounds and providing regulatory sandboxing for digital financial inclusion and drone spray licensing in agriculture.
                    </p>
                </div>
                <div class="pt-4 border-t border-gray-100 flex items-center justify-between text-xs">
                    <span class="text-slate-500 font-medium">Policy & Co-Investment</span>
                    <a href="contact.php?category=Support%20Organisation" class="text-red-800 font-bold hover:underline">Government Grants &rarr;</a>
                </div>
            </div>

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
                    Headquarters housing the Hardware Prototyping Workshop, investor suites, administrative desk, and international Demo Day auditoriums.
                </p>
                <div class="pt-2 text-[11px] font-bold text-slate-500 flex items-center">
                    <i class="fa-solid fa-location-dot text-emerald-600 mr-1.5"></i> Gulshan-2, Dhaka
                </div>
            </div>

            <!-- Node 2: Bogura Station -->
            <div class="bg-white rounded-3xl p-6 border border-agro-200/80 shadow-sm space-y-4">
                <div class="w-12 h-12 rounded-2xl bg-emerald-700 text-white flex items-center justify-center text-xl">
                    <i class="fa-solid fa-tractor"></i>
                </div>
                <div>
                    <h4 class="font-extrabold text-lg text-slate-900">Bogura Alluvial Lab</h4>
                    <span class="text-xs text-emerald-700 font-semibold">Vegetable & Cold Chain Hub</span>
                </div>
                <p class="text-xs text-slate-600 leading-relaxed">
                    250-acre intensive vegetable testing corridor, pilot solar micro-cold rooms, and farmer participatory testing co-ops.
                </p>
                <div class="pt-2 text-[11px] font-bold text-slate-500 flex items-center">
                    <i class="fa-solid fa-location-dot text-emerald-600 mr-1.5"></i> Bogura District
                </div>
            </div>

            <!-- Node 3: Rajshahi Station -->
            <div class="bg-white rounded-3xl p-6 border border-agro-200/80 shadow-sm space-y-4">
                <div class="w-12 h-12 rounded-2xl bg-amber-600 text-white flex items-center justify-center text-xl">
                    <i class="fa-solid fa-sun"></i>
                </div>
                <div>
                    <h4 class="font-extrabold text-lg text-slate-900">Rajshahi Barind Node</h4>
                    <span class="text-xs text-amber-700 font-semibold">Drought & Precision Water</span>
                </div>
                <p class="text-xs text-slate-600 leading-relaxed">
                    Arid tract testbed specializing in subsurface telemetry, automated drip irrigation, and drought-resistant fruit tree orchards.
                </p>
                <div class="pt-2 text-[11px] font-bold text-slate-500 flex items-center">
                    <i class="fa-solid fa-location-dot text-emerald-600 mr-1.5"></i> Godagari, Rajshahi
                </div>
            </div>

            <!-- Node 4: Khulna Station -->
            <div class="bg-white rounded-3xl p-6 border border-agro-200/80 shadow-sm space-y-4">
                <div class="w-12 h-12 rounded-2xl bg-teal-700 text-white flex items-center justify-center text-xl">
                    <i class="fa-solid fa-water"></i>
                </div>
                <div>
                    <h4 class="font-extrabold text-lg text-slate-900">Khulna Saline Delta</h4>
                    <span class="text-xs text-teal-700 font-semibold">Coastal Resilience & Bio-Saline</span>
                </div>
                <p class="text-xs text-slate-600 leading-relaxed">
                    Pioneering salt-tolerant rice trials, brackish water bio-floc shrimp telemetry, and mangrove agroforestry pilot projects.
                </p>
                <div class="pt-2 text-[11px] font-bold text-slate-500 flex items-center">
                    <i class="fa-solid fa-location-dot text-emerald-600 mr-1.5"></i> Dumuria, Khulna
                </div>
            </div>

        </div>

    </div>
</section>

<!-- Upcoming Ecosystem Networking Events -->
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
            
            <div class="lg:col-span-5 space-y-5">
                <span class="text-xs font-bold uppercase tracking-wider text-agro-700">Events & Roundtables</span>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900">Where the Ecosystem Gathers</h2>
                <p class="text-sm text-slate-600 leading-relaxed">
                    We host monthly founder mixers, quarterly investor syndicates, and our flagship annual AgriTech convergence bringing together global leaders.
                </p>
                <div class="pt-2">
                    <a href="contact.php?category=Support%20Organisation" class="inline-flex items-center px-6 py-3 rounded-xl font-bold text-xs text-white bg-agro-800 hover:bg-agro-900 shadow-md transition">
                        <i class="fa-solid fa-calendar-check mr-2 text-emerald-300"></i> RSVP for Next Convergence
                    </a>
                </div>
            </div>

            <div class="lg:col-span-7 space-y-4">
                
                <div class="p-6 rounded-2xl bg-agro-50/80 border border-agro-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div class="flex items-start space-x-4">
                        <div class="w-14 h-14 rounded-2xl bg-agro-800 text-white flex flex-col items-center justify-center shrink-0">
                            <span class="text-[10px] uppercase font-bold tracking-wider text-emerald-300">MAY</span>
                            <span class="text-lg font-black leading-none">14</span>
                        </div>
                        <div>
                            <span class="text-[10px] font-bold text-emerald-700 uppercase tracking-widest">Monthly Mixer</span>
                            <h4 class="font-extrabold text-slate-900 text-base">Agri-Founder Breakfast: Cold Chain Innovations</h4>
                            <p class="text-xs text-slate-500 mt-1">Gulshan Hub • 35 Selected Founders & Cold Chain Logistics Operators</p>
                        </div>
                    </div>
                    <a href="contact.php" class="px-4 py-2 bg-white hover:bg-agro-100 text-agro-900 border border-agro-200 font-bold text-xs rounded-xl text-center transition">RSVP</a>
                </div>

                <div class="p-6 rounded-2xl bg-amber-50/80 border border-amber-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div class="flex items-start space-x-4">
                        <div class="w-14 h-14 rounded-2xl bg-amber-600 text-white flex flex-col items-center justify-center shrink-0">
                            <span class="text-[10px] uppercase font-bold tracking-wider text-amber-200">JUN</span>
                            <span class="text-lg font-black leading-none">28</span>
                        </div>
                        <div>
                            <span class="text-[10px] font-bold text-amber-800 uppercase tracking-widest">Investor Summit</span>
                            <h4 class="font-extrabold text-slate-900 text-base">South Asia Agri-Venture Syndicate Demo Day</h4>
                            <p class="text-xs text-slate-500 mt-1">Hybrid (Dhaka & Virtual) • 60+ Angel & VC Funds Reviewing Cohort 04</p>
                        </div>
                    </div>
                    <a href="contact.php?category=Investor" class="px-4 py-2 bg-white hover:bg-amber-100 text-amber-950 border border-amber-200 font-bold text-xs rounded-xl text-center transition">Register</a>
                </div>

                <div class="p-6 rounded-2xl bg-blue-50/80 border border-blue-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div class="flex items-start space-x-4">
                        <div class="w-14 h-14 rounded-2xl bg-blue-700 text-white flex flex-col items-center justify-center shrink-0">
                            <span class="text-[10px] uppercase font-bold tracking-wider text-blue-200">NOV</span>
                            <span class="text-lg font-black leading-none">05</span>
                        </div>
                        <div>
                            <span class="text-[10px] font-bold text-blue-800 uppercase tracking-widest">Annual Flagship</span>
                            <h4 class="font-extrabold text-slate-900 text-base">Bangladesh AgriTech Convergence 2026</h4>
                            <p class="text-xs text-slate-500 mt-1">Radisson Blu Dhaka • 800+ Attendees, Keynotes, Policy Workshops</p>
                        </div>
                    </div>
                    <a href="contact.php?category=Support%20Organisation" class="px-4 py-2 bg-white hover:bg-blue-100 text-blue-950 border border-blue-200 font-bold text-xs rounded-xl text-center transition">Get Pass</a>
                </div>

            </div>

        </div>

    </div>
</section>

<!-- Onboarding Pathway CTA Grid -->
<section class="py-20 bg-gradient-to-br from-slate-950 via-agro-950 to-slate-900 text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="text-center max-w-2xl mx-auto mb-14">
            <span class="text-xs font-bold uppercase tracking-widest text-emerald-400">Collaborate With Us</span>
            <h2 class="text-3xl sm:text-4xl font-extrabold text-white mt-2">How Will You Plug In?</h2>
            <p class="mt-2 text-sm text-slate-300">Choose your stakeholder pathway to join NextGen Farming Incubation Hub.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            
            <a href="contact.php?category=Entrepreneur" class="group bg-slate-900/80 hover:bg-agro-900/60 border border-slate-800 hover:border-emerald-500/50 rounded-3xl p-6 transition duration-300 flex flex-col justify-between">
                <div>
                    <div class="w-12 h-12 rounded-2xl bg-emerald-500/10 text-emerald-400 flex items-center justify-center text-xl mb-4 group-hover:scale-110 transition">
                        <i class="fa-solid fa-seedling"></i>
                    </div>
                    <h3 class="font-extrabold text-white text-lg mb-2">Agri-Founders</h3>
                    <p class="text-xs text-slate-400 leading-relaxed mb-4">
                        Access living farmlands, catalytic seed capital, and accelerated off-take contracts.
                    </p>
                </div>
                <span class="text-xs font-bold text-emerald-400 group-hover:underline flex items-center">
                    Apply for Incubation &rarr;
                </span>
            </a>

            <a href="contact.php?category=Investor" class="group bg-slate-900/80 hover:bg-agro-900/60 border border-slate-800 hover:border-emerald-500/50 rounded-3xl p-6 transition duration-300 flex flex-col justify-between">
                <div>
                    <div class="w-12 h-12 rounded-2xl bg-amber-500/10 text-amber-400 flex items-center justify-center text-xl mb-4 group-hover:scale-110 transition">
                        <i class="fa-solid fa-coins"></i>
                    </div>
                    <h3 class="font-extrabold text-white text-lg mb-2">Investors & VCs</h3>
                    <p class="text-xs text-slate-400 leading-relaxed mb-4">
                        Access pre-screened deal flow, diligence dossiers, and syndicated SAFE co-investments.
                    </p>
                </div>
                <span class="text-xs font-bold text-amber-400 group-hover:underline flex items-center">
                    Join Angel Syndicate &rarr;
                </span>
            </a>

            <a href="contact.php?category=Support%20Organisation" class="group bg-slate-900/80 hover:bg-agro-900/60 border border-slate-800 hover:border-emerald-500/50 rounded-3xl p-6 transition duration-300 flex flex-col justify-between">
                <div>
                    <div class="w-12 h-12 rounded-2xl bg-blue-500/10 text-blue-400 flex items-center justify-center text-xl mb-4 group-hover:scale-110 transition">
                        <i class="fa-solid fa-building"></i>
                    </div>
                    <h3 class="font-extrabold text-white text-lg mb-2">Corporates & Donors</h3>
                    <p class="text-xs text-slate-400 leading-relaxed mb-4">
                        Co-design innovation challenges, test new agri-inputs, and sponsor specialized cohorts.
                    </p>
                </div>
                <span class="text-xs font-bold text-blue-400 group-hover:underline flex items-center">
                    Explore Partnerships &rarr;
                </span>
            </a>

            <a href="contact.php?category=Mentor" class="group bg-slate-900/80 hover:bg-agro-900/60 border border-slate-800 hover:border-emerald-500/50 rounded-3xl p-6 transition duration-300 flex flex-col justify-between">
                <div>
                    <div class="w-12 h-12 rounded-2xl bg-purple-500/10 text-purple-400 flex items-center justify-center text-xl mb-4 group-hover:scale-110 transition">
                        <i class="fa-solid fa-graduation-cap"></i>
                    </div>
                    <h3 class="font-extrabold text-white text-lg mb-2">Domain Mentors</h3>
                    <p class="text-xs text-slate-400 leading-relaxed mb-4">
                        Guide high-potential founders in agronomy, climate resilience, and emerging market distribution.
                    </p>
                </div>
                <span class="text-xs font-bold text-purple-400 group-hover:underline flex items-center">
                    Join Advisory Council &rarr;
                </span>
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
