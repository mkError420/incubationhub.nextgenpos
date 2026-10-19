# 🚀 cPanel Deployment Guide for NextGen Farming Incubation Hub

This step-by-step guide explains how to upload, configure, and launch the **NextGen Farming Incubation Hub** website on any standard cPanel web hosting account (Namecheap, Hostinger, Bluehost, cPanel Shared/VPS hosting).

---

## 📋 Pre-Deployment Checklist

Before starting, make sure you have:
1. Your **cPanel Login URL**, **Username**, and **Password**.
2. A registered domain or subdomain pointing to your hosting account (e.g. `https://yourdomain.com`).
3. The project files ready for upload (or the single `cpanel_ready_upload.zip` file).

---

## 🗂️ Overview of Directory Structure in cPanel

When uploaded to your cPanel's **`public_html`** (or your addon domain folder), the structure will look like this:

```
public_html/
├── .htaccess                     # Apache rules, clean URLs, security & caching (Do not delete!)
├── config.php                    # Your cPanel database credentials
├── index.php                     # Homepage
├── about.php                     # About Us page
├── programs.php                  # Programs & Accelerators page
├── contact.php                   # Contact & Application page
├── submissions.php               # Live Submissions & Database Review Portal
├── 404.php                       # Custom branded 404 error page
├── schema.sql                    # Database table & seed data script
├── robots.txt                    # Search engine crawler instructions
├── sitemap.xml                   # XML sitemap for SEO
├── api/
│   └── submit.php                # AJAX form submission endpoint
├── assets/
│   ├── css/
│   │   └── style.css             # Custom styles & design tokens
│   ├── js/
│   │   └── main.js               # jQuery interactions & AJAX handler
│   └── data_fallback.json        # Auto-created cache buffer
└── includes/
    ├── header.php                # Shared header, navigation & CDNs
    ├── footer.php                # Shared footer & scripts
    └── db.php                    # PDO database connector
```

---

## 🛠️ Step-by-Step Deployment Instructions

### STEP 1: Upload Files to cPanel

#### **Recommended Method: Upload via ZIP Archive**
1. Log into your **cPanel** dashboard.
2. In the **Files** section, click **File Manager**.
3. Double-click to open **`public_html`** (or your addon/subdomain directory).
   > *Note: If `public_html` contains a default `default.html` or `index.html` file from the host, delete or rename it.*
4. Click the **Upload** button at the top toolbar.
5. Drag and drop the **`cpanel_ready_upload.zip`** file.
6. Once the progress bar reaches 100% (green), return to File Manager.
7. Right-click on **`cpanel_ready_upload.zip`** and choose **Extract**.
8. Confirm the extraction path is `/public_html` and click **Extract Files**.
9. After extracting, you may delete the `.zip` file to save space.

> **⚠️ Important: Show Hidden Files in cPanel File Manager**  
> If you don't see `.htaccess` after extraction:
> - Click the **Settings** button in the top right corner of cPanel File Manager.
> - Check the box **"Show Hidden Files (dotfiles)"**.
> - Click **Save**.

---

### STEP 2: Create MySQL Database & User in cPanel

1. In your cPanel home, scroll to the **Databases** section and click **MySQL® Databases** (or **MySQL® Database Wizard**).
2. **Create New Database**:
   - Type a database name, e.g. `nextgen_hub`.
   - Click **Create Database**.
   - *Notice that cPanel prefixes your username*: e.g. `youruser_nextgen_hub`. Write this down!
3. **Create Database User**:
   - Scroll down to **Add New User**.
   - Enter a username, e.g. `hub_user` (Full user will be `youruser_hub_user`).
   - Use the **Password Generator** to create a strong password.
   - Copy this password to a safe place!
   - Click **Create User**.
4. **Link User to Database (Crucial Step)**:
   - Scroll to **Add User To Database**.
   - Select your user: `youruser_hub_user`.
   - Select your database: `youruser_nextgen_hub`.
   - Click **Add**.
   - On the next screen, check the box **ALL PRIVILEGES**.
   - Scroll down and click **Make Changes**.

---

### STEP 3: Import `schema.sql` via phpMyAdmin

1. Return to the cPanel main screen.
2. Under the **Databases** section, click **phpMyAdmin**.
3. On the left sidebar of phpMyAdmin, click on your newly created database (e.g. `youruser_nextgen_hub`).
4. Click the **Import** tab on the top menu.
5. Under **File to import**, click **Choose File** / **Browse**.
6. Select the file **`schema.sql`** (from your project files or downloaded from `public_html`).
7. Leave all other settings at default and scroll to the bottom.
8. Click **Import** (or **Go**).
9. You will see a green success message: *“Import has been successfully finished, queries executed.”*
10. The table **`hub_submissions`** will now appear in your database.

