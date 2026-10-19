# NextGen Farming Incubation Hub

> **Connecting Entrepreneur Ecosystem builders in Bangladesh and Emerging Markets.**

A clean, responsive, and professional multi-page web platform and venture incubation management system built for high-growth AgriTech startups, venture capital syndicates, developmental institutions, and agronomic mentors.

---

## 🌾 Project Architecture & Structure

```
nextgen-service/
├── assets/
│   ├── css/
│   │   └── style.css            # Custom CSS design tokens, glassmorphism, animations
│   ├── js/
│   │   └── main.js              # jQuery DOM, counters, dynamic category logic & AJAX handler
│   └── data_fallback.json       # Adaptive fallback file cache if MySQL is offline
├── includes/
│   ├── header.php               # Shared navigation, Google Fonts, Tailwind & FontAwesome CDNs
│   ├── footer.php               # Shared multi-column footer, newsletter form, scripts
│   └── db.php                   # Robust PDO MySQL connector with error handling & fallback
├── api/
│   └── submit.php               # RESTful endpoint handling AJAX POST submissions with CSRF/validation
├── index.php                    # Home: Hero, metrics, stakeholder pathways, venture showcases
├── about.php                    # About Us: Mission, Bangladesh deltaic context, living labs, leadership
├── programs.php                 # Programs & Cohorts: Dynamic accelerator tracks, curriculum, terms
├── offerings.php                # Core Offerings: Living testbeds, capital syndicates, off-take, hardware lab
├── networking.php               # Networking: Ecosystem partner directory, regional nodes, roundtables
├── contact.php                  # Interactive application form with dynamic category fields
├── submissions.php              # Real-time MySQL pipeline review portal & filtering dashboard
├── schema.sql                   # Production-ready MySQL schema with seed records
└── README.md                    # Setup, architecture & deployment documentation
```

---

## 🛠️ Technology Stack & Environment
- **Frontend**: HTML5, Tailwind CSS (via CDN), Custom Modern CSS, FontAwesome 6, and **jQuery 3.7.1** for dynamic DOM manipulation, number counter animations, category conditional fields, and AJAX form submissions.
- **Backend**: **PHP 7.4+ / 8.0+ / 8.3+** with modular include architecture (`header.php`, `footer.php`, `db.php`).
- **Database**: **MySQL 5.7+ / 8.0+ / MariaDB 10.3+** with PDO prepared statements and UTF-8 multibyte charset support.
- **Resilience Engine**: Built-in adaptive fallback storage ensures all form submissions and dashboard queries function smoothly even before MySQL database credentials are configured!

---

## 🚀 Quick Start Guide

### 1. Database Setup
Create and import the database into your MySQL instance (e.g. via phpMyAdmin, MySQL Workbench, or CLI):
```bash
mysql -u root -p < schema.sql
```
*Database name:* `nextgen_hub`  
*Table created:* `hub_submissions`, `newsletter_subscribers`

### 2. Configure Database Credentials (Optional)
If your MySQL credentials differ from the defaults (`127.0.0.1`, user `root`, no password), update them in `includes/db.php`:
```php
defined('DB_HOST') or define('DB_HOST', '127.0.0.1');
defined('DB_PORT') or define('DB_PORT', '3306');
defined('DB_NAME') or define('DB_NAME', 'nextgen_hub');
defined('DB_USER') or define('DB_USER', 'root');
defined('DB_PASS') or define('DB_PASS', '');
```
Or set them as environment variables (`DB_HOST`, `DB_NAME`, `DB_USER`, `DB_PASS`).

### 3. Run Locally

#### Using PHP Built-in Server:
```bash
cd "c:\New folder\All POS live done\nextgen\nextgen-service"
php -S localhost:8000
```
Open your browser at: **`http://localhost:8000`**

#### Using XAMPP / WAMP / Laragon:
Copy or symlink the folder into `htdocs` or `www` directory:
- URL: `http://localhost/nextgen-service/`

---

## 🌟 Key Features & Page Breakdown

### 1. Home (`index.php`)
- **Hero Section**:
  - Exact headline: *"NextGen Farming Incubation Hub"*
  - Exact subtitle: *"Connecting Entrepreneur Ecosystem builders in Bangladesh and Emerging Markets."*
  - Live cohort badge, venture metrics snapshot card, and quick application buttons.
- **The Track Record Section (3-Column Grid with Animated Counters)**:
  - **Community Members**: 5,200+ grassroots innovators, researchers, and rural leaders.
  - **Business Ventures**: 128+ accelerated ventures with $28.5M+ follow-on funding.
  - **Program Organizers**: 48+ institutional partners, universities, and multilateral agencies.
- **Core Offerings: Entrepreneurs & Investors (2-Column Grid)**:
  - Offerings for Agri-Founders (Fundraising, 16-week cohorts, ag-bio mentorship, living farmlands).
  - Offerings for Investors (Curated deal flow, angel syndicates, due diligence, ESG carbon tracking).
- **Core Offerings: Organisation & Mentors (2-Column Grid)**:
  - Offerings for Support Organisations (Capacity building, turnkey frameworks, CSR project delivery).
  - Offerings for Mentors (Domain residencies, advisory board placements, masterclasses).
- **Trusted Nationally & Globally Across Emerging Market**:
  - **Development & Corporate Leaders** (Ministry of Agriculture, BRAC, PRAN-RFL).
  - **International Cooperation** (Dutch Wageningen Consortium, UNDP Labs, JICA).
  - **Finance & Sustainability** (Acumen Fund, IDCOL Green Financing, Bangladesh Angels).

### 2. About Us (`about.php`)
- Detailed overview of Bangladesh as the deltaic testbed for emerging market agriculture.
- The 4 core bottlenecks addressed: post-harvest loss, supply chain fragmentation, precision sensing, and agri-fintech.
- 4 strategic pillars and leadership team profiles.

### 3. Programs (`programs.php`)
- In-depth program cards for **AgriTech Ignite Accelerator** (Pre-seed), **HarvestScale Growth Cohort** (Series Seed/A), **ClimateSmart Bio-Residency**, and **Women in AgriTech Fellowship**.
- 16-week incubation curriculum architecture across 4 phases.
- Interactive FAQ accordion built with jQuery.

### 4. Contact / Apply (`contact.php`)
- Interactive stakeholder category selector (Entrepreneur, Investor, Support Organisation, Mentor).
- Dynamic contextual fields: adjusts sub-interest dropdown, URL labels (Pitch deck vs. Portfolio vs. Profile), and custom placeholder prompts based on selected category.
- Seamless AJAX submission via jQuery to `api/submit.php` with real-time feedback toast notifications and submission ID generation.

### 5. Live Pipeline Portal (`submissions.php`)
- Administrative verification table listing leads captured into `hub_submissions`.
- Category filtering tabs and database connection health badge.

---

## 🔒 Security & Code Standards
- Strict PDO prepared statements preventing SQL injection attacks.
- Input sanitization and honeypot field to block automated spam bots.
- Responsive design tailored for mobile phones, tablets, and high-resolution displays.
- Semantic HTML5 structure with OpenGraph social metadata.
