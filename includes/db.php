<?php
/**
 * NextGen Farming Incubation Hub - Database Connector & Data Access Layer
 * 
 * Provides robust PDO connection to MySQL with fallback simulation mode
 * to guarantee graceful execution across all development and production environments.
 */

// Load primary cPanel configuration file if present
if (file_exists(__DIR__ . '/../config.php')) {
    require_once __DIR__ . '/../config.php';
}

// Database Configuration Constants (Fallback defaults)
defined('DB_HOST') or define('DB_HOST', getenv('DB_HOST') ?: 'localhost');
defined('DB_PORT') or define('DB_PORT', getenv('DB_PORT') ?: '3306');
defined('DB_NAME') or define('DB_NAME', getenv('DB_NAME') ?: 'nextgenp_incubation');
defined('DB_USER') or define('DB_USER', getenv('DB_USER') ?: 'nextgenp_incubation');
defined('DB_PASS') or define('DB_PASS', getenv('DB_PASS') ?: 'KdfTxkeQF?h~u9L;');

/**
 * Returns canonical seed data for programs
 * 
 * @return array
 */
function getDefaultSeedPrograms() {
    return [
        [
            'id' => 1,
            'title' => 'AgriTech Ignite Accelerator',
            'stage_tag' => 'Pre-Seed Stage • 16-Week Structured Sprint',
            'duration' => '16 Weeks (Hybrid: Dhaka & Field Labs)',
            'funding_text' => '$35,000 – $50,000 Pre-Seed SAFE',
            'field_access' => 'Guaranteed Farmland Pilot in Bogura',
            'description' => 'Designed for early-stage founders with an MVP, prototype, or validated proof-of-concept. Focuses on field experimentation, smallholder user feedback, unit economics, and initial institutional capital readiness.',
            'icon' => 'fa-seedling',
            'badge_status' => 'Applications Open',
            'status' => 'Active',
            'sort_order' => 1
        ],
        [
            'id' => 2,
            'title' => 'HarvestScale Growth Program',
            'stage_tag' => 'Seed to Series A • 6-Month Scale Sprint',
            'duration' => '6 Months Bespoke Advisory',
            'funding_text' => 'Up to $250,000 Co-Investment Syndicate',
            'field_access' => 'Direct PRAN, ACI, BRAC Integration',
            'description' => 'Engineered for startups with proven product-market fit and commercial revenue. Focuses on multi-district distribution scaling, corporate off-take contracts, supply-chain automation, and international venture capital syndication.',
            'icon' => 'fa-chart-line',
            'badge_status' => 'Rolling Review',
            'status' => 'Active',
            'sort_order' => 2
        ],
        [
            'id' => 3,
            'title' => 'ClimateSmart Bio-Residency',
            'stage_tag' => 'Deep-Tech Science • 12-Month Academic & Lab Residency',
            'duration' => '12 Months Residency',
            'funding_text' => 'Research Commercialization Grant',
            'field_access' => 'BAU Genomics & Wageningen Peer Support',
            'description' => 'Translating frontier agronomy breakthroughs (saline-tolerant genetics, mycorrhizal bio-fertilizers, and microbial pest management) from university research labs into commercially viable startup spin-offs.',
            'icon' => 'fa-dna',
            'badge_status' => 'Selective Cohort',
            'status' => 'Active',
            'sort_order' => 3
        ],
        [
            'id' => 4,
            'title' => 'Women in AgriTech Fellowship',
            'stage_tag' => 'Inclusion & Leadership • 6-Month Catalytic Track',
            'duration' => '6-Month Leadership Track',
            'funding_text' => '$15,000 Equity-Free Innovation Grant',
            'field_access' => 'Direct Linkage to 50+ Women Co-ops',
            'description' => 'Empowering female founders, agribusiness operators, and rural cooperative organizers. Features dedicated non-dilutive catalytic grants, executive female mentor matching, and distributor network onboarding.',
            'icon' => 'fa-venus',
            'badge_status' => 'Grant Funded',
            'status' => 'Active',
            'sort_order' => 4
        ]
    ];
}

/**
 * Returns canonical seed data for core offerings
 * 
 * @return array
 */
function getDefaultSeedOfferings() {
    return [
        [
            'id' => 1,
            'title' => 'Empirical Living Farmland Testbeds',
            'category' => 'field',
            'badge' => 'Testbeds',
            'icon' => 'fa-wheat-awn',
            'description' => 'Over 500+ dedicated acreage in Bogura (vegetable clusters), Rajshahi (drought/Barind), and Khulna (coastal saline delta) equipped with real smallholder farmer co-ops.',
            'features' => "On-site IoT telemetry stations & weather radar\nMulti-spectral drone corridor approvals\nCohorts of 200+ participatory farmers",
            'button_text' => 'Request Farmland Access →',
            'button_url' => 'contact.php?category=Entrepreneur',
            'sort_order' => 1,
            'status' => 'Active'
        ],
        [
            'id' => 2,
            'title' => 'Catalytic Capital & Seed Syndication',
            'category' => 'capital',
            'badge' => 'Venture Capital',
            'icon' => 'fa-sack-dollar',
            'description' => 'Direct pre-seed SAFE capital injections and coordinated syndicate matching with top-tier angel investors and institutional venture capital across Asia & Europe.',
            'features' => "$35k - $50k Founder-Friendly SAFE terms\nDemo Day showcase to 60+ global funds\nNon-dilutive grant application matching",
            'button_text' => 'Explore Capital Syndicates →',
            'button_url' => 'contact.php?category=Investor',
            'sort_order' => 2,
            'status' => 'Active'
        ],
        [
            'id' => 3,
            'title' => 'Corporate Off-Take & Procurement',
            'category' => 'commercial',
            'badge' => 'Enterprise',
            'icon' => 'fa-truck-ramp-box',
            'description' => 'Pre-structured pilot integrations with Bangladesh’s premier agribusiness conglomerates (PRAN-RFL, ACI Agribusiness, Square Foods, BRAC Enterprises).',
            'features' => "Guaranteed pilot off-take commercial contracts\nNationwide retail & rural dealer network access\nStrategic corporate venture co-investment",
            'button_text' => 'Corporate Partnership Inquiry →',
            'button_url' => 'contact.php?category=Support%20Organisation',
            'sort_order' => 3,
            'status' => 'Active'
        ],
        [
            'id' => 4,
            'title' => 'Hardware & Agronomy Sensor Workshop',
            'category' => 'tech',
            'badge' => 'Deep-Tech',
            'icon' => 'fa-microchip',
            'description' => 'Dedicated lab facilities in Gulshan for rapid hardware iteration: SMD soldering, LoRaWAN gateways, 3D casing prototyping, and IP67 weather-proofing.',
            'features' => "Multi-sensor calibration benches (NPK, soil pH)\nLow-power micro-controller inventory\nCloud API telemetry test harness",
            'button_text' => 'Book Prototyping Bench →',
            'button_url' => 'contact.php?category=Entrepreneur',
            'sort_order' => 4,
            'status' => 'Active'
        ],
        [
            'id' => 5,
            'title' => 'Biosafety & Regulatory Sandbox',
            'category' => 'regulatory',
            'badge' => 'Compliance',
            'icon' => 'fa-stamp',
            'description' => 'Navigating agricultural approvals in South Asia can take years. Our institutional sandbox fast-tracks phytosanitary, seed registration, and bio-fertilizer certifications.',
            'features' => "Direct liaison with DAE, BARC & BARI\nStandardized bio-safety & toxicity validation\nIntellectual Property & patent filings clinic",
            'button_text' => 'Consult Regulatory Desk →',
            'button_url' => 'contact.php?category=Mentor',
            'sort_order' => 5,
            'status' => 'Active'
        ],
        [
            'id' => 6,
            'title' => 'Decentralized Solar Cold Storage',
            'category' => 'field',
            'badge' => 'Infrastructure',
            'icon' => 'fa-snowflake',
            'description' => 'Testing and scaling micro-cold room solutions at farm-gate collection centers to tackle the 30-35% post-harvest spoilage crisis across perishables.',
            'features' => "Solar thermal & phase-change cooling tests\nFirst-mile aggregation logistics algorithms\nExport-grade packaging & ripening chambers",
            'button_text' => 'Test Cold Storage Unit →',
            'button_url' => 'contact.php?category=Entrepreneur',
            'sort_order' => 6,
            'status' => 'Active'
        ]
    ];
}

/**
 * Returns canonical seed rows for the Stakeholder Capability Matrix
 * 
 * @return array
 */
function getDefaultOfferingsMatrixRows() {
    return [
        [
            'title'      => 'Farmland Living Labs Access',
            'icon'       => 'fa-solid fa-tractor text-agro-600',
            'col1'       => 'Up to 5 Acres',
            'col1_check' => 1,
            'col2'       => '25+ Commercial Acres',
            'col2_check' => 1,
            'col3'       => 'Dedicated Trial Plots',
            'col3_check' => 1,
            'col4'       => 'Bespoke Contract Plots',
            'col4_check' => 0,
        ],
        [
            'title'      => 'Direct Risk Capital',
            'icon'       => 'fa-solid fa-coins text-amber-600',
            'col1'       => '$35k - $50k SAFE',
            'col1_check' => 0,
            'col2'       => 'Up to $250k Syndicate',
            'col2_check' => 0,
            'col3'       => 'Innovation Grants',
            'col3_check' => 0,
            'col4'       => 'Co-Investment Rights',
            'col4_check' => 0,
        ],
        [
            'title'      => 'Sensor & Hardware Lab',
            'icon'       => 'fa-solid fa-microchip text-purple-600',
            'col1'       => 'Full Access',
            'col1_check' => 1,
            'col2'       => 'Full Access',
            'col2_check' => 1,
            'col3'       => 'BAU / Wageningen Lab',
            'col3_check' => 1,
            'col4'       => 'Collaborative R&D',
            'col4_check' => 0,
        ],
        [
            'title'      => 'Corporate Off-Take Linkage',
            'icon'       => 'fa-solid fa-handshake text-blue-600',
            'col1'       => 'Pilot Introductions',
            'col1_check' => 0,
            'col2'       => 'Guaranteed Off-Take',
            'col2_check' => 1,
            'col3'       => 'Commercial Licensing',
            'col3_check' => 0,
            'col4'       => 'Preferred Supplier',
            'col4_check' => 1,
        ],
        [
            'title'      => 'Regulatory Fast-Track',
            'icon'       => 'fa-solid fa-stamp text-emerald-600',
            'col1'       => 'Advisory Desk',
            'col1_check' => 1,
            'col2'       => 'Expedited DAE/BARC',
            'col2_check' => 1,
            'col3'       => 'Biosafety Protocols',
            'col3_check' => 1,
            'col4'       => 'Joint Policy Advocacy',
            'col4_check' => 0,
        ],
    ];
}

/**
 * Returns dynamic or seed rows for Stakeholder Capability Matrix
 * 
 * @return array
 */
function getOfferingsMatrixRows() {
    $json = getSiteSetting('offerings_matrix_json', '');
    if (!empty($json)) {
        $decoded = json_decode($json, true);
        if (is_array($decoded) && !empty($decoded)) {
            return $decoded;
        }
    }
    return getDefaultOfferingsMatrixRows();
}

/**
 * Returns canonical seed data for Leadership & Advisory Board
 * 
 * @return array
 */
function getDefaultAboutAdvisors() {
    return [
        [
            'id'       => 1,
            'name'     => 'Dr. Rafiqul Alam',
            'role'     => 'Executive Director & Co-Founder',
            'initials' => 'RA',
            'bio'      => 'Former Senior Advisor, FAO & Bangladesh Agricultural Research Council (BARC).',
            'image'    => '',
            'color'    => 'from-agro-700 to-agro-900 text-white',
        ],
        [
            'id'       => 2,
            'name'     => 'Syeda Zarin Tasnim',
            'role'     => 'Head of Venture Acceleration',
            'initials' => 'SZ',
            'bio'      => 'Ex-Venture Partner at regional Seed VC; led 20+ emerging market startup investments.',
            'image'    => '',
            'color'    => 'from-amber-600 to-amber-800 text-white',
        ],
        [
            'id'       => 3,
            'name'     => 'Mahmudul Karim, PhD',
            'role'     => 'Chief Agronomy Scientist',
            'initials' => 'MK',
            'bio'      => 'Professor of Soil Genomics, BAU Mymensingh; specialist in saline-tolerant cultivars.',
            'image'    => '',
            'color'    => 'from-blue-600 to-blue-800 text-white',
        ],
        [
            'id'       => 4,
            'name'     => 'Annelies de Lange',
            'role'     => 'Global Alliances & ESG Advisor',
            'initials' => 'AL',
            'bio'      => 'Wageningen Agri-Advisory; leading Dutch-Bangladesh sustainable greenhouse initiatives.',
            'image'    => '',
            'color'    => 'from-purple-600 to-purple-800 text-white',
        ],
    ];
}

/**
 * Returns dynamic or seed advisors for Leadership & Advisory Board
 * 
 * @return array
 */
function getAboutAdvisors() {
    $json = getSiteSetting('about_advisors_json', '');
    if (!empty($json)) {
        $decoded = json_decode($json, true);
        if (is_array($decoded) && !empty($decoded)) {
            return $decoded;
        }
    }
    return getDefaultAboutAdvisors();
}

/**
 * Returns canonical seed data for ecosystem directory partners
 * 
 * @return array
 */
function getDefaultSeedNetworkPartners() {
    return [
        [
            'id' => 1,
            'name' => 'KrishiSense IoT',
            'category' => 'founder',
            'badge' => 'Portfolio Startup',
            'location' => 'Bogura & Dhaka',
            'tagline' => 'Precision Soil & Irrigation Tech',
            'description' => 'Deployed solar IoT sensors across 4,200 acres in Bogura and Rajshahi, reducing smallholder nitrogen fertilizer overuse by 28% and water waste by 35%.',
            'icon' => 'fa-seedling',
            'meta_label' => 'Cohort 02 Alum',
            'link_text' => 'Connect →',
            'link_url' => 'contact.php?category=Entrepreneur',
            'sort_order' => 1,
            'status' => 'Active'
        ],
        [
            'id' => 2,
            'name' => 'Bengal Delta Capital',
            'category' => 'investor',
            'badge' => 'Syndicate Partner',
            'location' => 'Dhaka & Singapore',
            'tagline' => 'Seed & Series A Venture Fund',
            'description' => 'A $20M early-stage fund backing climate-smart agriculture, digital input distribution marketplaces, and cold chain logistics across Bangladesh.',
            'icon' => 'fa-landmark',
            'meta_label' => 'Co-Investment Lead',
            'link_text' => 'Syndicate Deals →',
            'link_url' => 'contact.php?category=Investor',
            'sort_order' => 2,
            'status' => 'Active'
        ],
        [
            'id' => 3,
            'name' => 'BAU Research Institute',
            'category' => 'academic',
            'badge' => 'Research Anchor',
            'location' => 'Mymensingh',
            'tagline' => 'Agronomy & Soil Sciences',
            'description' => 'Collaborating on academic lab commercialization, saline-tolerant rice germplasm trials, and doctoral mentor residencies for deep-tech agri founders.',
            'icon' => 'fa-microscope',
            'meta_label' => 'Founding Science Partner',
            'link_text' => 'Academic R&D →',
            'link_url' => 'contact.php?category=Mentor',
            'sort_order' => 3,
            'status' => 'Active'
        ],
        [
            'id' => 4,
            'name' => 'PRAN-RFL Agro',
            'category' => 'corporate',
            'badge' => 'Corporate Partner',
            'location' => 'Nationwide',
            'tagline' => 'Processing & Rural Procurement',
            'description' => 'Providing our accelerated startups with contracted corporate off-take agreements, raw produce sourcing channels, and nationwide distribution integration.',
            'icon' => 'fa-truck-fast',
            'meta_label' => 'Off-Take Offerer',
            'link_text' => 'Partner Terms →',
            'link_url' => 'contact.php?category=Support%20Organisation',
            'sort_order' => 4,
            'status' => 'Active'
        ],
        [
            'id' => 5,
            'name' => 'Wageningen Agri Partners',
            'category' => 'academic',
            'badge' => 'Global Knowledge',
            'location' => 'Netherlands',
            'tagline' => 'Controlled Environment & Post-Harvest',
            'description' => 'Global knowledge exchange bringing Dutch protected greenhouse engineering and biological pest management to Bangladesh horticulture clusters.',
            'icon' => 'fa-dna',
            'meta_label' => 'Bilateral Knowledge Link',
            'link_text' => 'Exchange Info →',
            'link_url' => 'contact.php?category=Support%20Organisation',
            'sort_order' => 5,
            'status' => 'Active'
        ],
        [
            'id' => 6,
            'name' => 'Startup Bangladesh Ltd.',
            'category' => 'impact',
            'badge' => 'Sovereign VC',
            'location' => 'Agargaon, Dhaka',
            'tagline' => 'ICT Division Sovereign Fund',
            'description' => 'Co-sponsoring catalytic seed rounds and providing regulatory sandboxing for digital financial inclusion and drone spray licensing in agriculture.',
            'icon' => 'fa-building-columns',
            'meta_label' => 'Policy & Co-Investment',
            'link_text' => 'Government Grants →',
            'link_url' => 'contact.php?category=Support%20Organisation',
            'sort_order' => 6,
            'status' => 'Active'
        ]
    ];
}

/**
 * Ensure all required database tables exist and are seeded
 * 
 * @param PDO $pdo
 */
