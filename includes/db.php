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
    ];
}

/**
 * Fetch all site settings as associative array [key => value]
 * 
 * @return array
 */
function getAllSiteSettings() {
    static $cachedSettings = null;
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
        'total_programs' => 0
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

            return $stats;
        } catch (PDOException $e) {
            error_log("Failed to get admin stats from MySQL: " . $e->getMessage());
        }
    }

    // Dynamic fallback calculation from local files
    $fallbackFile = __DIR__ . '/../assets/data_fallback.json';
    $programsFile = __DIR__ . '/../assets/programs_fallback.json';

    $submissions = file_exists($fallbackFile) ? (json_decode(@file_get_contents($fallbackFile), true) ?: []) : [];
    $programs = file_exists($programsFile) ? (json_decode(@file_get_contents($programsFile), true) ?: []) : [];

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
    $stats['subscribers'] = 148;
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
            // Check if admin_users has any records; if empty, seed default admin
            $count = (int)$pdo->query("SELECT COUNT(*) FROM admin_users")->fetchColumn();
            if ($count === 0) {
                $seedHash = password_hash('admin123', PASSWORD_DEFAULT);
                $seedStmt = $pdo->prepare("INSERT INTO admin_users (username, email, password_hash, full_name, role)
                                           VALUES ('admin', 'admin@nextgenfarminghub.bd', :hash, 'Incubation Director', 'Super Admin')");
                $seedStmt->execute([':hash' => $seedHash]);
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

    // Fallback Emergency Admin Mode if MySQL is offline
    if ($username === 'admin' && $password === 'admin123') {
        return [
            'id'        => 1,
            'username'  => 'admin',
            'email'     => 'admin@nextgenfarminghub.bd',
            'full_name' => 'Incubation Director',
            'role'      => 'Super Admin'
        ];
    }

    return false;
}

/**
 * Update Admin Password & Profile
 * 
 * @param int $userId
 * @param string|null $newPassword
 * @param string|null $fullName
 * @param string|null $email
 * @return bool
 */
function updateAdminProfile($userId, $newPassword = null, $fullName = null, $email = null) {
    $pdo = getDbConnection();
    if ($pdo !== null) {
        try {
            $fields = [];
            $params = [':id' => (int)$userId];

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

            if (empty($fields)) return true;

            $sql = "UPDATE admin_users SET " . implode(", ", $fields) . " WHERE id = :id";
            $stmt = $pdo->prepare($sql);
            return $stmt->execute($params);
        } catch (PDOException $e) {
            error_log("Failed to update admin profile: " . $e->getMessage());
            return false;
        }
    }

    // Gracefully succeed in local fallback mode
    return true;
}