---

### STEP 4: Verify Database Credentials in `config.php`

The database configuration in **`config.php`** is already pre-configured for your account:

```php
// 1. Database Host (In 99% of cPanel hosts, this is 'localhost')
defined('DB_HOST') or define('DB_HOST', 'localhost');

// 2. Database Port (Default is 3306)
defined('DB_PORT') or define('DB_PORT', '3306');

// 3. Database Name
defined('DB_NAME') or define('DB_NAME', 'nextgenp_incubation');

// 4. Database User
defined('DB_USER') or define('DB_USER', 'nextgenp_incubation');

// 5. Database Password
defined('DB_PASS') or define('DB_PASS', 'KdfTxkeQF?h~u9L;');

// 6. Base URL (Optional, e.g. 'https://yourdomain.com')
defined('BASE_URL') or define('BASE_URL', '');

// 7. Environment ('production' hides raw errors from visitors)
defined('APP_ENV') or define('APP_ENV', 'production');
```

If you uploaded the pre-built `cpanel_ready_upload.zip`, this step is already done for you!

---

### STEP 5: Verify Permissions

Standard cPanel security requires:
- **Directories**: `755` permissions
- **Files**: `644` permissions
- The `assets/` directory must be writable (`755`) so the system can store the fallback buffer if MySQL is ever undergoing maintenance.

cPanel sets these permissions automatically upon extraction.

---

### STEP 6: Test & Verify

1. Open your browser and navigate to your website: `https://yourdomain.com`.
2. Test the navigation across all pages:
   - Homepage: `https://yourdomain.com/`
   - About Us: `https://yourdomain.com/about.php` (or `https://yourdomain.com/about`)
   - Programs: `https://yourdomain.com/programs.php` (or `https://yourdomain.com/programs`)
   - Contact / Apply: `https://yourdomain.com/contact.php` (or `https://yourdomain.com/contact`)
3. **Submit a Test Application**:
   - Go to `contact.php`.
   - Select a category (e.g., *Entrepreneur* or *Investor*).
   - Fill in your test details and click **Submit Application / Inquiry**.
   - You should see the checkmark confirmation banner and a toast notification.
4. **Verify in Submissions Portal**:
   - Navigate to `https://yourdomain.com/submissions.php`.
   - Verify that your newly submitted record is displayed.
   - Check the top right badge: it will display **"MySQL Connected"** with a glowing green indicator!

---

### STEP 7: Access & Maintain Content via the Admin CMS Portal

1. Navigate to: **`https://yourdomain.com/admin/`**
2. **Login Credentials**:
   - **Username**: `admin`
   - **Password**: `admin123`
3. What you can maintain dynamically:
   - **Site Content CMS (`admin/content.php`)**: Edit Hero title, subtitle, announcement banner, and Track Record numbers/descriptions. All edits reflect live immediately.
   - **Programs & Cohorts (`admin/programs.php`)**: Add, edit, or delete accelerator tracks.
   - **Submissions & Leads (`admin/submissions.php`)**: Review incoming applicants, filter by category/status, add private evaluation notes, and export to CSV.
   - **Security (`admin/settings.php`)**: Change the default admin password and check database health.

---

## 🔍 Troubleshooting & FAQs

### Q1: The Submissions portal says "Adaptive Cache Storage" instead of "MySQL Connected"?
- **Cause**: The database name, user, or password in `config.php` does not match your cPanel MySQL settings.
- **Fix**: Check that you included your cPanel username prefix (e.g. `youruser_nextgen_hub`, NOT just `nextgen_hub`). Also ensure you added the user to the database with **ALL PRIVILEGES**.

### Q2: Clean URLs (e.g. `/about`) give a 404 error?
- **Cause**: The `.htaccess` file was either not uploaded or hidden during extraction.
- **Fix**: In cPanel File Manager -> Settings -> Check **"Show Hidden Files (dotfiles)"**. Ensure `.htaccess` exists in `public_html`. Apache `mod_rewrite` is enabled by default on all cPanel servers.

### Q3: How do I enable Free SSL (HTTPS)?
- In cPanel, find **SSL/TLS Status** or **Let's Encrypt SSL**.
- Click **Run AutoSSL** on your domain. cPanel will issue and renew your free SSL certificate automatically.