function ensureDatabaseSchema(PDO $pdo) {
    static $schemaChecked = false;
    if ($schemaChecked) return;
    $schemaChecked = true;

    try {
        // 1. admin_users
        $pdo->exec("CREATE TABLE IF NOT EXISTS `admin_users` (
            `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            `username` VARCHAR(50) NOT NULL UNIQUE,
            `email` VARCHAR(100) NOT NULL UNIQUE,
            `password_hash` VARCHAR(255) NOT NULL,
            `full_name` VARCHAR(100) NOT NULL,
            `role` ENUM('Super Admin', 'Incubation Manager', 'Reviewer') DEFAULT 'Super Admin',
            `last_login` DATETIME DEFAULT NULL,
            `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

        // 2. site_settings
        $pdo->exec("CREATE TABLE IF NOT EXISTS `site_settings` (
            `setting_key` VARCHAR(100) PRIMARY KEY,
            `setting_value` TEXT NOT NULL,
            `setting_group` VARCHAR(50) DEFAULT 'general',
            `label` VARCHAR(150) DEFAULT NULL,
            `field_type` ENUM('text', 'textarea', 'number') DEFAULT 'text',
            `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

        // 3. programs
        $pdo->exec("CREATE TABLE IF NOT EXISTS `programs` (
            `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            `title` VARCHAR(150) NOT NULL,
            `stage_tag` VARCHAR(100) NOT NULL,
            `duration` VARCHAR(100) NOT NULL,
            `funding_text` VARCHAR(150) NOT NULL,
            `field_access` VARCHAR(150) DEFAULT NULL,
            `description` TEXT NOT NULL,
            `icon` VARCHAR(50) DEFAULT 'fa-seedling',
            `badge_status` VARCHAR(50) DEFAULT 'Applications Open',
            `status` ENUM('Active', 'Draft', 'Closed') DEFAULT 'Active',
            `sort_order` INT DEFAULT 0,
            `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            INDEX `idx_status` (`status`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

        // 4. hub_submissions
        $pdo->exec("CREATE TABLE IF NOT EXISTS `hub_submissions` (
            `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            `full_name` VARCHAR(150) NOT NULL,
            `email` VARCHAR(150) NOT NULL,
            `phone` VARCHAR(50) DEFAULT NULL,
            `organization` VARCHAR(150) DEFAULT NULL,
            `category` ENUM('Entrepreneur', 'Investor', 'Support Organisation', 'Mentor') NOT NULL,
            `sub_interest` VARCHAR(100) DEFAULT NULL,
            `pitch_url` VARCHAR(255) DEFAULT NULL,
            `message` TEXT NOT NULL,
            `ip_address` VARCHAR(45) DEFAULT NULL,
            `status` ENUM('New', 'Under Review', 'Shortlisted', 'Contacted', 'Archived') DEFAULT 'New',
            `admin_notes` TEXT DEFAULT NULL,
            `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            INDEX `idx_category` (`category`),
            INDEX `idx_status` (`status`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

        // 5. newsletter_subscribers
        $pdo->exec("CREATE TABLE IF NOT EXISTS `newsletter_subscribers` (
            `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            `email` VARCHAR(150) NOT NULL UNIQUE,
            `subscribed_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

        // 6. offerings
        $pdo->exec("CREATE TABLE IF NOT EXISTS `offerings` (
            `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            `title` VARCHAR(150) NOT NULL,
            `category` ENUM('field', 'capital', 'commercial', 'tech', 'regulatory') NOT NULL DEFAULT 'field',
            `badge` VARCHAR(50) DEFAULT 'Testbeds',
            `icon` VARCHAR(50) DEFAULT 'fa-wheat-awn',
            `description` TEXT NOT NULL,
            `features` TEXT DEFAULT NULL,
            `button_text` VARCHAR(100) DEFAULT 'Request Farmland Access →',
            `button_url` VARCHAR(255) DEFAULT 'contact.php?category=Entrepreneur',
            `sort_order` INT DEFAULT 0,
            `status` ENUM('Active', 'Draft') DEFAULT 'Active',
            `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            INDEX `idx_category` (`category`),
            INDEX `idx_status` (`status`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

        // 7. network_partners
        $pdo->exec("CREATE TABLE IF NOT EXISTS `network_partners` (
            `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            `name` VARCHAR(150) NOT NULL,
            `category` ENUM('founder', 'investor', 'academic', 'corporate', 'impact') NOT NULL DEFAULT 'founder',
            `badge` VARCHAR(50) DEFAULT 'Portfolio Startup',
            `location` VARCHAR(100) DEFAULT 'Dhaka',
            `tagline` VARCHAR(150) DEFAULT NULL,
            `description` TEXT NOT NULL,
            `icon` VARCHAR(50) DEFAULT 'fa-seedling',
            `meta_label` VARCHAR(100) DEFAULT 'Cohort Alum',
            `link_text` VARCHAR(100) DEFAULT 'Connect →',
            `link_url` VARCHAR(255) DEFAULT 'contact.php',
            `sort_order` INT DEFAULT 0,
            `status` ENUM('Active', 'Draft') DEFAULT 'Active',
            `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            INDEX `idx_category` (`category`),
            INDEX `idx_status` (`status`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

        // Seed default admin if empty
        $cnt = (int)$pdo->query("SELECT COUNT(*) FROM `admin_users`")->fetchColumn();
        if ($cnt === 0) {
            $hash = password_hash('admin123', PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO `admin_users` (username, email, password_hash, full_name, role) VALUES ('admin', 'admin@nextgenfarminghub.bd', :h, 'Incubation Director', 'Super Admin')");
            $stmt->execute([':h' => $hash]);
        }

        // Seed default programs if empty
        $pCount = (int)$pdo->query("SELECT COUNT(*) FROM `programs`")->fetchColumn();
        if ($pCount === 0) {
            $defaults = getDefaultSeedPrograms();
            $stmt = $pdo->prepare("INSERT INTO `programs` (title, stage_tag, duration, funding_text, field_access, description, icon, badge_status, status, sort_order) VALUES (:title, :stage_tag, :duration, :funding_text, :field_access, :description, :icon, :badge_status, :status, :sort_order)");
            foreach ($defaults as $dp) {
                $stmt->execute([
                    ':title'        => $dp['title'],
                    ':stage_tag'    => $dp['stage_tag'],
                    ':duration'     => $dp['duration'],
                    ':funding_text' => $dp['funding_text'],
                    ':field_access' => $dp['field_access'],
                    ':description'  => $dp['description'],
                    ':icon'         => $dp['icon'],
                    ':badge_status' => $dp['badge_status'],
                    ':status'       => $dp['status'],
                    ':sort_order'   => $dp['sort_order'],
                ]);
            }
        }

        // Seed default offerings if empty
        $offCount = (int)$pdo->query("SELECT COUNT(*) FROM `offerings`")->fetchColumn();
        if ($offCount === 0) {
            $defOfferings = getDefaultSeedOfferings();
            $stmt = $pdo->prepare("INSERT INTO `offerings` (title, category, badge, icon, description, features, button_text, button_url, sort_order, status) VALUES (:title, :category, :badge, :icon, :description, :features, :button_text, :button_url, :sort_order, :status)");
            foreach ($defOfferings as $do) {
                $stmt->execute([
                    ':title'       => $do['title'],
                    ':category'    => $do['category'],
                    ':badge'       => $do['badge'],
                    ':icon'        => $do['icon'],
                    ':description' => $do['description'],
                    ':features'    => $do['features'],
                    ':button_text' => $do['button_text'],
                    ':button_url'  => $do['button_url'],
                    ':sort_order'  => $do['sort_order'],
                    ':status'      => $do['status'],
                ]);
            }
        }

        // Seed default network partners if empty
        $partCount = (int)$pdo->query("SELECT COUNT(*) FROM `network_partners`")->fetchColumn();
        if ($partCount === 0) {
            $defPartners = getDefaultSeedNetworkPartners();
            $stmt = $pdo->prepare("INSERT INTO `network_partners` (name, category, badge, location, tagline, description, icon, meta_label, link_text, link_url, sort_order, status) VALUES (:name, :category, :badge, :location, :tagline, :description, :icon, :meta_label, :link_text, :link_url, :sort_order, :status)");
            foreach ($defPartners as $dn) {
                $stmt->execute([
                    ':name'        => $dn['name'],
                    ':category'    => $dn['category'],
                    ':badge'       => $dn['badge'],
                    ':location'    => $dn['location'],
                    ':tagline'     => $dn['tagline'],
                    ':description' => $dn['description'],
                    ':icon'        => $dn['icon'],
                    ':meta_label'  => $dn['meta_label'],
                    ':link_text'   => $dn['link_text'],
                    ':link_url'    => $dn['link_url'],
                    ':sort_order'  => $dn['sort_order'],
                    ':status'      => $dn['status'],
                ]);
            }
        }
    } catch (Throwable $e) {
        error_log("Schema auto-init notice: " . $e->getMessage());
    }
}

/**
 * Get singleton PDO Database connection instance
 * Connects with configured credentials or standard local fallback
 * 
 * @return PDO|null
 */
function getDbConnection() {
    static $pdo = null;
    static $attempted = false;
    
    if ($pdo !== null) {
        return $pdo;
    }

    if ($attempted) {
        return null;
    }

    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
        PDO::ATTR_TIMEOUT            => 2,
    ];

    // Attempt 1: Configured credentials
    $dsn = "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=utf8mb4";
    try {
        $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        ensureDatabaseSchema($pdo);
        return $pdo;
    } catch (PDOException $e) {
        error_log("Primary DB connection attempt failed (" . DB_HOST . "): " . $e->getMessage());
    }

    // Attempt 2: Local XAMPP/WAMP fallback if running on localhost
    if (in_array(DB_HOST, ['localhost', '127.0.0.1'])) {
        $localCreds = [
            ['u' => 'root', 'p' => ''],
            ['u' => 'root', 'p' => 'root']
        ];
        foreach ($localCreds as $c) {
            try {
                $serverDsn = "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";charset=utf8mb4";
                $tmpPdo = new PDO($serverDsn, $c['u'], $c['p'], $options);
                $tmpPdo->exec("CREATE DATABASE IF NOT EXISTS `" . DB_NAME . "` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
                $tmpPdo->exec("USE `" . DB_NAME . "`");
                $pdo = $tmpPdo;
                ensureDatabaseSchema($pdo);
                return $pdo;
            } catch (PDOException $e2) {
                // Try next credential
            }
        }
    }

    $attempted = true;
    return null;
}

/**
 * Checks whether MySQL connection is currently live
 * 
 * @return bool
 */
function isDbConnected() {
    return getDbConnection() !== null;
}

/**
 * Insert new application/contact lead into `hub_submissions`
 * 
 * @param array $data
 * @return array ['success' => bool, 'id' => int|null, 'message' => string, 'mode' => 'db'|'fallback']
 */
function saveHubSubmission(array $data) {
    $fullName     = trim($data['full_name'] ?? '');
    $email        = trim($data['email'] ?? '');
    $phone        = trim($data['phone'] ?? '');
    $organization = trim($data['organization'] ?? '');
    $category     = trim($data['category'] ?? '');
    $subInterest  = trim($data['sub_interest'] ?? '');
    $pitchUrl     = trim($data['pitch_url'] ?? '');
    $message      = trim($data['message'] ?? '');
    $ipAddress    = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';

    // Validate valid enum categories
    $validCategories = ['Entrepreneur', 'Investor', 'Support Organisation', 'Mentor'];
    if (!in_array($category, $validCategories)) {
        return [
            'success' => false,
            'message' => 'Invalid stakeholder category selected.'
        ];
    }

    if (empty($fullName) || empty($email) || empty($message)) {
        return [
            'success' => false,
            'message' => 'Please fill in all mandatory fields (Name, Email, Message).'
        ];
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return [
            'success' => false,
            'message' => 'Please provide a valid email address.'
        ];
    }

    $pdo = getDbConnection();

    if ($pdo !== null) {
        try {
            $sql = "INSERT INTO hub_submissions 
                    (full_name, email, phone, organization, category, sub_interest, pitch_url, message, ip_address, status, created_at)
                    VALUES (:full_name, :email, :phone, :organization, :category, :sub_interest, :pitch_url, :message, :ip_address, 'New', NOW())";
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':full_name'     => $fullName,
                ':email'        => $email,
                ':phone'        => $phone,
                ':organization' => $organization,
                ':category'     => $category,
                ':sub_interest'  => $subInterest,
                ':pitch_url'     => $pitchUrl,
                ':message'      => $message,
                ':ip_address'    => $ipAddress,
            ]);

            return [
                'success' => true,
                'id'      => (int)$pdo->lastInsertId(),
                'message' => 'Thank you! Your submission has been securely recorded into NextGen Farming Hub.',
                'mode'    => 'db'
            ];
        } catch (PDOException $e) {
            error_log("Failed to insert submission: " . $e->getMessage());
        }
    }

    // High-Resilience Fallback: If local MySQL is not started yet, store in JSON mock storage
    $mockFile = __DIR__ . '/../assets/data_fallback.json';
    $mockDir = dirname($mockFile);
    if (!is_dir($mockDir)) {
        @mkdir($mockDir, 0777, true);
    }

    $submissions = [];
    if (file_exists($mockFile)) {
        $json = @file_get_contents($mockFile);
        $submissions = json_decode($json, true) ?: [];
    }

    $newId = count($submissions) + 1;
    $record = [
        'id'           => $newId,
        'full_name'    => htmlspecialchars($fullName),
        'email'        => htmlspecialchars($email),
        'phone'        => htmlspecialchars($phone),
        'organization' => htmlspecialchars($organization),
        'category'     => $category,
        'sub_interest' => htmlspecialchars($subInterest),
        'pitch_url'    => htmlspecialchars($pitchUrl),
        'message'      => htmlspecialchars($message),
        'ip_address'   => $ipAddress,
        'status'       => 'New',
        'created_at'   => date('Y-m-d H:i:s'),
    ];

    array_unshift($submissions, $record);
    @file_put_contents($mockFile, json_encode($submissions, JSON_PRETTY_PRINT));

    return [
        'success' => true,
        'id'      => $newId,
        'message' => 'Thank you! Your submission has been logged into NextGen Farming Incubation Hub.',
        'mode'    => 'fallback'
    ];
}

/**
 * Fetch list of recent submissions (from MySQL or fallback cache)
 * 
 * @param int $limit
 * @param string|null $category
 * @return array
 */
function getHubSubmissions($limit = 50, $category = null) {
    $pdo = getDbConnection();

    if ($pdo !== null) {
        try {
            $sql = "SELECT * FROM hub_submissions";
            $params = [];
            if ($category && in_array($category, ['Entrepreneur', 'Investor', 'Support Organisation', 'Mentor'])) {
                $sql .= " WHERE category = :category";
                $params[':category'] = $category;
            }
            $sql .= " ORDER BY created_at DESC LIMIT " . (int)$limit;
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Failed to query submissions: " . $e->getMessage());
        }
    }

    // Read from fallback storage if MySQL is offline
    $mockFile = __DIR__ . '/../assets/data_fallback.json';
    if (file_exists($mockFile)) {
        $json = @file_get_contents($mockFile);
        $data = json_decode($json, true) ?: [];
        if ($category) {
            $data = array_values(array_filter($data, function($item) use ($category) {
                return $item['category'] === $category;
            }));
        }
        return array_slice($data, 0, $limit);
    }

    // Default Seed Data Showcase if entirely new
    return [
        [
            'id' => 1,
            'full_name' => 'Tanvir Ahmed',
            'email' => 'tanvir.ahmed@krishisense.bd',
            'phone' => '+880 1712-345678',
            'organization' => 'KrishiSense IoT Ltd.',
            'category' => 'Entrepreneur',
            'sub_interest' => 'AgriTech Ignite Accelerator',
            'pitch_url' => 'https://pitch.krishisense.bd/deck.pdf',
            'message' => 'Developing solar-powered soil moisture and automated drip irrigation sensors for rice and potato farmers in Bogura & Rajshahi.',
            'status' => 'Under Review',
            'created_at' => date('Y-m-d H:i:s', strtotime('-1 day'))
        ],
        [
            'id' => 2,
            'full_name' => 'Farhana Rahman',
            'email' => 'f.rahman@bengalventures.vc',
            'phone' => '+880 1819-876543',
            'organization' => 'Bengal Delta Capital',
            'category' => 'Investor',
            'sub_interest' => 'Syndicate Deal Flow',
            'pitch_url' => 'https://bengalventures.vc',
            'message' => 'Seeking syndicate co-investment in Series Seed AgriTech ventures with strong rural distribution and climate resilience.',
            'status' => 'Shortlisted',
            'created_at' => date('Y-m-d H:i:s', strtotime('-2 days'))
        ],
        [
            'id' => 3,
            'full_name' => 'Dr. Michael Jansen',
            'email' => 'm.jansen@agroinnovate-nl.org',
            'phone' => '+31 20 555 0192',
            'organization' => 'Wageningen Agro Partners',
            'category' => 'Support Organisation',
            'sub_interest' => 'Institutional Partnership',
            'pitch_url' => 'https://agroinnovate-nl.org',
            'message' => 'Collaborating on Dutch greenhouse precision tech trials across Mymensingh horticulture clusters.',
            'status' => 'Contacted',
            'created_at' => date('Y-m-d H:i:s', strtotime('-3 days'))
        ],
        [
            'id' => 4,
            'full_name' => 'Prof. A. S. M. Kamal',
            'email' => 'asm.kamal@bau.edu.bd',
            'phone' => '+880 1911-223344',
            'organization' => 'Bangladesh Agricultural University',
            'category' => 'Mentor',
            'sub_interest' => 'Ag-Bio Mentor Residency',
            'pitch_url' => 'https://bau.edu.bd/faculty/kamal',
            'message' => 'Offering 20+ years of domain expertise in drought-resistant seed genomics and organic bio-fertilizer scaling.',
            'status' => 'New',
            'created_at' => date('Y-m-d H:i:s', strtotime('-4 hours'))
        ]
    ];
}

/**
 * ============================================================
 * Dynamic CMS & Site Settings Functions
 * ============================================================
 */

/**
 * Default fallback site settings if database is empty or offline
 */
function getDefaultSiteSettings() {
    return [
        'site_name'              => 'NextGen Farming Incubation Hub',
        'hero_tagline'           => 'AgriTech Innovation & Venture Studio',
        'hero_title_main'        => 'NextGen Farming',
        'hero_title_highlight'   => 'Incubation Hub',
        'hero_subtitle'          => 'Connecting Entrepreneur Ecosystem builders in Bangladesh and Emerging Markets.',
        'hero_description'       => 'We nurture transformative agricultural startups, unlock catalytic capital, and connect cross-border knowledge to build resilient, high-yield, and climate-adaptive food systems.',
        'hero_banner_text'       => 'Applications Open for AgriTech Ignite Accelerator',
        'hero_banner_badge'      => 'New Call',
        'hero_banner_link'       => 'contact.php?category=Entrepreneur',
        'metric_members'         => '5200',
        'metric_members_suffix'  => '+',
        'metric_members_desc'    => 'A vibrant network of over 5,200 passionate smallholder innovators, agronomy scientists, youth climate advocates, university researchers, and rural co-op leaders spanning all 64 districts of Bangladesh and key regional farming corridors.',
        'metric_ventures'        => '128',
        'metric_ventures_suffix' => '+',
        'metric_ventures_desc'   => 'Over 128 high-growth agricultural ventures accelerated across post-harvest cold storage, biotech input formulations, sensor-driven irrigation, and digital farm-to-table supply chains, mobilizing more than $28.5M in cumulative follow-on investment.',
        'metric_capital'         => '$28.5M+',
        'metric_organizers'      => '48',
        'metric_organizers_suffix'=> '+',
        'metric_organizers_desc' => 'A robust coalition of 48+ co-design partners, including premier agricultural universities, multilateral development agencies, impact venture syndicates, and corporate agribusiness giants collaborating to execute world-class incubation cohorts.',
        'contact_email'          => 'connect@nextgenfarminghub.bd',
        'contact_phone'          => '+880 2 988-5544 / +880 1711-002233',
        'contact_address'        => 'Level 7, Venture Tower, Gulshan-2, Dhaka 1212, Bangladesh',
        'admissions_email'       => 'accelerate@nextgenfarminghub.bd',
        'investors_email'        => 'investors@nextgenfarminghub.bd',

        // Programs & Cohorts Page Dynamic Content
        'programs_badge'         => 'Structured Incubation Tracks',
        'programs_title'         => 'Programs & Founder Residencies',
        'programs_desc'          => 'From early lab prototypes to multi-district commercial scale, our specialized tracks provide catalytic risk capital, empirical farmland living labs, and global venture guidance.',
        'curriculum_phase1_weeks'=> 'Weeks 1 - 4',
        'curriculum_phase1_title'=> 'Phase 1: Problem & Agronomy Grounding',
        'curriculum_phase1_desc' => 'Farmer stakeholder immersion, soil & climate mapping, customer pain validation, and technical architecture review.',
        'curriculum_phase2_weeks'=> 'Weeks 5 - 8',
        'curriculum_phase2_title'=> 'Phase 2: Living Lab Farmland Trials',
        'curriculum_phase2_desc' => 'Deploying sensors, micro-drip prototypes, or biological formulations on 150-hectare pilot fields with cooperative farmers.',
        'curriculum_phase3_weeks'=> 'Weeks 9 - 12',
        'curriculum_phase3_title'=> 'Phase 3: Unit Economics & Distribution',
        'curriculum_phase3_desc' => 'Refining CAC/LTV, pricing models suited for smallholders, cold-chain logistics agreements, and supply partner contracts.',
        'curriculum_phase4_weeks'=> 'Weeks 13 - 16',
        'curriculum_phase4_title'=> 'Phase 4: Demo Day & Capital Syndicate',
        'curriculum_phase4_desc' => 'Pitching live to 40+ regional and international VC funds, angel syndicates, and corporate venture capital executives.',
        'faq1_q'                 => 'What stage of startup is eligible for the AgriTech Ignite Accelerator?',
        'faq1_a'                 => 'We accept early-stage teams with at least a functioning prototype, biological bench trial, software MVP, or deep-rooted domain insight. Full commercial revenue is not mandatory at entry; our goal is to help you achieve your first paid farmer or B2B enterprise pilot during the cohort.',
        'faq2_q'                 => 'Do founders need to relocate to Dhaka during the program?',
        'faq2_a'                 => 'No. Our program is hybrid. Core masterclasses and pitch clinics occur virtually, while intensive pilot trials take place in regional agricultural corridors (e.g. Bogura, Dinajpur, and Mymensingh) where our living testbeds and cooperative partner hubs are situated.',
        'faq3_q'                 => 'Can international startups outside Bangladesh apply?',
        'faq3_a'                 => 'Yes. We actively encourage startups from South Asia, Southeast Asia, the Netherlands, and across emerging markets who wish to pilot their technology or establish a joint venture in Bangladesh\'s high-density agricultural ecosystem.',
        'faq4_q'                 => 'How does the hub protect our proprietary intellectual property (IP)?',
        'faq4_a'                 => 'All mentor engagements, advisory reviews, and living lab testing protocols are conducted under strict mutual non-disclosure agreements (NDAs). Founders retain 100% ownership of their IP, hardware designs, algorithms, and biological formulas.',

        // Core Offerings Page Dynamic Content
        'offerings_badge'        => 'Venture Studio & Technical Infrastructure',
        'offerings_title_main'   => 'Full-Stack Capabilities Built for',
        'offerings_title_highlight' => 'Agricultural Transformation',
        'offerings_desc'         => 'Generalist tech accelerators cannot solve the physical complexities of biology, volatile weather, rural logistics, and fragmented middleman chains. NextGen Farming Hub provides purpose-built technical, empirical, and commercial infrastructure tailored for emerging deltaic markets.',
        'offering_stat1_num'     => '500+ Farmland Acres',
        'offering_stat1_desc'    => 'Active living labs in Bogura, Khulna & Rajshahi.',
        'offering_stat2_num'     => '48+ Off-Take Channels',
        'offering_stat2_desc'    => 'Direct procurement bridges with leading agribusinesses.',
        'offering_stat3_num'     => '$28.5M+ Syndicated Capital',
        'offering_stat3_desc'    => 'Pre-seed, angel syndicate & international VC co-investment.',
        'offering_stat4_num'     => '14-Day Regulatory Sandbox',
        'offering_stat4_desc'    => 'Streamlined DAE, seed certification, and bio-input clearance.',

        // Core Offerings - Stakeholder Capability Matrix Section
        'offerings_matrix_badge'    => 'Tailored For Every Stage',
        'offerings_matrix_title'    => 'Stakeholder Capability Matrix',
        'offerings_matrix_subtitle' => 'How founders, research spin-offs, corporate partners, and institutional investors engage with NextGen Farming Hub’s core infrastructure.',
        'offerings_matrix_col0'     => 'Capabilities & Resources',
        'offerings_matrix_col1'     => 'Pre-Seed Startups',
        'offerings_matrix_col2'     => 'Scaling Growth Ventures',
        'offerings_matrix_col3'     => 'Academic / Science Spin-offs',
        'offerings_matrix_col4'     => 'Corporate Agribusiness',
        'offerings_matrix_json'     => '',

        // Core Offerings - FAQ Section
        'offering_faq1_q'           => 'Who retains the Intellectual Property (IP) developed using Hub testbeds?',
        'offering_faq1_a'           => '100% of the Intellectual Property belongs to the startup founder or spinning-off institution. NextGen Farming Hub takes no claim over proprietary algorithms, hardware schematics, seed genomics, or formulations.',
        'offering_faq2_q'           => 'How quickly can our team begin field trials on the living farmland testbeds?',
        'offering_faq2_a'           => 'Upon cohort admission or technical review approval, teams can begin sensor deployment and soil baseline sampling within 10 to 14 business days across our Bogura or Rajshahi living field stations.',
        'offering_faq3_q'           => 'Can overseas/international AgriTech startups access NextGen Farming Hub offerings?',
        'offering_faq3_a'           => 'Yes! International ventures looking to localize agronomic technologies into Bangladesh, South Asia, or frontier deltaic environments are welcomed through our Cross-Border Landing Track, which includes regulatory clearance, local pilot facilitation, and corporate introductions.',

        // Networking Page Dynamic Content
        'networking_badge'       => 'Connecting Entrepreneur Ecosystem Builders',
        'networking_title_main'  => 'A Connected Web of',
        'networking_title_highlight' => 'Founders, Investors & Scientists',
        'networking_desc'        => 'Agriculture cannot be transformed in isolation. NextGen Farming Incubation Hub weaves together Bangladesh’s rural innovators, leading agronomy universities, regional venture syndicates, and global emerging market networks.',
        'network_stat1_num'      => '5,200+',
        'network_stat1_label'    => 'Network Members',
        'network_stat1_desc'     => 'Across all 64 agricultural districts',
        'network_stat2_num'      => '128+',
        'network_stat2_label'    => 'Ventures Accelerated',
        'network_stat2_desc'     => 'High-impact AgriTech solutions',
        'network_stat3_num'      => '48+',
        'network_stat3_label'    => 'Institutional Partners',
        'network_stat3_desc'     => 'Universities, corporates & donors',
        'network_stat4_num'      => '18',
        'network_stat4_label'    => 'Global Corridors',
        'network_stat4_desc'     => 'South Asia, Netherlands & Africa',

        // About Us Page Dynamic Content
        'about_hero_badge'           => 'Our Mission & Purpose',
        'about_hero_title_main'      => 'Catalyzing Frontier AgriTech Across',
        'about_hero_title_highlight' => 'Bangladesh & Emerging Markets',
        'about_hero_desc'            => 'NextGen Farming Incubation Hub was founded to transform fragmented agricultural value chains into resilient, high-productivity, and venture-fundable economic engines.',

        'about_context_badge'        => 'The Problem & The Deltaic Promise',
        'about_context_title'        => 'Why Bangladesh is the World’s Most Vital Agricultural Sandbox',
        'about_context_p1'           => 'With over 16.5 million smallholder farming families cultivating fertile yet climate-vulnerable alluvial soil, Bangladesh represents both an acute epicenter of climate risk and the ultimate proving ground for scalable AgriTech.',
        'about_context_p2'           => 'Technologies proven in the saline coastal zones of Khulna, the drought-prone barind tracts of Rajshahi, and the vegetable hubs of Bogura possess the immediate resilience needed across South Asia, Southeast Asia, and Sub-Saharan Africa.',

        'about_bottle1_title'        => 'Post-Harvest Loss',
        'about_bottle1_desc'         => 'Mitigating 30-35% perishable crop decay through modular solar micro-cold rooms.',
        'about_bottle1_icon'         => 'fa-temperature-arrow-down',
        'about_bottle2_title'        => 'Digital Supply Chains',
        'about_bottle2_desc'         => 'Disintermediating 5-tier middleman cartels via transparent mobile auction platforms.',
        'about_bottle2_icon'         => 'fa-mobile-screen',
        'about_bottle3_title'        => 'Precision Agronomy',
        'about_bottle3_desc'         => 'Affordable IoT sensors & satellite NDVI imagery for targeted nitrogen and water delivery.',
        'about_bottle3_icon'         => 'fa-satellite-dish',
        'about_bottle4_title'        => 'Agri-Fintech & Credit',
        'about_bottle4_desc'         => 'Alternative algorithmic credit scoring unlocking working capital for unbanked tenant farmers.',
        'about_bottle4_icon'         => 'fa-credit-card',

        'about_flywheel_badge'       => 'Our Operating Architecture',
        'about_flywheel_title'       => 'The NextGen Incubation Flywheel',
        'about_flywheel1_title'      => 'Grassroots Scouting & Problem Discovery',
        'about_flywheel1_desc'       => 'We work with agricultural universities and rural extension officers to identify pain-points directly from farm gates.',
        'about_flywheel2_title'      => 'Living Farmland Validation',
        'about_flywheel2_desc'       => 'Startups test prototypes on our 150+ hectares of living testbeds before scaling commercially.',
        'about_flywheel3_title'      => 'Blended Capital Syndication',
        'about_flywheel3_desc'       => 'We pair philanthropic grant de-risking with commercial equity from angel syndicates and venture funds.',
        'about_flywheel4_title'      => 'Cross-Border Market Expansion',
        'about_flywheel4_desc'       => 'Validated ventures receive commercial bridge advisory to expand across India, Nepal, Kenya, and Vietnam.',

        'about_pillars_badge'        => 'Core Foundations',
        'about_pillars_title'        => 'Our Four Strategic Pillars',
        'about_pillars_subtitle'     => 'How we engineer lasting agricultural transformation through discipline and science.',
        'about_pillar1_title'        => 'Deep-Tech & Agronomy Fusion',
        'about_pillar1_desc'         => 'Fusing computational modeling, soil chemistry, and drone-based multispectral sensing with traditional agronomic practices.',
        'about_pillar1_icon'         => 'fa-dna',
        'about_pillar2_title'        => 'Catalytic Blended Finance',
        'about_pillar2_desc'         => 'Structuring non-dilutive innovation grants alongside equity venture capital to de-risk high-capex hardware and biotech ventures.',
        'about_pillar2_icon'         => 'fa-sack-dollar',
        'about_pillar3_title'        => 'Empirical Farmland Testbeds',
        'about_pillar3_desc'         => 'Real-world trials conducted side-by-side with commercial farmer cooperatives across diverse climatic micro-zones.',
        'about_pillar3_icon'         => 'fa-wheat-awn-circle-exclamation',
        'about_pillar4_title'        => 'South-South Knowledge Exchange',
        'about_pillar4_desc'         => 'Facilitating technology and policy linkages between South Asia, the Mekong Delta, and East African agricultural corridors.',
        'about_pillar4_icon'         => 'fa-globe',

        'about_team_badge'           => 'Governance & Operators',
        'about_team_title'           => 'Leadership & Advisory Board',
        'about_team_subtitle'        => 'Combining decades of venture capital, ag-science, and rural market scaling.',
        'about_leader1_name'         => 'Dr. Rafiqul Alam',
        'about_leader1_role'         => 'Executive Director & Co-Founder',
        'about_leader1_initials'     => 'RA',
        'about_leader1_bio'          => 'Former Senior Advisor, FAO & Bangladesh Agricultural Research Council (BARC).',
        'about_leader2_name'         => 'Syeda Zarin Tasnim',
        'about_leader2_role'         => 'Head of Venture Acceleration',
        'about_leader2_initials'     => 'SZ',
        'about_leader2_bio'          => 'Ex-Venture Partner at regional Seed VC; led 20+ emerging market startup investments.',
        'about_leader3_name'         => 'Mahmudul Karim, PhD',
        'about_leader3_role'         => 'Chief Agronomy Scientist',
        'about_leader3_initials'     => 'MK',
        'about_leader3_bio'          => 'Professor of Soil Genomics, BAU Mymensingh; specialist in saline-tolerant cultivars.',
        'about_leader4_name'         => 'Annelies de Lange',
        'about_leader4_role'         => 'Global Alliances & ESG Advisor',
        'about_leader4_initials'     => 'AL',
        'about_leader4_bio'          => 'Wageningen Agri-Advisory; leading Dutch-Bangladesh sustainable greenhouse initiatives.',
        'about_advisors_json'        => '',
        'site_logo'                  => '',
        'login_logo'                 => '',
        'landing_gate_enabled'       => '1',
        'landing_site_name'          => 'NextGen IncubationHub',
        'landing_subtitle'           => 'Empowering visionary innovators, farmers, and entrepreneurs with living farmland testbeds, venture capital, and commercial scale.',
        'landing_carousel_enabled'   => '1',
        'landing_carousel_autoplay'  => '5000',
    ];
}

/**
 * Fetch all site settings as associative array [key => value]
 * 
 * @param bool $forceRefresh
 * @return array
 */
function getAllSiteSettings($forceRefresh = false) {
    static $cachedSettings = null;
    if ($forceRefresh) {
        $cachedSettings = null;
    }
    if ($cachedSettings !== null) {
        return $cachedSettings;
    }

    // 1. Base Defaults
    $settings = getDefaultSiteSettings();

    // 2. Read persistent fallback settings file
    $settingsFile = __DIR__ . '/../assets/settings_fallback.json';
    if (file_exists($settingsFile)) {
        $json = @file_get_contents($settingsFile);
        if ($json) {
            $fallbackSettings = json_decode($json, true);
            if (is_array($fallbackSettings)) {
                foreach ($fallbackSettings as $k => $v) {
                    $settings[$k] = (string)$v;
                }
            }
        }
    }

    // 3. MySQL override if live and reachable
    $pdo = getDbConnection();
    if ($pdo !== null) {
        try {
            $stmt = $pdo->query("SELECT setting_key, setting_value FROM site_settings");
            while ($row = $stmt->fetch()) {
                $settings[$row['setting_key']] = $row['setting_value'];
            }
        } catch (PDOException $e) {
            error_log("Failed to load site_settings from MySQL: " . $e->getMessage());
        }
    }

    $cachedSettings = $settings;
    return $settings;
}

/**
 * Get a specific site setting with fallback
 * 
 * @param string $key
 * @param string|null $default
 * @return string
 */
function getSiteSetting($key, $default = null) {
    $settings = getAllSiteSettings();
    if (isset($settings[$key])) {
        return $settings[$key];
    }
    return $default !== null ? $default : '';
}

/**
 * Resolve a logo or media path to a correct relative or absolute URL
 *
 * @param string|null $path
 * @param bool $fromAdmin Whether the caller is located inside /admin/
 * @return string
 */
function getLogoUrl($path = null, $fromAdmin = false) {
    if (empty($path)) {
        return '';
    }
    $path = trim($path);
    // If external URL or data URI, return as-is
    if (preg_match('#^(https?:)?//#i', $path) || str_starts_with($path, 'data:')) {
        return $path;
    }
    // Clean leading slashes
    $cleanPath = ltrim($path, '/\\');
    
    // If called from admin area (e.g. admin/login.php, admin/settings.php)
    if ($fromAdmin) {
        if (str_starts_with($cleanPath, '../')) {
            return $cleanPath;
        }
        return '../' . $cleanPath;
    } else {
        // Called from root (e.g. index.php, includes/header.php)
        if (str_starts_with($cleanPath, '../')) {
            return preg_replace('#^\.\./#', '', $cleanPath);
        }
        return $cleanPath;
    }
}

/**
 * Safely delete an uploaded logo file from assets/uploads/logos/
 *
 * @param string|null $path
 * @return bool
 */
function deleteOldLogoFile($path) {
    if (empty($path)) {
        return false;
    }
    $clean = ltrim($path, '/\\');
    $clean = preg_replace('#^\.\./#', '', $clean);
    if (strpos($clean, 'assets/uploads/logos/') === 0 && !str_contains($clean, '..')) {
        $fullPath = __DIR__ . '/../' . $clean;
        if (file_exists($fullPath) && is_file($fullPath) && basename($fullPath) !== 'index.html') {
            return @unlink($fullPath);
        }
    }
    return false;
}

/**
 * Handle image logo upload securely
 *
 * @param string $fileKey Key in $_FILES
 * @param string $prefix Filename prefix ('site_logo' or 'login_logo')
 * @return array ['success' => bool, 'path' => string, 'error' => string]
 */
function processLogoUpload($fileKey, $prefix = 'logo') {
    if (!isset($_FILES[$fileKey]) || !is_array($_FILES[$fileKey])) {
        return ['success' => false, 'path' => '', 'error' => 'No file received'];
    }

    $file = $_FILES[$fileKey];
    if ($file['error'] !== UPLOAD_ERR_OK) {
        $errorMap = [
            UPLOAD_ERR_INI_SIZE   => 'Uploaded file exceeds server upload_max_filesize limit.',
            UPLOAD_ERR_FORM_SIZE  => 'Uploaded file exceeds form MAX_FILE_SIZE limit.',
            UPLOAD_ERR_PARTIAL    => 'File was only partially uploaded.',
            UPLOAD_ERR_NO_FILE    => 'No file was selected.',
            UPLOAD_ERR_NO_TMP_DIR => 'Missing temporary folder on server.',
            UPLOAD_ERR_CANT_WRITE => 'Failed to write file to disk.',
            UPLOAD_ERR_EXTENSION  => 'A PHP extension stopped the file upload.',
        ];
        return ['success' => false, 'path' => '', 'error' => $errorMap[$file['error']] ?? ('Upload error code: ' . $file['error'])];
    }

    $allowedExts = ['png', 'jpg', 'jpeg', 'webp', 'svg', 'gif'];
    $origName = $file['name'];
    $ext = strtolower(pathinfo($origName, PATHINFO_EXTENSION));

    if (!in_array($ext, $allowedExts)) {
        return ['success' => false, 'path' => '', 'error' => 'Invalid file format (.' . htmlspecialchars($ext) . '). Allowed: PNG, JPG, JPEG, WEBP, SVG, GIF.'];
    }

    // Limit size to 5MB
    $maxBytes = 5 * 1024 * 1024;
    if ($file['size'] > $maxBytes) {
        return ['success' => false, 'path' => '', 'error' => 'File size exceeds 5MB limit. Please upload an optimized image.'];
    }

    $targetDir = __DIR__ . '/../assets/uploads/logos/';
    if (!is_dir($targetDir)) {
        @mkdir($targetDir, 0777, true);
    }

    $safeName = $prefix . '_' . time() . '_' . substr(md5(uniqid(mt_rand(), true)), 0, 8) . '.' . $ext;
    $targetPath = $targetDir . $safeName;

    if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
        return ['success' => false, 'path' => '', 'error' => 'Could not move uploaded file into destination directory.'];
    }

    return [
        'success' => true,
        'path'    => 'assets/uploads/logos/' . $safeName,
        'error'   => ''
    ];
}

/**
 * Update single site setting (Dual-Persistence: MySQL + Fallback JSON)
 * 
 * @param string $key
 * @param string $value
 * @param string $group
 * @return bool
 */
function updateSiteSetting($key, $value, $group = 'general') {
    $dbSaved = false;
    $pdo = getDbConnection();

    if ($pdo !== null) {
        try {
            $stmt = $pdo->prepare("INSERT INTO site_settings (setting_key, setting_value, setting_group) 
                                   VALUES (:key, :value, :group)
                                   ON DUPLICATE KEY UPDATE setting_value = :val_update");
            $dbSaved = $stmt->execute([
                ':key'        => $key,
                ':value'      => $value,
                ':group'      => $group,
                ':val_update' => $value
            ]);
        } catch (PDOException $e) {
            error_log("Error saving site setting to MySQL: " . $e->getMessage());
        }
    }

    // Always mirror to settings_fallback.json for 100% offline uptime & fast retrieval
    $settingsFile = __DIR__ . '/../assets/settings_fallback.json';
    $dir = dirname($settingsFile);
    if (!is_dir($dir)) {
        @mkdir($dir, 0777, true);
    }
    $current = [];
    if (file_exists($settingsFile)) {
        $current = json_decode(@file_get_contents($settingsFile), true) ?: [];
    }
    $current[$key] = (string)$value;
    $fileSaved = @file_put_contents($settingsFile, json_encode($current, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)) !== false;

    // Reset runtime cache
    getAllSiteSettings(true);

    return ($dbSaved || $fileSaved);
}

/**
 * Update multiple site settings at once
 * 
 * @param array $settings [key => value]
 * @return bool
 */
function updateSiteSettings(array $settings) {
    $success = true;
    foreach ($settings as $k => $v) {
        if (!updateSiteSetting($k, $v)) {
            $success = false;
        }
    }
    return $success;
}

/**
 * ============================================================
 * Dynamic Programs Management (Dual Persistence)
 * ============================================================
 */

/**
 * Get path to programs fallback JSON file
 * 
 * @return string
 */
function getProgramsFallbackFilePath() {
    return __DIR__ . '/../assets/programs_fallback.json';
}

/**
 * Read programs from fallback JSON file (auto-creates if missing)
 * 
 * @return array
 */
function getProgramsFromFallbackFile() {
    $filePath = getProgramsFallbackFilePath();
    if (file_exists($filePath)) {
        $json = @file_get_contents($filePath);
        if ($json) {
            $data = json_decode($json, true);
            if (is_array($data) && !empty($data)) {
                return $data;
            }
        }
    }

    // Initialize with default seed programs
    $defaults = getDefaultSeedPrograms();
    $dir = dirname($filePath);
    if (!is_dir($dir)) {
        @mkdir($dir, 0777, true);
    }
    @file_put_contents($filePath, json_encode($defaults, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
    return $defaults;
}

/**
 * Save program to persistent fallback JSON storage
 * 
 * @param array $data
 * @return bool
 */
function saveProgramToFallbackFile(array $data) {
    $filePath = getProgramsFallbackFilePath();
    $dir = dirname($filePath);
    if (!is_dir($dir)) {
        @mkdir($dir, 0777, true);
    }

    $programs = getProgramsFromFallbackFile();
    $id = !empty($data['id']) ? (int)$data['id'] : null;

    $record = [
        'id'           => $id,
        'title'        => (string)($data['title'] ?? ''),
        'stage_tag'    => (string)($data['stage_tag'] ?? ''),
        'duration'     => (string)($data['duration'] ?? ''),
        'funding_text' => (string)($data['funding_text'] ?? ''),
        'field_access' => (string)($data['field_access'] ?? ''),
        'description'  => (string)($data['description'] ?? ''),
        'icon'         => (string)($data['icon'] ?? 'fa-seedling'),
        'badge_status' => (string)($data['badge_status'] ?? 'Applications Open'),
        'status'       => in_array($data['status'] ?? '', ['Active', 'Draft', 'Closed']) ? $data['status'] : 'Active',
        'sort_order'   => (int)($data['sort_order'] ?? 1)
    ];

    $found = false;
    if ($id) {
        foreach ($programs as $idx => $p) {
            if ((int)$p['id'] === $id) {
                $programs[$idx] = array_merge($p, $record);
                $found = true;
                break;
            }
        }
    }

    if (!$found) {
        $maxId = 0;
        foreach ($programs as $p) {
            if ((int)$p['id'] > $maxId) {
                $maxId = (int)$p['id'];
            }
        }
        $record['id'] = $maxId + 1;
        $programs[] = $record;
    }

    // Sort by sort_order ASC, id ASC
    usort($programs, function($a, $b) {
        $soA = (int)($a['sort_order'] ?? 0);
        $soB = (int)($b['sort_order'] ?? 0);
        if ($soA === $soB) {
            return ((int)($a['id'] ?? 0)) <=> ((int)($b['id'] ?? 0));
        }
        return $soA <=> $soB;
    });

    @file_put_contents($filePath, json_encode($programs, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
    return true;
}

/**
 * Remove program from persistent fallback JSON storage
 * 
 * @param int $id
 * @return bool
 */
function deleteProgramFromFallbackFile($id) {
    $filePath = getProgramsFallbackFilePath();
    $programs = getProgramsFromFallbackFile();
    $filtered = [];
    foreach ($programs as $p) {
        if ((int)$p['id'] !== (int)$id) {
            $filtered[] = $p;
        }
    }
    @file_put_contents($filePath, json_encode($filtered, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
    return true;
}

/**
 * Fetch programs from database or persistent fallback storage
 * 
 * @param bool $activeOnly
 * @return array
 */
function getPrograms($activeOnly = false) {
    $pdo = getDbConnection();

    if ($pdo !== null) {
        try {
            $sql = "SELECT * FROM programs";
            if ($activeOnly) {
                $sql .= " WHERE status = 'Active'";
            }
            $sql .= " ORDER BY sort_order ASC, id ASC";
            $stmt = $pdo->query($sql);
            $results = $stmt->fetchAll();
            if (!empty($results)) {
                return $results;
            }
        } catch (PDOException $e) {
            error_log("Failed to fetch programs from MySQL: " . $e->getMessage());
        }
    }

    // Read from persistent fallback storage
    $programs = getProgramsFromFallbackFile();
    if ($activeOnly) {
        $programs = array_filter($programs, function($p) {
            return isset($p['status']) && $p['status'] === 'Active';
        });
    }
    return array_values($programs);
}

/**
 * Get program by ID
 * 
 * @param int $id
 * @return array|null
 */
function getProgramById($id) {
    $id = (int)$id;
    $pdo = getDbConnection();
    if ($pdo !== null) {
        try {
            $stmt = $pdo->prepare("SELECT * FROM programs WHERE id = :id LIMIT 1");
            $stmt->execute([':id' => $id]);
            $row = $stmt->fetch();
            if ($row) return $row;
        } catch (PDOException $e) {
            error_log("Failed to get program by id from MySQL: " . $e->getMessage());
        }
    }

    $all = getProgramsFromFallbackFile();
    foreach ($all as $prog) {
        if ((int)$prog['id'] === $id) {
            return $prog;
        }
    }
    return null;
}

/**
 * Save program (insert or update) with Dual-Persistence
 * Guarantees successful save in both live MySQL and offline environments.
 * 
 * @param array $data
 * @return bool
 */
function saveProgram(array $data) {
    $id          = !empty($data['id']) ? (int)$data['id'] : null;
    $title       = trim($data['title'] ?? '');
    $stageTag    = trim($data['stage_tag'] ?? '');
    $duration    = trim($data['duration'] ?? '');
    $fundingText = trim($data['funding_text'] ?? '');
    $fieldAccess = trim($data['field_access'] ?? '');
    $description = trim($data['description'] ?? '');
    $icon        = trim($data['icon'] ?? 'fa-seedling');
    $badgeStatus = trim($data['badge_status'] ?? 'Applications Open');
    $status      = in_array($data['status'] ?? '', ['Active', 'Draft', 'Closed']) ? $data['status'] : 'Active';
    $sortOrder   = (int)($data['sort_order'] ?? 0);

    $pdo = getDbConnection();
    $dbSaved = false;

    if ($pdo !== null) {
        try {
            if ($id) {
                $stmt = $pdo->prepare("UPDATE programs SET 
                    title = :title, stage_tag = :stage_tag, duration = :duration, funding_text = :funding_text,
                    field_access = :field_access, description = :description, icon = :icon, badge_status = :badge_status,
                    status = :status, sort_order = :sort_order WHERE id = :id");
                $dbSaved = $stmt->execute([
                    ':title'        => $title,
                    ':stage_tag'    => $stageTag,
                    ':duration'     => $duration,
                    ':funding_text' => $fundingText,
                    ':field_access' => $fieldAccess,
                    ':description'  => $description,
                    ':icon'         => $icon,
                    ':badge_status' => $badgeStatus,
                    ':status'       => $status,
                    ':sort_order'   => $sortOrder,
                    ':id'           => $id
                ]);
            } else {
                $stmt = $pdo->prepare("INSERT INTO programs 
                    (title, stage_tag, duration, funding_text, field_access, description, icon, badge_status, status, sort_order)
                    VALUES (:title, :stage_tag, :duration, :funding_text, :field_access, :description, :icon, :badge_status, :status, :sort_order)");
                $dbSaved = $stmt->execute([
                    ':title'        => $title,
                    ':stage_tag'    => $stageTag,
                    ':duration'     => $duration,
                    ':funding_text' => $fundingText,
                    ':field_access' => $fieldAccess,
                    ':description'  => $description,
                    ':icon'         => $icon,
                    ':badge_status' => $badgeStatus,
                    ':status'       => $status,
                    ':sort_order'   => $sortOrder
                ]);
                if ($dbSaved) {
                    $data['id'] = (int)$pdo->lastInsertId();
                }
            }
        } catch (PDOException $e) {
            error_log("Failed to save program to MySQL: " . $e->getMessage());
            $dbSaved = false;
        }
    }

    // Always mirror to persistent JSON storage so changes are preserved and public pages immediately render
    $fileSaved = saveProgramToFallbackFile($data);

    // Return true if saved to DB or fallback file
    return ($dbSaved || $fileSaved);
}

/**
 * Delete program by ID
 * 
 * @param int $id
 * @return bool
 */
function deleteProgram($id) {
    $delId = (int)$id;
    $pdo = getDbConnection();
    if ($pdo !== null) {
        try {
            $stmt = $pdo->prepare("DELETE FROM programs WHERE id = :id");
            $stmt->execute([':id' => $delId]);
        } catch (PDOException $e) {
            error_log("Failed to delete program from MySQL: " . $e->getMessage());
        }
    }

    // Always remove from persistent fallback storage
    return deleteProgramFromFallbackFile($delId);
}

/**
 * ============================================================
 * Dynamic Core Offerings Management (Dual Persistence)
 * ============================================================
 */

function getOfferingsFallbackFilePath() {
    return __DIR__ . '/../assets/offerings_fallback.json';
}

function getOfferingsFromFallbackFile() {
    $filePath = getOfferingsFallbackFilePath();
    if (file_exists($filePath)) {
        $json = @file_get_contents($filePath);
        if ($json) {
            $data = json_decode($json, true);
            if (is_array($data) && !empty($data)) {
                return $data;
            }
        }
    }
    $defaults = getDefaultSeedOfferings();
    $dir = dirname($filePath);
    if (!is_dir($dir)) {
        @mkdir($dir, 0777, true);
    }
    @file_put_contents($filePath, json_encode($defaults, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
    return $defaults;
}

function saveOfferingToFallbackFile(array $data) {
    $filePath = getOfferingsFallbackFilePath();
    $dir = dirname($filePath);
    if (!is_dir($dir)) {
        @mkdir($dir, 0777, true);
    }

    $offerings = getOfferingsFromFallbackFile();
    $id = !empty($data['id']) ? (int)$data['id'] : null;

    $record = [
        'id'          => $id,
        'title'       => (string)($data['title'] ?? ''),
        'category'    => in_array($data['category'] ?? '', ['field', 'capital', 'commercial', 'tech', 'regulatory']) ? $data['category'] : 'field',
        'badge'       => (string)($data['badge'] ?? 'Testbeds'),
        'icon'        => (string)($data['icon'] ?? 'fa-wheat-awn'),
        'description' => (string)($data['description'] ?? ''),
        'features'    => (string)($data['features'] ?? ''),
        'button_text' => (string)($data['button_text'] ?? 'Request Farmland Access →'),
        'button_url'  => (string)($data['button_url'] ?? 'contact.php?category=Entrepreneur'),
        'sort_order'  => (int)($data['sort_order'] ?? 1),
        'status'      => in_array($data['status'] ?? '', ['Active', 'Draft']) ? $data['status'] : 'Active'
    ];

    $found = false;
    if ($id) {
        foreach ($offerings as $idx => $o) {
            if ((int)$o['id'] === $id) {
                $offerings[$idx] = array_merge($o, $record);
                $found = true;
                break;
            }
        }
    }

    if (!$found) {
        $maxId = 0;
        foreach ($offerings as $o) {
            if ((int)$o['id'] > $maxId) {
                $maxId = (int)$o['id'];
            }
        }
        $record['id'] = $maxId + 1;
        $offerings[] = $record;
    }

    usort($offerings, function($a, $b) {
        $soA = (int)($a['sort_order'] ?? 0);
        $soB = (int)($b['sort_order'] ?? 0);
        if ($soA === $soB) {
            return ((int)($a['id'] ?? 0)) <=> ((int)($b['id'] ?? 0));
        }
        return $soA <=> $soB;
    });

    @file_put_contents($filePath, json_encode($offerings, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
    return true;
}

function deleteOfferingFromFallbackFile($id) {
    $filePath = getOfferingsFallbackFilePath();
    $offerings = getOfferingsFromFallbackFile();
    $filtered = [];
    foreach ($offerings as $o) {
        if ((int)$o['id'] !== (int)$id) {
            $filtered[] = $o;
        }
    }
    @file_put_contents($filePath, json_encode($filtered, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
    return true;
}

function getOfferings($activeOnly = false) {
    $pdo = getDbConnection();
    if ($pdo !== null) {
        try {
            $sql = "SELECT * FROM offerings";
            if ($activeOnly) {
                $sql .= " WHERE status = 'Active'";
            }
            $sql .= " ORDER BY sort_order ASC, id ASC";
            $stmt = $pdo->query($sql);
            $results = $stmt->fetchAll();
            if (!empty($results)) {
                return $results;
            }
        } catch (PDOException $e) {
            error_log("Failed to fetch offerings from MySQL: " . $e->getMessage());
        }
    }

    $offerings = getOfferingsFromFallbackFile();
    if ($activeOnly) {
        $offerings = array_filter($offerings, function($o) {
            return isset($o['status']) && $o['status'] === 'Active';
        });
    }
    return array_values($offerings);
}

function getOfferingById($id) {
    $id = (int)$id;
    $pdo = getDbConnection();
    if ($pdo !== null) {
        try {
            $stmt = $pdo->prepare("SELECT * FROM offerings WHERE id = :id LIMIT 1");
            $stmt->execute([':id' => $id]);
            $row = $stmt->fetch();
            if ($row) return $row;
        } catch (PDOException $e) {
            error_log("Failed to get offering by id from MySQL: " . $e->getMessage());
        }
    }

    $all = getOfferingsFromFallbackFile();
    foreach ($all as $o) {
        if ((int)$o['id'] === $id) {
            return $o;
        }
    }
    return null;
}

function saveOffering(array $data) {
    $id          = !empty($data['id']) ? (int)$data['id'] : null;
    $title       = trim($data['title'] ?? '');
    $category    = in_array($data['category'] ?? '', ['field', 'capital', 'commercial', 'tech', 'regulatory']) ? $data['category'] : 'field';
    $badge       = trim($data['badge'] ?? 'Testbeds');
    $icon        = trim($data['icon'] ?? 'fa-wheat-awn');
    $description = trim($data['description'] ?? '');
    $features    = trim($data['features'] ?? '');
    $buttonText  = trim($data['button_text'] ?? 'Request Farmland Access →');
    $buttonUrl   = trim($data['button_url'] ?? 'contact.php?category=Entrepreneur');
    $sortOrder   = (int)($data['sort_order'] ?? 0);
    $status      = in_array($data['status'] ?? '', ['Active', 'Draft']) ? $data['status'] : 'Active';

    $pdo = getDbConnection();
    $dbSaved = false;

    if ($pdo !== null) {
        try {
            if ($id) {
                $stmt = $pdo->prepare("UPDATE offerings SET 
                    title = :title, category = :category, badge = :badge, icon = :icon,
                    description = :description, features = :features, button_text = :button_text,
                    button_url = :button_url, sort_order = :sort_order, status = :status WHERE id = :id");
                $dbSaved = $stmt->execute([
                    ':title'       => $title,
                    ':category'    => $category,
                    ':badge'       => $badge,
                    ':icon'        => $icon,
                    ':description' => $description,
                    ':features'    => $features,
                    ':button_text' => $buttonText,
                    ':button_url'  => $buttonUrl,
                    ':sort_order'  => $sortOrder,
                    ':status'      => $status,
                    ':id'          => $id
                ]);
            } else {
                $stmt = $pdo->prepare("INSERT INTO offerings 
                    (title, category, badge, icon, description, features, button_text, button_url, sort_order, status)
                    VALUES (:title, :category, :badge, :icon, :description, :features, :button_text, :button_url, :sort_order, :status)");
                $dbSaved = $stmt->execute([
                    ':title'       => $title,
                    ':category'    => $category,
                    ':badge'       => $badge,
                    ':icon'        => $icon,
                    ':description' => $description,
                    ':features'    => $features,
                    ':button_text' => $buttonText,
                    ':button_url'  => $buttonUrl,
                    ':sort_order'  => $sortOrder,
                    ':status'      => $status
                ]);
                if ($dbSaved) {
                    $data['id'] = (int)$pdo->lastInsertId();
                }
            }
        } catch (PDOException $e) {
            error_log("Failed to save offering to MySQL: " . $e->getMessage());
            $dbSaved = false;
        }
    }

    $fileSaved = saveOfferingToFallbackFile($data);
    return ($dbSaved || $fileSaved);
}

function deleteOffering($id) {
    $delId = (int)$id;
    $pdo = getDbConnection();
    if ($pdo !== null) {
        try {
            $stmt = $pdo->prepare("DELETE FROM offerings WHERE id = :id");
            $stmt->execute([':id' => $delId]);
        } catch (PDOException $e) {
            error_log("Failed to delete offering from MySQL: " . $e->getMessage());
        }
    }
    return deleteOfferingFromFallbackFile($delId);
}

/**
 * ============================================================
 * Dynamic Network & Partners Management (Dual Persistence)
 * ============================================================
 */

function getNetworkPartnersFallbackFilePath() {
    return __DIR__ . '/../assets/network_fallback.json';
}

function getNetworkPartnersFromFallbackFile() {
    $filePath = getNetworkPartnersFallbackFilePath();
    if (file_exists($filePath)) {
        $json = @file_get_contents($filePath);
        if ($json) {
            $data = json_decode($json, true);
            if (is_array($data) && !empty($data)) {
                return $data;
            }
        }
    }
    $defaults = getDefaultSeedNetworkPartners();
    $dir = dirname($filePath);
    if (!is_dir($dir)) {
        @mkdir($dir, 0777, true);
    }
    @file_put_contents($filePath, json_encode($defaults, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
    return $defaults;
}

function saveNetworkPartnerToFallbackFile(array $data) {
    $filePath = getNetworkPartnersFallbackFilePath();
    $dir = dirname($filePath);
    if (!is_dir($dir)) {
        @mkdir($dir, 0777, true);
    }

    $partners = getNetworkPartnersFromFallbackFile();
    $id = !empty($data['id']) ? (int)$data['id'] : null;

    $record = [
        'id'          => $id,
        'name'        => (string)($data['name'] ?? ''),
        'category'    => in_array($data['category'] ?? '', ['founder', 'investor', 'academic', 'corporate', 'impact']) ? $data['category'] : 'founder',
        'badge'       => (string)($data['badge'] ?? 'Portfolio Startup'),
        'location'    => (string)($data['location'] ?? 'Dhaka'),
        'tagline'     => (string)($data['tagline'] ?? ''),
        'description' => (string)($data['description'] ?? ''),
        'icon'        => (string)($data['icon'] ?? 'fa-seedling'),
        'meta_label'  => (string)($data['meta_label'] ?? 'Cohort Alum'),
        'link_text'   => (string)($data['link_text'] ?? 'Connect →'),
        'link_url'    => (string)($data['link_url'] ?? 'contact.php?category=Entrepreneur'),
        'sort_order'  => (int)($data['sort_order'] ?? 1),
        'status'      => in_array($data['status'] ?? '', ['Active', 'Draft']) ? $data['status'] : 'Active'
    ];

    $found = false;
    if ($id) {
        foreach ($partners as $idx => $p) {
            if ((int)$p['id'] === $id) {
                $partners[$idx] = array_merge($p, $record);
                $found = true;
                break;
            }
        }
    }

    if (!$found) {
        $maxId = 0;
        foreach ($partners as $p) {
            if ((int)$p['id'] > $maxId) {
                $maxId = (int)$p['id'];
            }
        }
        $record['id'] = $maxId + 1;
        $partners[] = $record;
    }

    usort($partners, function($a, $b) {
        $soA = (int)($a['sort_order'] ?? 0);
        $soB = (int)($b['sort_order'] ?? 0);
        if ($soA === $soB) {
            return ((int)($a['id'] ?? 0)) <=> ((int)($b['id'] ?? 0));
        }
        return $soA <=> $soB;
    });

    @file_put_contents($filePath, json_encode($partners, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
    return true;
}

function deleteNetworkPartnerFromFallbackFile($id) {
    $filePath = getNetworkPartnersFallbackFilePath();
    $partners = getNetworkPartnersFromFallbackFile();
    $filtered = [];
    foreach ($partners as $p) {
        if ((int)$p['id'] !== (int)$id) {
            $filtered[] = $p;
        }
    }
    @file_put_contents($filePath, json_encode($filtered, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
    return true;
}

function getNetworkPartners($activeOnly = false) {
    $pdo = getDbConnection();
    if ($pdo !== null) {
        try {
            $sql = "SELECT * FROM network_partners";
            if ($activeOnly) {
                $sql .= " WHERE status = 'Active'";
            }
            $sql .= " ORDER BY sort_order ASC, id ASC";
            $stmt = $pdo->query($sql);
            $results = $stmt->fetchAll();
            if (!empty($results)) {
                return $results;
            }
        } catch (PDOException $e) {
            error_log("Failed to fetch network partners from MySQL: " . $e->getMessage());
        }
    }

    $partners = getNetworkPartnersFromFallbackFile();
    if ($activeOnly) {
        $partners = array_filter($partners, function($p) {
            return isset($p['status']) && $p['status'] === 'Active';
        });
    }
    return array_values($partners);
}

function getNetworkPartnerById($id) {
    $id = (int)$id;
    $pdo = getDbConnection();
    if ($pdo !== null) {
        try {
            $stmt = $pdo->prepare("SELECT * FROM network_partners WHERE id = :id LIMIT 1");
            $stmt->execute([':id' => $id]);
            $row = $stmt->fetch();
            if ($row) return $row;
        } catch (PDOException $e) {
            error_log("Failed to get network partner by id from MySQL: " . $e->getMessage());
        }
    }

    $all = getNetworkPartnersFromFallbackFile();
    foreach ($all as $p) {
        if ((int)$p['id'] === $id) {
            return $p;
        }
    }
    return null;
}

function saveNetworkPartner(array $data) {
    $id          = !empty($data['id']) ? (int)$data['id'] : null;
    $name        = trim($data['name'] ?? '');
    $category    = in_array($data['category'] ?? '', ['founder', 'investor', 'academic', 'corporate', 'impact']) ? $data['category'] : 'founder';
    $badge       = trim($data['badge'] ?? 'Portfolio Startup');
    $location    = trim($data['location'] ?? 'Dhaka');
    $tagline     = trim($data['tagline'] ?? '');
    $description = trim($data['description'] ?? '');
    $icon        = trim($data['icon'] ?? 'fa-seedling');
    $metaLabel   = trim($data['meta_label'] ?? 'Cohort Alum');
    $linkText    = trim($data['link_text'] ?? 'Connect →');
    $linkUrl     = trim($data['link_url'] ?? 'contact.php?category=Entrepreneur');
    $sortOrder   = (int)($data['sort_order'] ?? 0);
    $status      = in_array($data['status'] ?? '', ['Active', 'Draft']) ? $data['status'] : 'Active';

    $pdo = getDbConnection();
    $dbSaved = false;

    if ($pdo !== null) {
        try {
            if ($id) {
                $stmt = $pdo->prepare("UPDATE network_partners SET 
                    name = :name, category = :category, badge = :badge, location = :location,
                    tagline = :tagline, description = :description, icon = :icon,
                    meta_label = :meta_label, link_text = :link_text, link_url = :link_url,
                    sort_order = :sort_order, status = :status WHERE id = :id");
                $dbSaved = $stmt->execute([
                    ':name'        => $name,
                    ':category'    => $category,
                    ':badge'       => $badge,
                    ':location'    => $location,
                    ':tagline'     => $tagline,
                    ':description' => $description,
                    ':icon'        => $icon,
                    ':meta_label'  => $metaLabel,
                    ':link_text'   => $linkText,
                    ':link_url'    => $linkUrl,
                    ':sort_order'  => $sortOrder,
                    ':status'      => $status,
                    ':id'          => $id
                ]);
            } else {
                $stmt = $pdo->prepare("INSERT INTO network_partners 
                    (name, category, badge, location, tagline, description, icon, meta_label, link_text, link_url, sort_order, status)
                    VALUES (:name, :category, :badge, :location, :tagline, :description, :icon, :meta_label, :link_text, :link_url, :sort_order, :status)");
                $dbSaved = $stmt->execute([
                    ':name'        => $name,
                    ':category'    => $category,
                    ':badge'       => $badge,
                    ':location'    => $location,
                    ':tagline'     => $tagline,
                    ':description' => $description,
                    ':icon'        => $icon,
                    ':meta_label'  => $metaLabel,
                    ':link_text'   => $linkText,
                    ':link_url'    => $linkUrl,
                    ':sort_order'  => $sortOrder,
                    ':status'      => $status
                ]);
                if ($dbSaved) {
                    $data['id'] = (int)$pdo->lastInsertId();
                }
            }
        } catch (PDOException $e) {
            error_log("Failed to save network partner to MySQL: " . $e->getMessage());
            $dbSaved = false;
        }
    }

    $fileSaved = saveNetworkPartnerToFallbackFile($data);
    return ($dbSaved || $fileSaved);
}

function deleteNetworkPartner($id) {
    $delId = (int)$id;
    $pdo = getDbConnection();
    if ($pdo !== null) {
        try {
            $stmt = $pdo->prepare("DELETE FROM network_partners WHERE id = :id");
            $stmt->execute([':id' => $delId]);
        } catch (PDOException $e) {
            error_log("Failed to delete network partner from MySQL: " . $e->getMessage());
        }
    }
    return deleteNetworkPartnerFromFallbackFile($delId);
}

/**
 * ============================================================
 * Submissions & Pipeline Management
 * ============================================================
 */

/**
 * Update submission status and optional notes
 * 
 * @param int $id
 * @param string $status
 * @param string|null $notes
 * @return bool
 */
function updateSubmissionStatus($id, $status, $notes = null) {
    $valid = ['New', 'Under Review', 'Shortlisted', 'Contacted', 'Archived'];
    if (!in_array($status, $valid)) return false;

    $id = (int)$id;
    $pdo = getDbConnection();
    $dbUpdated = false;

    if ($pdo !== null) {
        try {
            if ($notes !== null) {
                $stmt = $pdo->prepare("UPDATE hub_submissions SET status = :status, admin_notes = :notes WHERE id = :id");
                $dbUpdated = $stmt->execute([':status' => $status, ':notes' => $notes, ':id' => $id]);
            } else {
                $stmt = $pdo->prepare("UPDATE hub_submissions SET status = :status WHERE id = :id");
                $dbUpdated = $stmt->execute([':status' => $status, ':id' => $id]);
            }
        } catch (PDOException $e) {
            error_log("Failed to update submission in MySQL: " . $e->getMessage());
        }
    }

    // Mirror to fallback storage
    $mockFile = __DIR__ . '/../assets/data_fallback.json';
    if (file_exists($mockFile)) {
        $submissions = json_decode(@file_get_contents($mockFile), true) ?: [];
        foreach ($submissions as &$sub) {
            if ((int)$sub['id'] === $id) {
                $sub['status'] = $status;
                if ($notes !== null) {
                    $sub['admin_notes'] = $notes;
                }
                break;
            }
        }
        @file_put_contents($mockFile, json_encode($submissions, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
    }

    return true;
}

/**
 * Delete submission by ID
 * 
 * @param int $id
 * @return bool
 */
function deleteSubmission($id) {
    $id = (int)$id;
    $pdo = getDbConnection();
    if ($pdo !== null) {
        try {
            $stmt = $pdo->prepare("DELETE FROM hub_submissions WHERE id = :id");
            $stmt->execute([':id' => $id]);
        } catch (PDOException $e) {
            error_log("Failed to delete submission from MySQL: " . $e->getMessage());
        }
    }

    // Remove from fallback storage
    $mockFile = __DIR__ . '/../assets/data_fallback.json';
    if (file_exists($mockFile)) {
        $submissions = json_decode(@file_get_contents($mockFile), true) ?: [];
        $submissions = array_values(array_filter($submissions, function($sub) use ($id) {
            return (int)$sub['id'] !== $id;
        }));
        @file_put_contents($mockFile, json_encode($submissions, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
    }

    return true;
}

/**
 * Get aggregate statistics for Admin Dashboard
 * 
 * @return array
 */
function getAdminDashboardStats() {
    $stats = [
        'total'          => 0,
        'new'            => 0,
        'under_review'   => 0,
        'shortlisted'    => 0,
        'contacted'      => 0,
        'archived'       => 0,
        'entrepreneurs'  => 0,
        'investors'      => 0,
        'organisations'  => 0,
        'mentors'        => 0,
        'subscribers'    => 0,
        'total_programs' => 0,
        'total_offerings'=> 0,
        'total_partners' => 0,
        'total_ideas'    => 0,
        'new_ideas'      => 0,
        'ideas_with_video'=> 0,
        'total_banners'  => 0,
        'active_banners' => 0
    ];

    $pdo = getDbConnection();
    if ($pdo !== null) {
        try {
            // Count submissions by status & category
            $stmt = $pdo->query("SELECT category, status, COUNT(*) as cnt FROM hub_submissions GROUP BY category, status");
            while ($row = $stmt->fetch()) {
                $stats['total'] += (int)$row['cnt'];
                
                $st = strtolower(str_replace(' ', '_', $row['status']));
                if (isset($stats[$st])) {
                    $stats[$st] += (int)$row['cnt'];
                }

                if ($row['category'] === 'Entrepreneur') $stats['entrepreneurs'] += (int)$row['cnt'];
                if ($row['category'] === 'Investor') $stats['investors'] += (int)$row['cnt'];
                if ($row['category'] === 'Support Organisation') $stats['organisations'] += (int)$row['cnt'];
                if ($row['category'] === 'Mentor') $stats['mentors'] += (int)$row['cnt'];
            }

            // Count subscribers
            $subStmt = $pdo->query("SELECT COUNT(*) FROM newsletter_subscribers");
            $stats['subscribers'] = (int)$subStmt->fetchColumn();

            // Count programs
            $progStmt = $pdo->query("SELECT COUNT(*) FROM programs");
            $stats['total_programs'] = (int)$progStmt->fetchColumn();

            // Count offerings
            $offStmt = $pdo->query("SELECT COUNT(*) FROM offerings");
            $stats['total_offerings'] = (int)$offStmt->fetchColumn();

            // Count network partners
            $partStmt = $pdo->query("SELECT COUNT(*) FROM network_partners");
            $stats['total_partners'] = (int)$partStmt->fetchColumn();

            // Count visitor landing page ideas
            if (function_exists('ensureVisitorIdeasTable')) {
                ensureVisitorIdeasTable();
            }
            $ideaStmt = $pdo->query("SELECT status, video_path FROM visitor_ideas");
            if ($ideaStmt) {
                while ($iRow = $ideaStmt->fetch()) {
                    $stats['total_ideas']++;
                    if (($iRow['status'] ?? 'New') === 'New') {
                        $stats['new_ideas']++;
                    }
                    if (!empty($iRow['video_path'])) {
                        $stats['ideas_with_video']++;
                    }
                }
            }

            // Count landing banners
            if (function_exists('ensureLandingBannersTable')) {
                ensureLandingBannersTable();
            }
            $bannerStmt = $pdo->query("SELECT status FROM landing_banners");
            if ($bannerStmt) {
                while ($bRow = $bannerStmt->fetch()) {
                    $stats['total_banners']++;
                    if (($bRow['status'] ?? 'Active') === 'Active') {
                        $stats['active_banners']++;
                    }
                }
            }

            return $stats;
        } catch (PDOException $e) {
            error_log("Failed to get admin stats from MySQL: " . $e->getMessage());
        }
    }

    // Dynamic fallback calculation from local files
    $fallbackFile = __DIR__ . '/../assets/data_fallback.json';
    $programsFile = __DIR__ . '/../assets/programs_fallback.json';
    $offeringsFile= __DIR__ . '/../assets/offerings_fallback.json';
    $partnersFile = __DIR__ . '/../assets/network_fallback.json';
    $ideasFile    = __DIR__ . '/../assets/ideas_fallback.json';
    $bannersFile  = __DIR__ . '/../assets/banners_fallback.json';

    $submissions = file_exists($fallbackFile) ? (json_decode(@file_get_contents($fallbackFile), true) ?: []) : [];
    $programs = file_exists($programsFile) ? (json_decode(@file_get_contents($programsFile), true) ?: []) : [];
    $offerings = file_exists($offeringsFile) ? (json_decode(@file_get_contents($offeringsFile), true) ?: []) : [];
    $partners = file_exists($partnersFile) ? (json_decode(@file_get_contents($partnersFile), true) ?: []) : [];
    $ideas    = file_exists($ideasFile) ? (json_decode(@file_get_contents($ideasFile), true) ?: []) : [];
    $banners  = file_exists($bannersFile) ? (json_decode(@file_get_contents($bannersFile), true) ?: []) : (function_exists('getDefaultLandingBanners') ? getDefaultLandingBanners() : []);

    foreach ($submissions as $row) {
        $stats['total']++;
        $st = strtolower(str_replace(' ', '_', $row['status'] ?? 'new'));
        if (isset($stats[$st])) {
            $stats[$st]++;
        }
        $cat = $row['category'] ?? '';
        if ($cat === 'Entrepreneur') $stats['entrepreneurs']++;
        if ($cat === 'Investor') $stats['investors']++;
        if ($cat === 'Support Organisation') $stats['organisations']++;
        if ($cat === 'Mentor') $stats['mentors']++;
    }

    $stats['total_programs'] = count($programs) ?: 4;
    $stats['total_offerings'] = count($offerings) ?: 6;
    $stats['total_partners'] = count($partners) ?: 6;
    $stats['subscribers'] = 148;

    $stats['total_ideas'] = count($ideas);
    foreach ($ideas as $iItem) {
        if (($iItem['status'] ?? 'New') === 'New') {
            $stats['new_ideas']++;
        }
        if (!empty($iItem['video_path']) || !empty($iItem['video_duration'])) {
            $stats['ideas_with_video']++;
        }
    }

    $stats['total_banners'] = count($banners);
    foreach ($banners as $bItem) {
        if (($bItem['status'] ?? 'Active') === 'Active') {
            $stats['active_banners']++;
        }
    }

    return $stats;
}

/**
 * ============================================================
 * Admin User Authentication
 * ============================================================
 */

/**
 * Verify admin credentials
 * Auto-creates default admin (admin / admin123) if database table is empty
 * 
 * @param string $username
 * @param string $password
 * @return array|false
 */
function verifyAdminLogin($username, $password) {
    $username = trim($username);
    $pdo = getDbConnection();

    if ($pdo !== null) {
        try {
            // Check if admin_users has any records; if empty, seed default admin or from fallback
            $count = (int)$pdo->query("SELECT COUNT(*) FROM admin_users")->fetchColumn();
            if ($count === 0) {
                // If fallback file has admins, seed from fallback file
                $fallbackFile = getAdminsFallbackFilePath();
                $seeded = false;
                if (file_exists($fallbackFile)) {
                    $fallbackAdmins = json_decode(@file_get_contents($fallbackFile), true) ?: [];
                    if (!empty($fallbackAdmins)) {
                        $seedStmt = $pdo->prepare("INSERT INTO admin_users (id, username, email, password_hash, full_name, role)
                                                   VALUES (:id, :u, :e, :p, :f, :r)");
                        foreach ($fallbackAdmins as $fa) {
                            $seedStmt->execute([
                                ':id' => (int)($fa['id'] ?? 1),
                                ':u'  => $fa['username'],
                                ':e'  => $fa['email'],
                                ':p'  => $fa['password_hash'],
                                ':f'  => $fa['full_name'],
                                ':r'  => $fa['role'] ?? 'Super Admin'
                            ]);
                        }
                        $seeded = true;
                    }
                }
                if (!$seeded) {
                    $seedHash = password_hash('admin123', PASSWORD_DEFAULT);
                    $seedStmt = $pdo->prepare("INSERT INTO admin_users (username, email, password_hash, full_name, role)
                                               VALUES ('admin', 'admin@nextgenfarminghub.bd', :hash, 'Incubation Director', 'Super Admin')");
                    $seedStmt->execute([':hash' => $seedHash]);
                }
            }

            $stmt = $pdo->prepare("SELECT * FROM admin_users WHERE username = :u OR email = :u LIMIT 1");
            $stmt->execute([':u' => $username]);
            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['password_hash'])) {
                // Update last login
                $updateStmt = $pdo->prepare("UPDATE admin_users SET last_login = NOW() WHERE id = :id");
                $updateStmt->execute([':id' => $user['id']]);
                return $user;
            }
        } catch (PDOException $e) {
            error_log("Admin auth error: " . $e->getMessage());
        }
    }

    // Fallback mode if MySQL is offline or credentials not found in DB
    $fallbackFile = getAdminsFallbackFilePath();
    if (file_exists($fallbackFile)) {
        $fallbackAdmins = json_decode(@file_get_contents($fallbackFile), true) ?: [];
        foreach ($fallbackAdmins as $fa) {
            if ((strtolower($fa['username']) === strtolower($username) || strtolower($fa['email']) === strtolower($username))
                && !empty($fa['password_hash']) && password_verify($password, $fa['password_hash'])) {
                return [
                    'id'        => (int)($fa['id'] ?? 1),
                    'username'  => $fa['username'] ?? $username,
                    'email'     => $fa['email'] ?? '',
                    'full_name' => $fa['full_name'] ?? 'Administrator',
                    'role'      => $fa['role'] ?? 'Super Admin'
                ];
            }
        }
    }

    // Only allow default admin / admin123 if fallback file does NOT exist
    if (!file_exists($fallbackFile)) {
        if ($username === 'admin' && $password === 'admin123') {
            return [
                'id'        => 1,
                'username'  => 'admin',
                'email'     => 'admin@nextgenfarminghub.bd',
                'full_name' => 'Incubation Director',
                'role'      => 'Super Admin'
            ];
        }
    }

    return false;
}

/**
 * Update Admin Password & Profile
 * 
 * @param int|string $userId
 * @param string|null $newPassword
 * @param string|null $fullName
 * @param string|null $email
 * @param string|null $username
 * @return bool
 */
function updateAdminProfile($userId, $newPassword = null, $fullName = null, $email = null, $username = null) {
    $dbSaved = false;
    $pdo = getDbConnection();

    if ($pdo !== null) {
        try {
            $fields = [];
            $params = [];

            if (!empty($newPassword)) {
                $fields[] = "password_hash = :hash";
                $params[':hash'] = password_hash($newPassword, PASSWORD_DEFAULT);
            }
            if (!empty($fullName)) {
                $fields[] = "full_name = :name";
                $params[':name'] = trim($fullName);
            }
            if (!empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $fields[] = "email = :email";
                $params[':email'] = trim($email);
            }

            if (!empty($fields)) {
                $updated = false;

                // 1. Try update by user ID
                if (!empty($userId)) {
                    $idParams = $params;
                    $idParams[':id'] = (int)$userId;
                    $stmt = $pdo->prepare("UPDATE admin_users SET " . implode(", ", $fields) . " WHERE id = :id");
                    $stmt->execute($idParams);
                    if ($stmt->rowCount() > 0) {
                        $updated = true;
                    }
                }

                // 2. If no row updated and username provided, try update by username
                if (!$updated && !empty($username)) {
                    $userParams = $params;
                    $userParams[':uname'] = $username;
                    $stmt = $pdo->prepare("UPDATE admin_users SET " . implode(", ", $fields) . " WHERE username = :uname");
                    $stmt->execute($userParams);
                    if ($stmt->rowCount() > 0) {
                        $updated = true;
                    }
                }

                // 3. If user doesn't exist yet in MySQL table, insert them
                if (!$updated) {
                    $passHash = !empty($newPassword) ? password_hash($newPassword, PASSWORD_DEFAULT) : password_hash('admin123', PASSWORD_DEFAULT);
                    $insStmt = $pdo->prepare("INSERT INTO admin_users (username, email, password_hash, full_name, role)
                                              VALUES (:u, :e, :p, :f, 'Super Admin')");
                    $insStmt->execute([
                        ':u' => $username ?: 'admin',
                        ':e' => $email ?: 'admin@nextgenfarminghub.bd',
                        ':p' => $passHash,
                        ':f' => $fullName ?: 'Incubation Director'
                    ]);
                }

                $dbSaved = true;
                syncAdminsToFallbackFile();
            } else {
                $dbSaved = true;
            }
        } catch (PDOException $e) {
            error_log("Failed to update admin profile in DB: " . $e->getMessage());
        }
    }

    // Always update/mirror to fallback file assets/admins_fallback.json
    $filePath = getAdminsFallbackFilePath();
    $dir = dirname($filePath);
    if (!is_dir($dir)) @mkdir($dir, 0777, true);

    $admins = [];
    if (file_exists($filePath)) {
        $admins = json_decode(@file_get_contents($filePath), true) ?: [];
    }
    if (empty($admins)) {
        $admins = getDefaultSeedAdmins();
    }

    $found = false;
    foreach ($admins as &$a) {
        $matchId = !empty($userId) && (int)($a['id'] ?? 0) === (int)$userId;
        $matchUser = !empty($username) && strtolower($a['username'] ?? '') === strtolower($username);

        if ($matchId || $matchUser) {
            if (!empty($fullName)) $a['full_name'] = trim($fullName);
            if (!empty($email)) $a['email'] = trim($email);
            if (!empty($newPassword)) $a['password_hash'] = password_hash($newPassword, PASSWORD_DEFAULT);
            $found = true;
            break;
        }
    }

    if (!$found) {
        $admins[] = [
            'id'            => (int)$userId ?: 1,
            'username'      => $username ?: 'admin',
            'email'         => $email ?: 'admin@nextgenfarminghub.bd',
            'password_hash' => !empty($newPassword) ? password_hash($newPassword, PASSWORD_DEFAULT) : password_hash('admin123', PASSWORD_DEFAULT),
            'full_name'     => $fullName ?: 'Administrator',
            'role'          => 'Super Admin',
            'last_login'    => null,
            'created_at'    => date('Y-m-d H:i:s'),
        ];
    }

    $fileSaved = @file_put_contents($filePath, json_encode($admins, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)) !== false;

    return ($dbSaved || $fileSaved);
}

/**
 * Path to administrators fallback JSON file
 * 
 * @return string
 */
function getAdminsFallbackFilePath() {
    return __DIR__ . '/../assets/admins_fallback.json';
}

/**
 * Default initial admin seed
 * 
 * @return array
 */
function getDefaultSeedAdmins() {
    return [
        [
            'id'            => 1,
            'username'      => 'admin',
            'email'         => 'admin@nextgenfarminghub.bd',
            'password_hash' => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', // admin123
            'full_name'     => 'Incubation Director',
            'role'          => 'Super Admin',
            'last_login'    => null,
            'created_at'    => date('Y-m-d H:i:s'),
        ]
    ];
}

/**
 * Fetch all administrator users (Dual persistence: MySQL + Fallback JSON)
 * 
 * @return array
 */
function getAllAdminUsers() {
    $pdo = getDbConnection();
    if ($pdo !== null) {
        try {
            $stmt = $pdo->query("SELECT id, username, email, full_name, role, last_login, created_at FROM admin_users ORDER BY id ASC");
            $users = $stmt->fetchAll();
            if (!empty($users)) {
                return $users;
            }
        } catch (PDOException $e) {
            error_log("Failed to query admin_users: " . $e->getMessage());
        }
    }

    // Fallback file
    $filePath = getAdminsFallbackFilePath();
    if (file_exists($filePath)) {
        $json = @file_get_contents($filePath);
        if ($json) {
            $data = json_decode($json, true);
            if (is_array($data) && !empty($data)) {
                return $data;
            }
        }
    }

    return getDefaultSeedAdmins();
}

/**
 * Create a new administrator user
 * 
 * @param array $data ['username', 'email', 'password', 'full_name', 'role']
 * @return array ['success' => bool, 'message' => string, 'id' => int|null]
 */
function createAdminUser($data) {
    $username = trim($data['username'] ?? '');
    $email    = trim($data['email'] ?? '');
    $password = trim($data['password'] ?? '');
    $fullName = trim($data['full_name'] ?? '');
    $role     = in_array($data['role'] ?? '', ['Super Admin', 'Editor']) ? $data['role'] : 'Super Admin';

    // Validation
    if ($username === '' || !preg_match('/^[a-zA-Z0-9_\-\.]{3,30}$/', $username)) {
        return ['success' => false, 'message' => 'Username must be 3-30 characters long and contain only letters, numbers, dots, hyphens or underscores.'];
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return ['success' => false, 'message' => 'Please provide a valid administrator email address.'];
    }

    if (strlen($password) < 6) {
        return ['success' => false, 'message' => 'Password must be at least 6 characters long.'];
    }

    if ($fullName === '') {
        $fullName = ucfirst($username);
    }

    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    $pdo = getDbConnection();
    if ($pdo !== null) {
        try {
            // Check if username or email already exists
            $checkStmt = $pdo->prepare("SELECT id, username, email FROM admin_users WHERE username = :u OR email = :e LIMIT 1");
            $checkStmt->execute([':u' => $username, ':e' => $email]);
            $existing = $checkStmt->fetch();
            if ($existing) {
                if (strtolower($existing['username']) === strtolower($username)) {
                    return ['success' => false, 'message' => "An administrator with username '{$username}' already exists."];
                }
                return ['success' => false, 'message' => "An administrator with email '{$email}' already exists."];
            }

            $stmt = $pdo->prepare("INSERT INTO admin_users (username, email, password_hash, full_name, role, created_at)
                                   VALUES (:u, :e, :p, :f, :r, NOW())");
            $stmt->execute([
                ':u' => $username,
                ':e' => $email,
                ':p' => $passwordHash,
                ':f' => $fullName,
                ':r' => $role
            ]);
            $newId = (int)$pdo->lastInsertId();

            // Sync to fallback file
            syncAdminsToFallbackFile();

            return ['success' => true, 'message' => "Administrator '{$fullName}' (@{$username}) created successfully!", 'id' => $newId];
        } catch (PDOException $e) {
            error_log("Failed to insert admin_user: " . $e->getMessage());
            return ['success' => false, 'message' => "Database error creating administrator: " . $e->getMessage()];
        }
    }

    // Fallback mode if MySQL is offline
    $filePath = getAdminsFallbackFilePath();
    $dir = dirname($filePath);
    if (!is_dir($dir)) @mkdir($dir, 0777, true);

    $admins = [];
    if (file_exists($filePath)) {
        $admins = json_decode(@file_get_contents($filePath), true) ?: [];
    }
    if (empty($admins)) {
        $admins = getDefaultSeedAdmins();
    }

    foreach ($admins as $a) {
        if (strtolower($a['username']) === strtolower($username)) {
            return ['success' => false, 'message' => "An administrator with username '{$username}' already exists."];
        }
        if (strtolower($a['email']) === strtolower($email)) {
            return ['success' => false, 'message' => "An administrator with email '{$email}' already exists."];
        }
    }

    $maxId = 1;
    foreach ($admins as $a) {
        if (isset($a['id']) && $a['id'] > $maxId) $maxId = (int)$a['id'];
    }
    $newId = $maxId + 1;

    $admins[] = [
        'id'            => $newId,
        'username'      => $username,
        'email'         => $email,
        'password_hash' => $passwordHash,
        'full_name'     => $fullName,
        'role'          => $role,
        'last_login'    => null,
        'created_at'    => date('Y-m-d H:i:s'),
    ];

    @file_put_contents($filePath, json_encode($admins, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
    return ['success' => true, 'message' => "Administrator '{$fullName}' (@{$username}) created successfully!", 'id' => $newId];
}

/**
 * Delete an administrator user
 * 
 * @param int $userId
 * @param int $currentAdminId
 * @return array ['success' => bool, 'message' => string]
 */
function deleteAdminUser($userId, $currentAdminId) {
    $userId = (int)$userId;
    $currentAdminId = (int)$currentAdminId;

    if ($userId <= 0) {
        return ['success' => false, 'message' => 'Invalid administrator ID provided.'];
    }

    if ($userId === $currentAdminId) {
        return ['success' => false, 'message' => 'Security constraint: You cannot delete your own active administrator account!'];
    }

    $pdo = getDbConnection();
    if ($pdo !== null) {
        try {
            $totalCount = (int)$pdo->query("SELECT COUNT(*) FROM admin_users")->fetchColumn();
            if ($totalCount <= 1) {
                return ['success' => false, 'message' => 'Cannot delete the only remaining administrator on the system.'];
            }

            $stmt = $pdo->prepare("DELETE FROM admin_users WHERE id = :id");
            $stmt->execute([':id' => $userId]);

            syncAdminsToFallbackFile();

            return ['success' => true, 'message' => 'Administrator account deleted successfully.'];
        } catch (PDOException $e) {
            error_log("Failed to delete admin_user: " . $e->getMessage());
            return ['success' => false, 'message' => "Database error deleting administrator: " . $e->getMessage()];
        }
    }

    // Fallback file deletion
    $filePath = getAdminsFallbackFilePath();
    if (file_exists($filePath)) {
        $admins = json_decode(@file_get_contents($filePath), true) ?: [];
        if (count($admins) <= 1) {
            return ['success' => false, 'message' => 'Cannot delete the only remaining administrator on the system.'];
        }
        $admins = array_values(array_filter($admins, function($a) use ($userId) {
            return (int)($a['id'] ?? 0) !== $userId;
        }));
        @file_put_contents($filePath, json_encode($admins, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
    }

    return ['success' => true, 'message' => 'Administrator account deleted successfully.'];
}

/**
 * Helper to sync MySQL admin records into fallback JSON file
 */
function syncAdminsToFallbackFile() {
    $pdo = getDbConnection();
    if ($pdo === null) return;
    try {
        $stmt = $pdo->query("SELECT id, username, email, password_hash, full_name, role, last_login, created_at FROM admin_users ORDER BY id ASC");
        $users = $stmt->fetchAll();
        if (!empty($users)) {
            $filePath = getAdminsFallbackFilePath();
            $dir = dirname($filePath);
            if (!is_dir($dir)) @mkdir($dir, 0777, true);
            @file_put_contents($filePath, json_encode($users, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
        }
    } catch (PDOException $e) {
        // Silently ignore
    }
}

/**
 * ============================================================
 * Visitor Landing Page Idea Submissions & Gate Access Layer
 * ============================================================
 */

function getVisitorIdeasFallbackFilePath() {
    return __DIR__ . '/../assets/ideas_fallback.json';
}

function ensureVisitorIdeasTable() {
    $pdo = getDbConnection();
    if ($pdo === null) return false;
    try {
        $pdo->exec("CREATE TABLE IF NOT EXISTS `visitor_ideas` (
            `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            `full_name` VARCHAR(150) NOT NULL,
            `category` VARCHAR(50) NOT NULL DEFAULT 'Entrepreneurs',
            `email` VARCHAR(150) DEFAULT NULL,
            `phone` VARCHAR(50) NOT NULL,
            `local_address` TEXT NOT NULL,
            `education` VARCHAR(255) NOT NULL,
            `idea_description` TEXT NOT NULL,
            `video_path` VARCHAR(255) DEFAULT NULL,
            `video_filename` VARCHAR(255) DEFAULT NULL,
            `video_duration` INT UNSIGNED DEFAULT NULL,
            `video_size` INT UNSIGNED DEFAULT NULL,
            `status` ENUM('New', 'Reviewed', 'Shortlisted', 'Contacted', 'Archived') DEFAULT 'New',
            `admin_notes` TEXT DEFAULT NULL,
            `ip_address` VARCHAR(45) DEFAULT NULL,
            `user_agent` VARCHAR(255) DEFAULT NULL,
            `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            INDEX `idx_category` (`category`),
            INDEX `idx_status` (`status`),
            INDEX `idx_created_at` (`created_at`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

        // Ensure category column exists in already created tables
        try {
            $colCheck = $pdo->query("SHOW COLUMNS FROM `visitor_ideas` LIKE 'category'")->fetch();
            if (!$colCheck) {
                $pdo->exec("ALTER TABLE `visitor_ideas` ADD COLUMN `category` VARCHAR(50) NOT NULL DEFAULT 'Entrepreneurs' AFTER `full_name`");
                $pdo->exec("ALTER TABLE `visitor_ideas` ADD INDEX `idx_category` (`category`)");
            }
            $pdo->exec("UPDATE `visitor_ideas` SET `category` = 'Entrepreneurs' WHERE `category` IS NULL OR `category` = ''");
        } catch (PDOException $eCol) {
            // Silently ignore if already exists
        }

        return true;
    } catch (PDOException $e) {
        error_log("Failed to ensure visitor_ideas table: " . $e->getMessage());
        return false;
    }
}

/**
 * Check if the current visitor has full access to the website
 */
function isVisitorUnlocked() {
    if (session_status() === PHP_SESSION_NONE) {
        @session_start();
    }

    // Manual reset check for testing gate intake flow
    if (isset($_GET['reset_gate']) || isset($_GET['reset'])) {
        unset($_SESSION['nextgen_visitor_unlocked']);
        @setcookie('nextgen_visitor_pass', '', time() - 3600, '/');
        return false;
    }

    // If gate is disabled in settings, allow everyone
    $gateEnabled = getSiteSetting('landing_gate_enabled', '1');
    if ($gateEnabled === '0' || $gateEnabled === 'false') {
        return true;
    }

    // Explicit Admin bypass if requested with query parameter
    if (isset($_GET['admin_bypass']) && !empty($_SESSION['admin_user'])) {
        $_SESSION['nextgen_visitor_unlocked'] = true;
        return true;
    }

    // Session unlock flag (set after successfully submitting idea form)
    if (!empty($_SESSION['nextgen_visitor_unlocked'])) {
        return true;
    }

    // Cookie persistent unlock check (valid for 365 days)
    if (!empty($_COOKIE['nextgen_visitor_pass'])) {
        $_SESSION['nextgen_visitor_unlocked'] = true;
        return true;
    }

    return false;
}

/**
 * Force visitor to landing page unless unlocked
 */
function requireVisitorAccess() {
    if (isVisitorUnlocked()) {
        return;
    }

    $currentScript = basename($_SERVER['PHP_SELF'] ?? '', '.php');
    if ($currentScript === 'landing') {
        return;
    }

    // Do not gate admin or api scripts
    $uri = $_SERVER['REQUEST_URI'] ?? '';
    if (strpos($uri, '/admin/') !== false || strpos($uri, '/api/') !== false) {
        return;
    }

    header('Location: landing.php');
    exit;
}

/**
 * Save visitor idea submission and grant website unlock access
 */
function saveVisitorIdea(array $data, ?array $videoFile = null) {
    $fullName     = trim($data['full_name'] ?? '');
    $rawCategory  = trim($data['category'] ?? '');
    if (strcasecmp($rawCategory, 'Investor') === 0 || strcasecmp($rawCategory, 'Investors') === 0) {
        $category = 'Investors';
    } elseif (strcasecmp($rawCategory, 'Mentor') === 0 || strcasecmp($rawCategory, 'Mentors') === 0) {
        $category = 'Mentors';
    } elseif (strcasecmp($rawCategory, 'Organizer') === 0 || strcasecmp($rawCategory, 'Organizers') === 0 || stripos($rawCategory, 'organis') !== false || stripos($rawCategory, 'organiz') !== false) {
        $category = 'Organizers';
    } else {
        $category = 'Entrepreneurs';
    }

    $email        = trim($data['email'] ?? '');
    $phone        = trim($data['phone'] ?? '');
    $localAddress = trim($data['local_address'] ?? '');
    $education    = trim($data['education'] ?? '');
    $ideaDesc     = trim($data['idea_description'] ?? '');
    $videoDuration= !empty($data['video_duration']) ? (int)$data['video_duration'] : null;
    $ipAddress    = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
    $userAgent    = substr($_SERVER['HTTP_USER_AGENT'] ?? '', 0, 255);

    // Validation
    if (empty($fullName)) {
        return ['success' => false, 'message' => 'Please provide your Full Name.'];
    }
    if (empty($phone)) {
        return ['success' => false, 'message' => 'Please provide your WhatsApp Phone number.'];
    }
    if (empty($localAddress)) {
        return ['success' => false, 'message' => 'Please provide your Local Address.'];
    }
    if (empty($education)) {
        return ['success' => false, 'message' => 'Please provide your Education / Academic background.'];
    }
    if (empty($ideaDesc)) {
        return ['success' => false, 'message' => 'Please tell us a little bit about your idea.'];
    }
    if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return ['success' => false, 'message' => 'Please enter a valid email address or leave it blank.'];
    }

    // Video Upload Processing (Optional, max 5 minutes)
    $videoPath = null;
    $videoOriginalName = null;
    $videoSize = null;

    if ($videoFile && isset($videoFile['error']) && $videoFile['error'] === UPLOAD_ERR_OK) {
        $allowedExts = ['mp4', 'webm', 'mov', 'avi', 'm4v', 'mkv', '3gp'];
        $originalExt = strtolower(pathinfo($videoFile['name'], PATHINFO_EXTENSION));

        if (!in_array($originalExt, $allowedExts)) {
            return ['success' => false, 'message' => 'Unsupported video format. Please upload MP4, WebM, MOV, or AVI.'];
        }

        // Max file size 100MB
        if ($videoFile['size'] > 104857600) {
            return ['success' => false, 'message' => 'Video size exceeds 100MB. Please compress or shorten your 5-minute pitch video.'];
        }

        $uploadDir = __DIR__ . '/../assets/uploads/pitch_videos/';
        if (!is_dir($uploadDir)) {
            @mkdir($uploadDir, 0777, true);
        }

        $newFileName = 'pitch_' . date('Ymd_His') . '_' . bin2hex(random_bytes(5)) . '.' . $originalExt;
        $destPath = $uploadDir . $newFileName;

        if (move_uploaded_file($videoFile['tmp_name'], $destPath)) {
            $videoPath = 'assets/uploads/pitch_videos/' . $newFileName;
            $videoOriginalName = preg_replace('/[^a-zA-Z0-9_\.-]/', '_', basename($videoFile['name']));
            $videoSize = (int)$videoFile['size'];
        } else {
            error_log("Failed to move uploaded pitch video to: " . $destPath);
        }
    } elseif ($videoFile && isset($videoFile['error']) && $videoFile['error'] !== UPLOAD_ERR_NO_FILE) {
        if ($videoFile['error'] === UPLOAD_ERR_INI_SIZE || $videoFile['error'] === UPLOAD_ERR_FORM_SIZE) {
            return ['success' => false, 'message' => 'Uploaded video exceeds server upload limits. Please choose a smaller video file.'];
        }
    }

    $newId = null;
    $pdo = getDbConnection();

    if ($pdo !== null) {
        ensureVisitorIdeasTable();
        try {
            $sql = "INSERT INTO visitor_ideas 
                    (full_name, category, email, phone, local_address, education, idea_description, video_path, video_filename, video_duration, video_size, status, ip_address, user_agent, created_at)
                    VALUES (:full_name, :category, :email, :phone, :local_address, :education, :idea_description, :video_path, :video_filename, :video_duration, :video_size, 'New', :ip_address, :user_agent, NOW())";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':full_name'        => $fullName,
                ':category'         => $category,
                ':email'            => !empty($email) ? $email : null,
                ':phone'            => $phone,
                ':local_address'    => $localAddress,
                ':education'        => $education,
                ':idea_description' => $ideaDesc,
                ':video_path'       => $videoPath,
                ':video_filename'   => $videoOriginalName,
                ':video_duration'   => $videoDuration,
                ':video_size'       => $videoSize,
                ':ip_address'       => $ipAddress,
                ':user_agent'       => $userAgent,
            ]);
            $newId = (int)$pdo->lastInsertId();
        } catch (PDOException $e) {
            error_log("Failed to insert visitor idea into MySQL: " . $e->getMessage());
        }
    }

    // Always mirror to fallback file
    $fallbackFile = getVisitorIdeasFallbackFilePath();
    $ideas = file_exists($fallbackFile) ? (json_decode(@file_get_contents($fallbackFile), true) ?: []) : [];
    if (!$newId) {
        $newId = count($ideas) + 1;
    }

    $newRecord = [
        'id'               => $newId,
        'full_name'        => htmlspecialchars($fullName),
        'category'         => $category,
        'email'            => htmlspecialchars($email),
        'phone'            => htmlspecialchars($phone),
        'local_address'    => htmlspecialchars($localAddress),
        'education'        => htmlspecialchars($education),
        'idea_description' => htmlspecialchars($ideaDesc),
        'video_path'       => $videoPath,
        'video_filename'   => $videoOriginalName,
        'video_duration'   => $videoDuration,
        'video_size'       => $videoSize,
        'status'           => 'New',
        'admin_notes'      => null,
        'ip_address'       => $ipAddress,
        'user_agent'       => $userAgent,
        'created_at'       => date('Y-m-d H:i:s')
    ];

    array_unshift($ideas, $newRecord);
    @file_put_contents($fallbackFile, json_encode($ideas, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));

    // Unlock visitor session and set persistent cookie
    if (session_status() === PHP_SESSION_NONE) {
        @session_start();
    }
    $_SESSION['nextgen_visitor_unlocked'] = true;
    $_SESSION['visitor_name'] = $fullName;

    // Cookie valid for 365 days
    @setcookie('nextgen_visitor_pass', 'unlocked_' . md5($fullName . $phone), time() + (86400 * 365), '/');

    return [
        'success'  => true,
        'id'       => $newId,
        'category' => $category,
        'message'  => 'Thank you, ' . htmlspecialchars($fullName) . '! Your idea has been sent to our incubation management board. Full website access is now unlocked.'
    ];
}

function getVisitorIdeas($limit = 100, $status = null, $search = null, $category = null) {
    $pdo = getDbConnection();
    if ($pdo !== null) {
        ensureVisitorIdeasTable();
        try {
            $sql = "SELECT * FROM visitor_ideas WHERE 1=1";
            $params = [];
            if (!empty($status)) {
                $sql .= " AND status = :status";
                $params[':status'] = $status;
            }
            if (!empty($category)) {
                $sql .= " AND category = :category";
                $params[':category'] = $category;
            }
            if (!empty($search)) {
                $sql .= " AND (full_name LIKE :s OR category LIKE :s OR phone LIKE :s OR email LIKE :s OR local_address LIKE :s OR education LIKE :s OR idea_description LIKE :s)";
                $params[':s'] = '%' . $search . '%';
            }
            $sql .= " ORDER BY created_at DESC LIMIT " . (int)$limit;
            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Failed to query visitor_ideas: " . $e->getMessage());
        }
    }

    $fallbackFile = getVisitorIdeasFallbackFilePath();
    $ideas = file_exists($fallbackFile) ? (json_decode(@file_get_contents($fallbackFile), true) ?: []) : [];

    if (!empty($status)) {
        $ideas = array_values(array_filter($ideas, function($i) use ($status) {
            return ($i['status'] ?? '') === $status;
        }));
    }
    if (!empty($category)) {
        $ideas = array_values(array_filter($ideas, function($i) use ($category) {
            return ($i['category'] ?? 'Entrepreneurs') === $category;
        }));
    }
    if (!empty($search)) {
        $searchLower = strtolower($search);
        $ideas = array_values(array_filter($ideas, function($i) use ($searchLower) {
            $txt = strtolower(($i['full_name'] ?? '') . ' ' . ($i['category'] ?? '') . ' ' . ($i['phone'] ?? '') . ' ' . ($i['email'] ?? '') . ' ' . ($i['local_address'] ?? '') . ' ' . ($i['education'] ?? '') . ' ' . ($i['idea_description'] ?? ''));
            return strpos($txt, $searchLower) !== false;
        }));
    }
    return array_slice($ideas, 0, $limit);
}

function getVisitorIdeaCountsByCategory() {
    $counts = [
        'Entrepreneurs' => 0,
        'Investors'     => 0,
        'Mentors'       => 0,
        'Organizers'    => 0
    ];

    $pdo = getDbConnection();
    if ($pdo !== null) {
        try {
            ensureVisitorIdeasTable();
            $stmt = $pdo->query("SELECT category, COUNT(*) as cnt FROM visitor_ideas GROUP BY category");
            if ($stmt) {
                while ($row = $stmt->fetch()) {
                    $cat = trim($row['category'] ?? '');
                    $cnt = (int)($row['cnt'] ?? 0);
                    if (strcasecmp($cat, 'Investor') === 0 || strcasecmp($cat, 'Investors') === 0) {
                        $counts['Investors'] += $cnt;
                    } elseif (strcasecmp($cat, 'Mentor') === 0 || strcasecmp($cat, 'Mentors') === 0) {
                        $counts['Mentors'] += $cnt;
                    } elseif (strcasecmp($cat, 'Organizer') === 0 || strcasecmp($cat, 'Organizers') === 0 || stripos($cat, 'organis') !== false || stripos($cat, 'organiz') !== false) {
                        $counts['Organizers'] += $cnt;
                    } else {
                        $counts['Entrepreneurs'] += $cnt;
                    }
                }
                return $counts;
            }
        } catch (PDOException $e) {
            error_log("Failed to count visitor ideas by category: " . $e->getMessage());
        }
    }

    $fallbackFile = getVisitorIdeasFallbackFilePath();
    if (file_exists($fallbackFile)) {
        $ideas = json_decode(@file_get_contents($fallbackFile), true) ?: [];
        foreach ($ideas as $item) {
            $cat = trim($item['category'] ?? '');
            if (strcasecmp($cat, 'Investor') === 0 || strcasecmp($cat, 'Investors') === 0) {
                $counts['Investors']++;
            } elseif (strcasecmp($cat, 'Mentor') === 0 || strcasecmp($cat, 'Mentors') === 0) {
                $counts['Mentors']++;
            } elseif (strcasecmp($cat, 'Organizer') === 0 || strcasecmp($cat, 'Organizers') === 0 || stripos($cat, 'organis') !== false || stripos($cat, 'organiz') !== false) {
                $counts['Organizers']++;
            } else {
                $counts['Entrepreneurs']++;
            }
        }
    }

    return $counts;
}

function getVisitorIdeaById($id) {
    $id = (int)$id;
    if ($id <= 0) return null;
    $pdo = getDbConnection();
    if ($pdo !== null) {
        try {
            $stmt = $pdo->prepare("SELECT * FROM visitor_ideas WHERE id = :id LIMIT 1");
            $stmt->execute([':id' => $id]);
            $res = $stmt->fetch();
            if ($res) return $res;
        } catch (PDOException $e) {
            error_log("Failed to get visitor idea by ID: " . $e->getMessage());
        }
    }

    $fallbackFile = getVisitorIdeasFallbackFilePath();
    $ideas = file_exists($fallbackFile) ? (json_decode(@file_get_contents($fallbackFile), true) ?: []) : [];
    foreach ($ideas as $item) {
        if ((int)($item['id'] ?? 0) === $id) return $item;
    }
    return null;
}

function updateVisitorIdeaStatus($id, $status, $adminNotes = null) {
    $id = (int)$id;
    if ($id <= 0) return false;
    $allowedStatuses = ['New', 'Reviewed', 'Shortlisted', 'Contacted', 'Archived'];
    if (!in_array($status, $allowedStatuses)) return false;

    $pdo = getDbConnection();
    if ($pdo !== null) {
        try {
            $sql = "UPDATE visitor_ideas SET status = :status";
            $params = [':status' => $status, ':id' => $id];
            if ($adminNotes !== null) {
                $sql .= ", admin_notes = :notes";
                $params[':notes'] = $adminNotes;
            }
            $sql .= " WHERE id = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
        } catch (PDOException $e) {
            error_log("Failed to update visitor idea status in MySQL: " . $e->getMessage());
        }
    }

    $fallbackFile = getVisitorIdeasFallbackFilePath();
    if (file_exists($fallbackFile)) {
        $ideas = json_decode(@file_get_contents($fallbackFile), true) ?: [];
        foreach ($ideas as &$item) {
            if ((int)($item['id'] ?? 0) === $id) {
                $item['status'] = $status;
                if ($adminNotes !== null) {
                    $item['admin_notes'] = $adminNotes;
                }
                break;
            }
        }
        @file_put_contents($fallbackFile, json_encode($ideas, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
    }
    return true;
}

function deleteVisitorIdea($id) {
    $id = (int)$id;
    if ($id <= 0) return false;

    $existing = getVisitorIdeaById($id);
    if (!empty($existing['video_path'])) {
        $fullPath = __DIR__ . '/../' . ltrim($existing['video_path'], '/');
        if (file_exists($fullPath)) {
            @unlink($fullPath);
        }
    }

    $pdo = getDbConnection();
    if ($pdo !== null) {
        try {
            $stmt = $pdo->prepare("DELETE FROM visitor_ideas WHERE id = :id");
            $stmt->execute([':id' => $id]);
        } catch (PDOException $e) {
            error_log("Failed to delete visitor idea in MySQL: " . $e->getMessage());
        }
    }

    $fallbackFile = getVisitorIdeasFallbackFilePath();
    if (file_exists($fallbackFile)) {
        $ideas = json_decode(@file_get_contents($fallbackFile), true) ?: [];
        $ideas = array_values(array_filter($ideas, function($item) use ($id) {
            return (int)($item['id'] ?? 0) !== $id;
        }));
        @file_put_contents($fallbackFile, json_encode($ideas, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
    }
    return true;
}

/**
 * ============================================================
 * LANDING PAGE DYNAMIC BANNER CAROUSEL FUNCTIONS
 * ============================================================
 */

function getDefaultLandingBanners() {
    return [
        [
            'id' => 1,
            'title' => 'Scale Your AgriTech Innovation to National Impact',
            'subtitle' => 'Access 500+ acres of living farmland testbeds, IoT sensor laboratories, and direct commercial off-take partnerships across Bangladesh.',
            'badge' => 'Flagship Accelerator 2026',
            'image_path' => 'https://images.unsplash.com/photo-1586771107445-d3ca888129ff?auto=format&fit=crop&w=1600&q=80',
            'link_text' => 'Submit Your Idea & Unlock Access',
            'link_url' => '#intake-form-section',
            'status' => 'Active',
            'sort_order' => 1
        ],
        [
            'id' => 2,
            'title' => 'Pre-Seed & Catalytic Risk Capital for Agripreneurs',
            'subtitle' => '$35,000 – $50,000 SAFE agreements, syndicate investor matchmaking, and institutional grant advisory to build resilient ventures.',
            'badge' => 'Catalytic Venture Funding',
            'image_path' => 'https://images.unsplash.com/photo-1625246333195-78d9c38ad449?auto=format&fit=crop&w=1600&q=80',
            'link_text' => 'Pitch to Investment Committee',
            'link_url' => '#intake-form-section',
            'status' => 'Active',
            'sort_order' => 2
        ],
        [
            'id' => 3,
            'title' => 'Bridging Research, Farmers & Venture Studio Ecosystems',
            'subtitle' => 'Collaborate with DAE agronomists, university research labs, and supply chain aggregators to engineer climate-smart agriculture.',
            'badge' => 'Ecosystem Alliances',
            'image_path' => 'https://images.unsplash.com/photo-1530836369250-ef72a3f5cda8?auto=format&fit=crop&w=1600&q=80',
            'link_text' => 'Join NextGen Cohort',
            'link_url' => '#intake-form-section',
            'status' => 'Active',
            'sort_order' => 3
        ]
    ];
}

function getLandingBannersFallbackFilePath() {
    return __DIR__ . '/../assets/banners_fallback.json';
}

function ensureLandingBannersTable() {
    $pdo = getDbConnection();
    if ($pdo === null) return false;
    try {
        $pdo->exec("CREATE TABLE IF NOT EXISTS `landing_banners` (
            `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            `title` VARCHAR(255) NOT NULL,
            `subtitle` TEXT DEFAULT NULL,
            `badge` VARCHAR(100) DEFAULT NULL,
            `image_path` VARCHAR(255) NOT NULL,
            `link_text` VARCHAR(100) DEFAULT 'Explore More',
            `link_url` VARCHAR(255) DEFAULT '#intake-form-section',
            `sort_order` INT DEFAULT 0,
            `status` ENUM('Active', 'Draft') DEFAULT 'Active',
            `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            INDEX `idx_status` (`status`),
            INDEX `idx_sort` (`sort_order`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

        // Seed if empty
        $check = (int)$pdo->query("SELECT COUNT(*) FROM landing_banners")->fetchColumn();
        if ($check === 0) {
            $seeds = getDefaultLandingBanners();
            $stmt = $pdo->prepare("INSERT INTO landing_banners (id, title, subtitle, badge, image_path, link_text, link_url, sort_order, status)
                VALUES (:id, :title, :subtitle, :badge, :image_path, :link_text, :link_url, :sort_order, :status)");
            foreach ($seeds as $s) {
                $stmt->execute([
                    ':id' => $s['id'],
                    ':title' => $s['title'],
                    ':subtitle' => $s['subtitle'],
                    ':badge' => $s['badge'],
                    ':image_path' => $s['image_path'],
                    ':link_text' => $s['link_text'],
                    ':link_url' => $s['link_url'],
                    ':sort_order' => $s['sort_order'],
                    ':status' => $s['status']
                ]);
            }
        }
        return true;
    } catch (PDOException $e) {
        error_log("Failed to ensure landing_banners table: " . $e->getMessage());
        return false;
    }
}

function getLandingBanners($activeOnly = false) {
    $pdo = getDbConnection();
    if ($pdo !== null) {
        try {
            ensureLandingBannersTable();
            $sql = "SELECT * FROM landing_banners";
            if ($activeOnly) {
                $sql .= " WHERE status = 'Active'";
            }
            $sql .= " ORDER BY sort_order ASC, id ASC";
            $stmt = $pdo->query($sql);
            $rows = $stmt->fetchAll();
            if (!empty($rows)) {
                return $rows;
            }
        } catch (PDOException $e) {
            error_log("Failed to fetch landing_banners from MySQL: " . $e->getMessage());
        }
    }

    // Fallback file handling
    $fallbackFile = getLandingBannersFallbackFilePath();
    if (file_exists($fallbackFile)) {
        $banners = json_decode(@file_get_contents($fallbackFile), true) ?: [];
    } else {
        $banners = getDefaultLandingBanners();
        @file_put_contents($fallbackFile, json_encode($banners, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
    }

    if ($activeOnly) {
        $banners = array_values(array_filter($banners, function($item) {
            return ($item['status'] ?? 'Active') === 'Active';
        }));
    }

    usort($banners, function($a, $b) {
        $sa = (int)($a['sort_order'] ?? 0);
        $sb = (int)($b['sort_order'] ?? 0);
        if ($sa === $sb) {
            return ((int)($a['id'] ?? 0)) <=> ((int)($b['id'] ?? 0));
        }
        return $sa <=> $sb;
    });

    return $banners;
}

function getLandingBannerById($id) {
    $id = (int)$id;
    if ($id <= 0) return null;

    $pdo = getDbConnection();
    if ($pdo !== null) {
        try {
            ensureLandingBannersTable();
            $stmt = $pdo->prepare("SELECT * FROM landing_banners WHERE id = :id LIMIT 1");
            $stmt->execute([':id' => $id]);
            $row = $stmt->fetch();
            if ($row) return $row;
        } catch (PDOException $e) {
            error_log("Failed to fetch landing banner by id from MySQL: " . $e->getMessage());
        }
    }

    $fallbackFile = getLandingBannersFallbackFilePath();
    if (file_exists($fallbackFile)) {
        $banners = json_decode(@file_get_contents($fallbackFile), true) ?: [];
        foreach ($banners as $b) {
            if ((int)($b['id'] ?? 0) === $id) {
                return $b;
            }
        }
    }
    return null;
}

function saveLandingBanner(array $data) {
    $id        = !empty($data['id']) ? (int)$data['id'] : null;
    $title     = trim($data['title'] ?? '');
    $subtitle  = trim($data['subtitle'] ?? '');
    $badge     = trim($data['badge'] ?? '');
    $imagePath = trim($data['image_path'] ?? '');
    $linkText  = trim($data['link_text'] ?? '');
    $linkUrl   = trim($data['link_url'] ?? '#intake-form-section');
    $sortOrder = (int)($data['sort_order'] ?? 0);
    $status    = in_array($data['status'] ?? '', ['Active', 'Draft']) ? $data['status'] : 'Active';

    if (empty($title)) {
        return false;
    }

    // Default image if empty
    if (empty($imagePath)) {
        $imagePath = 'https://images.unsplash.com/photo-1586771107445-d3ca888129ff?auto=format&fit=crop&w=1600&q=80';
    }

    $pdo = getDbConnection();
    $dbSaved = false;

    if ($pdo !== null) {
        try {
            ensureLandingBannersTable();
            if ($id) {
                $stmt = $pdo->prepare("UPDATE landing_banners SET 
                    title = :title, subtitle = :subtitle, badge = :badge,
                    image_path = :image_path, link_text = :link_text, link_url = :link_url,
                    sort_order = :sort_order, status = :status WHERE id = :id");
                $dbSaved = $stmt->execute([
                    ':title'      => $title,
                    ':subtitle'   => $subtitle,
                    ':badge'      => $badge,
                    ':image_path' => $imagePath,
                    ':link_text'  => $linkText,
                    ':link_url'   => $linkUrl,
                    ':sort_order' => $sortOrder,
                    ':status'     => $status,
                    ':id'         => $id
                ]);
            } else {
                $stmt = $pdo->prepare("INSERT INTO landing_banners 
                    (title, subtitle, badge, image_path, link_text, link_url, sort_order, status)
                    VALUES (:title, :subtitle, :badge, :image_path, :link_text, :link_url, :sort_order, :status)");
                $dbSaved = $stmt->execute([
                    ':title'      => $title,
                    ':subtitle'   => $subtitle,
                    ':badge'      => $badge,
                    ':image_path' => $imagePath,
                    ':link_text'  => $linkText,
                    ':link_url'   => $linkUrl,
                    ':sort_order' => $sortOrder,
                    ':status'     => $status
                ]);
                if ($dbSaved) {
                    $id = (int)$pdo->lastInsertId();
                }
            }
        } catch (PDOException $e) {
            error_log("Failed to save landing banner in MySQL: " . $e->getMessage());
        }
    }

    // Dual-persistence fallback file
    $fallbackFile = getLandingBannersFallbackFilePath();
    $banners = file_exists($fallbackFile) ? (json_decode(@file_get_contents($fallbackFile), true) ?: []) : getDefaultLandingBanners();
    
    if (!$id) {
        $maxId = 0;
        foreach ($banners as $b) {
            if ((int)($b['id'] ?? 0) > $maxId) $maxId = (int)$b['id'];
        }
        $id = $maxId + 1;
    }

    $updated = false;
    foreach ($banners as &$b) {
        if ((int)($b['id'] ?? 0) === $id) {
            $b['title']      = $title;
            $b['subtitle']   = $subtitle;
            $b['badge']      = $badge;
            $b['image_path'] = $imagePath;
            $b['link_text']  = $linkText;
            $b['link_url']   = $linkUrl;
            $b['sort_order'] = $sortOrder;
            $b['status']     = $status;
            $updated = true;
            break;
        }
    }
    unset($b);

    if (!$updated) {
        $banners[] = [
            'id'         => $id,
            'title'      => $title,
            'subtitle'   => $subtitle,
            'badge'      => $badge,
            'image_path' => $imagePath,
            'link_text'  => $linkText,
            'link_url'   => $linkUrl,
            'sort_order' => $sortOrder,
            'status'     => $status
        ];
    }

    @file_put_contents($fallbackFile, json_encode($banners, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
    return true;
}

function deleteLandingBanner($id) {
    $id = (int)$id;
    if ($id <= 0) return false;

    $existing = getLandingBannerById($id);
    if (!empty($existing['image_path'])) {
        deleteOldBannerFile($existing['image_path']);
    }

    $pdo = getDbConnection();
    if ($pdo !== null) {
        try {
            ensureLandingBannersTable();
            $stmt = $pdo->prepare("DELETE FROM landing_banners WHERE id = :id");
            $stmt->execute([':id' => $id]);
        } catch (PDOException $e) {
            error_log("Failed to delete landing banner from MySQL: " . $e->getMessage());
        }
    }

    $fallbackFile = getLandingBannersFallbackFilePath();
    if (file_exists($fallbackFile)) {
        $banners = json_decode(@file_get_contents($fallbackFile), true) ?: [];
        $banners = array_values(array_filter($banners, function($item) use ($id) {
            return (int)($item['id'] ?? 0) !== $id;
        }));
        @file_put_contents($fallbackFile, json_encode($banners, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
    }
    return true;
}

function toggleLandingBannerStatus($id) {
    $existing = getLandingBannerById($id);
    if (!$existing) return false;

    $newStatus = (($existing['status'] ?? 'Active') === 'Active') ? 'Draft' : 'Active';
    $existing['status'] = $newStatus;
    return saveLandingBanner($existing);
}

function resetDefaultLandingBanners() {
    $seeds = getDefaultLandingBanners();
    $pdo = getDbConnection();
    if ($pdo !== null) {
        try {
            ensureLandingBannersTable();
            $pdo->exec("TRUNCATE TABLE landing_banners");
            $stmt = $pdo->prepare("INSERT INTO landing_banners (id, title, subtitle, badge, image_path, link_text, link_url, sort_order, status)
                VALUES (:id, :title, :subtitle, :badge, :image_path, :link_text, :link_url, :sort_order, :status)");
            foreach ($seeds as $s) {
                $stmt->execute([
                    ':id' => $s['id'],
                    ':title' => $s['title'],
                    ':subtitle' => $s['subtitle'],
                    ':badge' => $s['badge'],
                    ':image_path' => $s['image_path'],
                    ':link_text' => $s['link_text'],
                    ':link_url' => $s['link_url'],
                    ':sort_order' => $s['sort_order'],
                    ':status' => $s['status']
                ]);
            }
        } catch (PDOException $e) {
            error_log("Failed to reset landing banners in MySQL: " . $e->getMessage());
        }
    }

    $fallbackFile = getLandingBannersFallbackFilePath();
    @file_put_contents($fallbackFile, json_encode($seeds, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
    return true;
}

function getBannerImageUrl($path = null, $fromAdmin = false) {
    if (empty($path)) {
        return 'https://images.unsplash.com/photo-1586771107445-d3ca888129ff?auto=format&fit=crop&w=1600&q=80';
    }
    $path = trim($path);
    // If external URL or data URI, return as-is
    if (preg_match('#^(https?:)?//#i', $path) || str_starts_with($path, 'data:')) {
        return $path;
    }
    $cleanPath = ltrim($path, '/\\');
    if ($fromAdmin) {
        if (str_starts_with($cleanPath, '../')) {
            return $cleanPath;
        }
        return '../' . $cleanPath;
    } else {
        if (str_starts_with($cleanPath, '../')) {
            return preg_replace('#^\.\./#', '', $cleanPath);
        }
        return $cleanPath;
    }
}

function deleteOldBannerFile($path) {
    if (empty($path)) return false;
    $clean = ltrim($path, '/\\');
    $clean = preg_replace('#^\.\./#', '', $clean);
    if (strpos($clean, 'assets/uploads/banners/') === 0 && !str_contains($clean, '..')) {
        $fullPath = __DIR__ . '/../' . $clean;
        if (file_exists($fullPath) && is_file($fullPath) && basename($fullPath) !== 'index.html') {
            return @unlink($fullPath);
        }
    }
    return false;
}

function processBannerUpload($fileKey, $prefix = 'banner') {
    if (!isset($_FILES[$fileKey]) || !is_array($_FILES[$fileKey])) {
        return ['success' => false, 'path' => '', 'error' => 'No file received'];
    }

    $file = $_FILES[$fileKey];
    if ($file['error'] !== UPLOAD_ERR_OK) {
        $errorMap = [
            UPLOAD_ERR_INI_SIZE   => 'Uploaded image exceeds server upload_max_filesize limit.',
            UPLOAD_ERR_FORM_SIZE  => 'Uploaded image exceeds form MAX_FILE_SIZE limit.',
            UPLOAD_ERR_PARTIAL    => 'Image was only partially uploaded.',
            UPLOAD_ERR_NO_FILE    => 'No image was selected.',
            UPLOAD_ERR_NO_TMP_DIR => 'Missing temporary folder on server.',
            UPLOAD_ERR_CANT_WRITE => 'Failed to write image file to disk.',
            UPLOAD_ERR_EXTENSION  => 'A PHP extension stopped the file upload.',
        ];
        return ['success' => false, 'path' => '', 'error' => $errorMap[$file['error']] ?? ('Upload error code: ' . $file['error'])];
    }

    $allowedExts = ['png', 'jpg', 'jpeg', 'webp', 'gif'];
    $origName = $file['name'];
    $ext = strtolower(pathinfo($origName, PATHINFO_EXTENSION));

    if (!in_array($ext, $allowedExts)) {
        return ['success' => false, 'path' => '', 'error' => 'Invalid image format (.' . htmlspecialchars($ext) . '). Allowed: PNG, JPG, JPEG, WEBP, GIF.'];
    }

    // Limit size to 10MB
    $maxBytes = 10 * 1024 * 1024;
    if ($file['size'] > $maxBytes) {
        return ['success' => false, 'path' => '', 'error' => 'Image size exceeds 10MB limit. Please upload an optimized banner image.'];
    }

    $targetDir = __DIR__ . '/../assets/uploads/banners/';
    if (!is_dir($targetDir)) {
        @mkdir($targetDir, 0777, true);
    }

    $safeName = $prefix . '_' . time() . '_' . substr(md5(uniqid(mt_rand(), true)), 0, 8) . '.' . $ext;
    $targetPath = $targetDir . $safeName;

    if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
        return ['success' => false, 'path' => '', 'error' => 'Could not move uploaded banner into assets/uploads/banners/.'];
    }

    return [
        'success' => true,
        'path'    => 'assets/uploads/banners/' . $safeName,
        'error'   => ''
    ];
}



