-- ============================================================
-- NextGen Farming Incubation Hub - Database Schema Script
-- Optimized for cPanel phpMyAdmin & Dynamic CMS Admin Portal
-- Database Target: nextgenp_incubation
-- ============================================================
-- 
-- CPANEL IMPORT INSTRUCTIONS:
-- 1. In your cPanel dashboard, open "phpMyAdmin".
-- 2. On the left sidebar, click on your database: "nextgenp_incubation".
-- 3. Click the "Import" tab at the top.
-- 4. Choose this file (schema.sql) and click "Import" / "Go" at the bottom.
-- ============================================================

-- USE `nextgenp_incubation`;

-- ------------------------------------------------------------
-- 1. Table: admin_users
-- Controls authentication & roles for the Admin Dashboard
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `admin_users` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `username` VARCHAR(50) NOT NULL UNIQUE,
    `email` VARCHAR(100) NOT NULL UNIQUE,
    `password_hash` VARCHAR(255) NOT NULL,
    `full_name` VARCHAR(100) DEFAULT 'Hub Administrator',
    `role` ENUM('Super Admin', 'Editor') DEFAULT 'Super Admin',
    `last_login` TIMESTAMP NULL DEFAULT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- 2. Table: site_settings
-- Stores all dynamic content across the website
-- (Hero text, track record metrics, contact details, banners)
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `site_settings` (
    `setting_key` VARCHAR(100) PRIMARY KEY,
    `setting_value` TEXT NOT NULL,
    `setting_group` VARCHAR(50) DEFAULT 'general',
    `label` VARCHAR(150) DEFAULT NULL,
    `field_type` ENUM('text', 'textarea', 'number') DEFAULT 'text',
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- 3. Table: programs
-- Dynamic accelerator cohorts & founder residencies
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `programs` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- 4. Table: hub_submissions
-- Captures applications & partnership inquiries from
-- Entrepreneurs, Investors, Support Organisations, and Mentors
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `hub_submissions` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `full_name` VARCHAR(150) NOT NULL,
    `email` VARCHAR(150) NOT NULL,
    `phone` VARCHAR(50) DEFAULT NULL,
    `organization` VARCHAR(150) DEFAULT NULL,
    `category` ENUM('Entrepreneur', 'Investor', 'Support Organisation', 'Mentor') NOT NULL,
    `sub_interest` VARCHAR(100) DEFAULT NULL,
    `pitch_url` VARCHAR(255) DEFAULT NULL,
    `message` TEXT NOT NULL,
    `admin_notes` TEXT DEFAULT NULL,
    `ip_address` VARCHAR(45) DEFAULT NULL,
    `status` ENUM('New', 'Under Review', 'Shortlisted', 'Contacted', 'Archived') DEFAULT 'New',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX `idx_category` (`category`),
    INDEX `idx_created_at` (`created_at`),
    INDEX `idx_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- 5. Table: newsletter_subscribers
-- Captures email subscriptions from the website footer
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `newsletter_subscribers` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `email` VARCHAR(150) NOT NULL UNIQUE,
    `subscribed_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `is_active` TINYINT(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- 6. Table: offerings
-- Dynamic core offerings & infrastructure cards
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `offerings` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- 7. Table: network_partners
-- Dynamic ecosystem directory alliances & portfolio partners
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `network_partners` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- SEED DATA: Dynamic Site Settings, Programs, and Admin User
-- ============================================================

-- Default Admin Account (Username: admin | Password: admin123)
-- Hash generated using standard password_hash('admin123', PASSWORD_BCRYPT)
INSERT INTO `admin_users` (`username`, `email`, `password_hash`, `full_name`, `role`)
VALUES ('admin', 'admin@nextgenfarminghub.bd', '$2y$10$wO3o0l3rA/2hGf4m1sE0lO/7Qf6jI4q.Y5tF1zG9xK2mP0o8N3pLu', 'Incubation Director', 'Super Admin')
ON DUPLICATE KEY UPDATE `username` = `username`;

-- Dynamic Content Settings
INSERT INTO `site_settings` (`setting_key`, `setting_value`, `setting_group`, `label`, `field_type`) VALUES
('site_name', 'NextGen Farming Incubation Hub', 'general', 'Website Title', 'text'),
('hero_tagline', 'AgriTech Innovation & Venture Studio', 'hero', 'Hero Tagline Badge', 'text'),
('hero_title_main', 'NextGen Farming', 'hero', 'Hero Title Line 1', 'text'),
('hero_title_highlight', 'Incubation Hub', 'hero', 'Hero Title Highlighted', 'text'),
('hero_subtitle', 'Connecting Entrepreneur Ecosystem builders in Bangladesh and Emerging Markets.', 'hero', 'Hero Subtitle', 'textarea'),
('hero_description', 'We nurture transformative agricultural startups, unlock catalytic capital, and connect cross-border knowledge to build resilient, high-yield, and climate-adaptive food systems.', 'hero', 'Hero Description', 'textarea'),
('hero_banner_text', 'Applications Open for AgriTech Ignite Accelerator', 'hero', 'Announcement Banner Text', 'text'),
('hero_banner_badge', 'New Call', 'hero', 'Announcement Badge Text', 'text'),
('hero_banner_link', 'contact.php?category=Entrepreneur', 'hero', 'Announcement Link Target', 'text'),

('metric_members', '5200', 'track_record', 'Community Members Count', 'number'),
('metric_members_suffix', '+', 'track_record', 'Community Members Suffix', 'text'),
('metric_members_desc', 'A vibrant network of over 5,200 passionate smallholder innovators, agronomy scientists, youth climate advocates, university researchers, and rural co-op leaders spanning all 64 districts of Bangladesh and key regional farming corridors.', 'track_record', 'Community Members Description', 'textarea'),

('metric_ventures', '128', 'track_record', 'Business Ventures Count', 'number'),
('metric_ventures_suffix', '+', 'track_record', 'Business Ventures Suffix', 'text'),
('metric_ventures_desc', 'Over 128 high-growth agricultural ventures accelerated across post-harvest cold storage, biotech input formulations, sensor-driven irrigation, and digital farm-to-table supply chains, mobilizing more than $28.5M in cumulative follow-on investment.', 'track_record', 'Business Ventures Description', 'textarea'),

('metric_capital', '$28.5M+', 'track_record', 'Total Catalyzed Funding', 'text'),

('metric_organizers', '48', 'track_record', 'Program Organizers Count', 'number'),
('metric_organizers_suffix', '+', 'track_record', 'Program Organizers Suffix', 'text'),
('metric_organizers_desc', 'A robust coalition of 48+ co-design partners, including premier agricultural universities, multilateral development agencies, impact venture syndicates, and corporate agribusiness giants collaborating to execute world-class incubation cohorts.', 'track_record', 'Program Organizers Description', 'textarea'),

('contact_email', 'connect@nextgenfarminghub.bd', 'contact', 'Public Contact Email', 'text'),
('contact_phone', '+880 2 988-5544 / +880 1711-002233', 'contact', 'Public Contact Telephone', 'text'),
('contact_address', 'Level 7, Venture Tower, Gulshan-2, Dhaka 1212, Bangladesh', 'contact', 'Physical Headquarters Address', 'textarea'),
('admissions_email', 'accelerate@nextgenfarminghub.bd', 'contact', 'Admissions Email', 'text'),
('investors_email', 'investors@nextgenfarminghub.bd', 'contact', 'Investor Relations Email', 'text'),

-- Programs Page Content
('programs_badge', 'Structured Incubation Tracks', 'programs', 'Programs Page Badge', 'text'),
('programs_title', 'Programs & Founder Residencies', 'programs', 'Programs Page Title', 'text'),
('programs_desc', 'From early lab prototypes to multi-district commercial scale, our specialized tracks provide catalytic risk capital, empirical farmland living labs, and global venture guidance.', 'programs', 'Programs Page Subtext', 'textarea'),

-- Core Offerings Content
('offerings_badge', 'Venture Studio & Technical Infrastructure', 'offerings', 'Offerings Page Badge', 'text'),
('offerings_title_main', 'Full-Stack Capabilities Built for', 'offerings', 'Offerings Main Title', 'text'),
('offerings_title_highlight', 'Agricultural Transformation', 'offerings', 'Offerings Title Highlight', 'text'),
('offerings_desc', 'Generalist tech accelerators cannot solve the physical complexities of biology, volatile weather, rural logistics, and fragmented middleman chains. NextGen Farming Hub provides purpose-built technical, empirical, and commercial infrastructure tailored for emerging deltaic markets.', 'offerings', 'Offerings Subtext', 'textarea'),

-- Networking Content
('networking_badge', 'Connecting Entrepreneur Ecosystem Builders', 'networking', 'Networking Page Badge', 'text'),
('networking_title_main', 'A Connected Web of', 'networking', 'Networking Main Title', 'text'),
('networking_title_highlight', 'Founders, Investors & Scientists', 'networking', 'Networking Title Highlight', 'text'),
('networking_desc', 'Agriculture cannot be transformed in isolation. NextGen Farming Incubation Hub weaves together Bangladesh’s rural innovators, leading agronomy universities, regional venture syndicates, and global emerging market networks.', 'networking', 'Networking Subtext', 'textarea')
ON DUPLICATE KEY UPDATE `setting_value` = VALUES(`setting_value`);

-- Dynamic Accelerator Programs
INSERT INTO `programs` 
(`title`, `stage_tag`, `duration`, `funding_text`, `field_access`, `description`, `icon`, `badge_status`, `status`, `sort_order`) 
VALUES
('AgriTech Ignite Accelerator', 'Pre-Seed Stage • 16-Week Structured Sprint', '16 Weeks (Hybrid: Dhaka & Field Labs)', '$35,000 – $50,000 Pre-Seed SAFE', 'Guaranteed Farmland Pilot in Bogura', 'Designed for early-stage founders with an MVP, prototype, or validated proof-of-concept. Focuses on field experimentation, smallholder user feedback, unit economics, and initial institutional capital readiness.', 'fa-seedling', 'Applications Open', 'Active', 1),
('HarvestScale Growth Program', 'Seed to Series A • 6-Month Scale Sprint', '6 Months Bespoke Advisory', 'Up to $250,000 Co-Investment Syndicate', 'Direct PRAN, ACI, BRAC Integration', 'Engineered for startups with proven product-market fit and commercial revenue. Focuses on multi-district distribution scaling, corporate off-take contracts, supply-chain automation, and international venture capital syndication.', 'fa-chart-line', 'Rolling Review', 'Active', 2),
('ClimateSmart Bio-Residency', 'Deep-Tech Science • 12-Month Academic & Lab Residency', '12 Months Residency', 'Research Commercialization Grant', 'BAU Genomics & Wageningen Peer Support', 'Translating frontier agronomy breakthroughs (saline-tolerant genetics, mycorrhizal bio-fertilizers, and microbial pest management) from university research labs into commercially viable startup spin-offs.', 'fa-dna', 'Selective Cohort', 'Active', 3),
('Women in AgriTech Fellowship', 'Inclusion & Leadership • 6-Month Catalytic Track', '6-Month Leadership Track', '$15,000 Equity-Free Innovation Grant', 'Direct Linkage to 50+ Women Co-ops', 'Empowering female founders, agribusiness operators, and rural cooperative organizers. Features dedicated non-dilutive catalytic grants, executive female mentor matching, and distributor network onboarding.', 'fa-venus', 'Grant Funded', 'Active', 4)
ON DUPLICATE KEY UPDATE `title` = VALUES(`title`);

-- Dynamic Core Offerings
INSERT INTO `offerings`
(`title`, `category`, `badge`, `icon`, `description`, `features`, `button_text`, `button_url`, `sort_order`, `status`)
VALUES
('Empirical Living Farmland Testbeds', 'field', 'Testbeds', 'fa-wheat-awn', 'Over 500+ dedicated acreage in Bogura (vegetable clusters), Rajshahi (drought/Barind), and Khulna (coastal saline delta) equipped with real smallholder farmer co-ops.', 'On-site IoT telemetry stations & weather radar\nMulti-spectral drone corridor approvals\nCohorts of 200+ participatory farmers', 'Request Farmland Access →', 'contact.php?category=Entrepreneur', 1, 'Active'),
('Catalytic Capital & Seed Syndication', 'capital', 'Venture Capital', 'fa-sack-dollar', 'Direct pre-seed SAFE capital injections and coordinated syndicate matching with top-tier angel investors and institutional venture capital across Asia & Europe.', '$35k - $50k Founder-Friendly SAFE terms\nDemo Day showcase to 60+ global funds\nNon-dilutive grant application matching', 'Explore Capital Syndicates →', 'contact.php?category=Investor', 2, 'Active'),
('Corporate Off-Take & Procurement', 'commercial', 'Enterprise', 'fa-truck-ramp-box', 'Pre-structured pilot integrations with Bangladesh’s premier agribusiness conglomerates (PRAN-RFL, ACI Agribusiness, Square Foods, BRAC Enterprises).', 'Guaranteed pilot off-take commercial contracts\nNationwide retail & rural dealer network access\nStrategic corporate venture co-investment', 'Corporate Partnership Inquiry →', 'contact.php?category=Support%20Organisation', 3, 'Active'),
('Hardware & Agronomy Sensor Workshop', 'tech', 'Deep-Tech', 'fa-microchip', 'Dedicated lab facilities in Gulshan for rapid hardware iteration: SMD soldering, LoRaWAN gateways, 3D casing prototyping, and IP67 weather-proofing.', 'Multi-sensor calibration benches (NPK, soil pH)\nLow-power micro-controller inventory\nCloud API telemetry test harness', 'Book Prototyping Bench →', 'contact.php?category=Entrepreneur', 4, 'Active'),
('Biosafety & Regulatory Sandbox', 'regulatory', 'Compliance', 'fa-stamp', 'Navigating agricultural approvals in South Asia can take years. Our institutional sandbox fast-tracks phytosanitary, seed registration, and bio-fertilizer certifications.', 'Direct liaison with DAE, BARC & BARI\nStandardized bio-safety & toxicity validation\nIntellectual Property & patent filings clinic', 'Consult Regulatory Desk →', 'contact.php?category=Mentor', 5, 'Active'),
('Decentralized Solar Cold Storage', 'field', 'Infrastructure', 'fa-snowflake', 'Testing and scaling micro-cold room solutions at farm-gate collection centers to tackle the 30-35% post-harvest spoilage crisis across perishables.', 'Solar thermal & phase-change cooling tests\nFirst-mile aggregation logistics algorithms\nExport-grade packaging & ripening chambers', 'Test Cold Storage Unit →', 'contact.php?category=Entrepreneur', 6, 'Active')
ON DUPLICATE KEY UPDATE `title` = VALUES(`title`);

-- Dynamic Network Partners
INSERT INTO `network_partners`
(`name`, `category`, `badge`, `location`, `tagline`, `description`, `icon`, `meta_label`, `link_text`, `link_url`, `sort_order`, `status`)
VALUES
('KrishiSense IoT', 'founder', 'Portfolio Startup', 'Bogura & Dhaka', 'Precision Soil & Irrigation Tech', 'Deployed solar IoT sensors across 4,200 acres in Bogura and Rajshahi, reducing smallholder nitrogen fertilizer overuse by 28% and water waste by 35%.', 'fa-seedling', 'Cohort 02 Alum', 'Connect →', 'contact.php?category=Entrepreneur', 1, 'Active'),
('Bengal Delta Capital', 'investor', 'Syndicate Partner', 'Dhaka & Singapore', 'Seed & Series A Venture Fund', 'A $20M early-stage fund backing climate-smart agriculture, digital input distribution marketplaces, and cold chain logistics across Bangladesh.', 'fa-landmark', 'Co-Investment Lead', 'Syndicate Deals →', 'contact.php?category=Investor', 2, 'Active'),
('BAU Research Institute', 'academic', 'Research Anchor', 'Mymensingh', 'Agronomy & Soil Sciences', 'Collaborating on academic lab commercialization, saline-tolerant rice germplasm trials, and doctoral mentor residencies for deep-tech agri founders.', 'fa-microscope', 'Founding Science Partner', 'Academic R&D →', 'contact.php?category=Mentor', 3, 'Active'),
('PRAN-RFL Agro', 'corporate', 'Corporate Partner', 'Nationwide', 'Processing & Rural Procurement', 'Providing our accelerated startups with contracted corporate off-take agreements, raw produce sourcing channels, and nationwide distribution integration.', 'fa-truck-fast', 'Off-Take Offerer', 'Partner Terms →', 'contact.php?category=Support%20Organisation', 4, 'Active'),
('Wageningen Agri Partners', 'academic', 'Global Knowledge', 'Netherlands', 'Controlled Environment & Post-Harvest', 'Global knowledge exchange bringing Dutch protected greenhouse engineering and biological pest management to Bangladesh horticulture clusters.', 'fa-dna', 'Bilateral Knowledge Link', 'Exchange Info →', 'contact.php?category=Support%20Organisation', 5, 'Active'),
('Startup Bangladesh Ltd.', 'impact', 'Sovereign VC', 'Agargaon, Dhaka', 'ICT Division Sovereign Fund', 'Co-sponsoring catalytic seed rounds and providing regulatory sandboxing for digital financial inclusion and drone spray licensing in agriculture.', 'fa-building-columns', 'Policy & Co-Investment', 'Government Grants →', 'contact.php?category=Support%20Organisation', 6, 'Active')
ON DUPLICATE KEY UPDATE `name` = VALUES(`name`);

-- Sample Submissions
INSERT INTO `hub_submissions` 
(`full_name`, `email`, `phone`, `organization`, `category`, `sub_interest`, `pitch_url`, `message`, `status`, `created_at`) 
VALUES
('Tanvir Ahmed', 'tanvir.ahmed@krishisense.bd', '+880 1712-345678', 'KrishiSense IoT Ltd.', 'Entrepreneur', 'AgriTech Ignite Accelerator', 'https://pitch.krishisense.bd/deck.pdf', 'We have developed a solar-powered soil moisture and automated drip irrigation sensor for rice and potato farmers in Bogura and Rajshahi. Looking for pre-seed acceleration and mentorship.', 'Under Review', NOW() - INTERVAL 2 DAY),
('Farhana Rahman', 'f.rahman@bengalventures.vc', '+880 1819-876543', 'Bengal Delta Capital', 'Investor', 'Syndicate Deal Flow', 'https://bengalventures.vc', 'Interested in joining your angel syndicate and co-investing in Series Seed AgriTech ventures with strong rural distribution and climate resilience models.', 'Shortlisted', NOW() - INTERVAL 1 DAY),
('Dr. Michael Jansen', 'm.jansen@agroinnovate-nl.org', '+31 20 555 0192', 'Wageningen Agro Partners', 'Support Organisation', 'Institutional Partnership', 'https://agroinnovate-nl.org', 'We are looking to collaborate with NextGen Farming Incubation Hub to deploy Dutch greenhouse precision tech trials across Mymensingh horticulture clusters.', 'Contacted', NOW() - INTERVAL 5 HOUR),
('Prof. A. S. M. Kamal', 'asm.kamal@bau.edu.bd', '+880 1911-223344', 'Bangladesh Agricultural University', 'Mentor', 'Ag-Bio Mentor Residency', 'https://bau.edu.bd/faculty/kamal', 'Offering 20+ years of domain expertise in drought-resistant seed genomics and organic bio-fertilizer scaling to mentor early-stage cohort founders.', 'New', NOW() - INTERVAL 30 MINUTE);

