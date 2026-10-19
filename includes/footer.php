<?php
/**
 * NextGen Farming Incubation Hub - Shared Footer Component
 */
?>
    <!-- Global CTA Banner Section -->
    <section class="relative bg-gradient-to-br from-agro-950 via-agro-900 to-agro-800 text-white py-16 px-4 overflow-hidden mt-auto">
        <div class="absolute inset-0 bg-agro-pattern opacity-10"></div>
        <div class="absolute -right-20 -bottom-20 w-96 h-96 bg-emerald-500/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="max-w-7xl mx-auto relative z-10 text-center">
            <span class="inline-flex items-center px-4 py-1.5 rounded-full text-xs font-semibold bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 mb-4">
                <i class="fa-solid fa-leaf mr-2 text-emerald-400"></i> Empowering Emerging Agriculture
            </span>
            <h2 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold tracking-tight max-w-3xl mx-auto">
                Ready to Build the Future of Agriculture with Us?
            </h2>
            <p class="mt-4 text-base sm:text-lg text-emerald-100/90 max-w-2xl mx-auto leading-relaxed">
                Whether you are a founder scaling bio-sensors, an investor seeking frontier deal flow, or an institution driving rural transformation — join our ecosystem.
            </p>
            <div class="mt-8 flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="contact.php?category=Entrepreneur" class="w-full sm:w-auto px-8 py-3.5 text-base font-bold text-agro-950 bg-emerald-400 hover:bg-emerald-300 rounded-xl shadow-lg shadow-emerald-500/20 transition transform hover:-translate-y-0.5">
                    <i class="fa-solid fa-rocket mr-2"></i> Apply for Acceleration
                </a>
                <a href="contact.php?category=Investor" class="w-full sm:w-auto px-8 py-3.5 text-base font-bold text-white bg-white/10 hover:bg-white/20 border border-white/20 rounded-xl backdrop-blur transition">
                    <i class="fa-solid fa-handshake mr-2 text-emerald-300"></i> Partner as an Investor
                </a>
            </div>
        </div>
    </section>

    <!-- Main Footer -->
    <footer class="bg-slate-950 text-slate-300 pt-16 pb-12 border-t border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-10 lg:gap-8 pb-12 border-b border-slate-800">
                
                <!-- Col 1: Brand & Mission (2 cols on lg) -->
                <div class="lg:col-span-2 space-y-4">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-agro-600 to-agro-800 flex items-center justify-center text-white shadow-md">
                            <i class="fa-solid fa-leaf text-lg text-emerald-300"></i>
                        </div>
                        <div>
                            <span class="text-xl font-bold tracking-tight text-white">NextGen <span class="text-emerald-400">Farming</span></span>
                            <span class="block text-[10px] font-semibold text-slate-400 uppercase tracking-widest -mt-1">Incubation Hub</span>
                        </div>
                    </div>
                    <p class="text-sm text-slate-400 leading-relaxed max-w-sm">
                        Connecting Entrepreneur Ecosystem builders in Bangladesh and Emerging Markets. Scaling AgriTech innovation, smart cold-chain infrastructure, and rural climate resilience.
                    </p>
                    <div class="pt-2">
                        <h5 class="text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Operational Hub</h5>
                        <p class="text-xs text-slate-400 flex items-start space-x-2">
                            <i class="fa-solid fa-location-dot text-emerald-400 mt-0.5"></i>
                            <span><?= htmlspecialchars(getSiteSetting('contact_address', 'Level 7, Venture Tower, Gulshan-2, Dhaka 1212, Bangladesh')) ?></span>
                        </p>
                        <p class="text-xs text-slate-400 flex items-center space-x-2 mt-2">
                            <i class="fa-solid fa-envelope text-emerald-400"></i>
                            <span><?= htmlspecialchars(getSiteSetting('contact_email', 'connect@nextgenfarminghub.bd')) ?></span>
                        </p>
                    </div>
                </div>

                <!-- Col 2: Navigation Links -->
                <div>
                    <h4 class="text-sm font-bold text-white uppercase tracking-wider mb-4 border-l-2 border-emerald-500 pl-2">Navigation</h4>
                    <ul class="space-y-2.5 text-sm">
                        <li><a href="index.php" class="hover:text-emerald-400 transition">Home Overview</a></li>
                        <li><a href="about.php" class="hover:text-emerald-400 transition">About the Hub</a></li>
                        <li><a href="programs.php" class="hover:text-emerald-400 transition">Programs & Cohorts</a></li>
                        <li><a href="offerings.php" class="hover:text-emerald-400 transition">Core Offerings</a></li>
                        <li><a href="networking.php" class="hover:text-emerald-400 transition">Global Networking</a></li>
                        <li><a href="submissions.php" class="hover:text-emerald-400 transition flex items-center"><i class="fa-solid fa-database mr-1 text-xs text-emerald-400"></i> Submissions</a></li>
                        <li><a href="admin/index.php" class="text-amber-400 hover:text-amber-300 font-bold transition flex items-center"><i class="fa-solid fa-lock mr-1.5 text-xs"></i> Admin Dashboard</a></li>
                    </ul>
                </div>

                <!-- Col 3: Stakeholders -->
                <div>
                    <h4 class="text-sm font-bold text-white uppercase tracking-wider mb-4 border-l-2 border-emerald-500 pl-2">Ecosystem</h4>
                    <ul class="space-y-2.5 text-sm">
                        <li><a href="contact.php?category=Entrepreneur" class="hover:text-emerald-400 transition">Agri-Entrepreneurs</a></li>
                        <li><a href="contact.php?category=Investor" class="hover:text-emerald-400 transition">Angel & VC Investors</a></li>
                        <li><a href="contact.php?category=Support%20Organisation" class="hover:text-emerald-400 transition">Support Organisations</a></li>
                        <li><a href="contact.php?category=Mentor" class="hover:text-emerald-400 transition">Domain Mentors</a></li>
                        <li><a href="programs.php#curriculum" class="hover:text-emerald-400 transition">Incubation Framework</a></li>
                        <li><a href="about.php#impact" class="hover:text-emerald-400 transition">Impact & ESG Metrics</a></li>
                    </ul>
                </div>

                <!-- Col 4: Newsletter Dispatch -->
                <div>
                    <h4 class="text-sm font-bold text-white uppercase tracking-wider mb-4 border-l-2 border-emerald-500 pl-2">Agri-Dispatch</h4>
                    <p class="text-xs text-slate-400 mb-3 leading-relaxed">
                        Monthly briefing on frontier AgriTech funding rounds, agronomy innovations, and cohort calls.
                    </p>
                    <form id="newsletter-form" class="space-y-2">
                        <div class="relative">
                            <input type="email" required placeholder="Enter your email" class="w-full px-3.5 py-2.5 bg-slate-900 border border-slate-700 rounded-xl text-xs text-white placeholder-slate-500 focus:outline-none focus:border-emerald-500">
                        </div>
                        <button type="submit" class="w-full px-4 py-2.5 bg-agro-700 hover:bg-agro-600 text-white font-bold text-xs rounded-xl transition flex items-center justify-center">
                            <i class="fa-solid fa-paper-plane mr-2"></i> Subscribe
                        </button>
                    </form>
                </div>

            </div>

            <!-- Bottom Copyright & Socials -->
            <div class="pt-8 flex flex-col md:flex-row items-center justify-between text-xs text-slate-500 gap-4">
                <p>&copy; <?= date('Y') ?> NextGen Farming Incubation Hub. All rights reserved. Connecting Entrepreneur Ecosystem Builders.</p>
                <div class="flex items-center space-x-4">
                    <a href="https://linkedin.com" target="_blank" class="w-8 h-8 rounded-lg bg-slate-900 border border-slate-800 flex items-center justify-center text-slate-400 hover:text-emerald-400 hover:border-emerald-500 transition">
                        <i class="fa-brands fa-linkedin-in"></i>
                    </a>
                    <a href="https://twitter.com" target="_blank" class="w-8 h-8 rounded-lg bg-slate-900 border border-slate-800 flex items-center justify-center text-slate-400 hover:text-emerald-400 hover:border-emerald-500 transition">
                        <i class="fa-brands fa-x-twitter"></i>
                    </a>
                    <a href="https://facebook.com" target="_blank" class="w-8 h-8 rounded-lg bg-slate-900 border border-slate-800 flex items-center justify-center text-slate-400 hover:text-emerald-400 hover:border-emerald-500 transition">
                        <i class="fa-brands fa-facebook-f"></i>
                    </a>
                    <a href="https://youtube.com" target="_blank" class="w-8 h-8 rounded-lg bg-slate-900 border border-slate-800 flex items-center justify-center text-slate-400 hover:text-emerald-400 hover:border-emerald-500 transition">
                        <i class="fa-brands fa-youtube"></i>
                    </a>
                </div>
            </div>
        </div>
    </footer>

    <!-- jQuery 3.7.1 CDN -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>

    <!-- Main JavaScript Logic -->
    <script src="assets/js/main.js"></script>
</body>
</html>
