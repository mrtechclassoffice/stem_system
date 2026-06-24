# STEM Academy LMS — Laravel & Blade Version

A full-stack, server-rendered Learning Management System (LMS) designed for once-a-week classes, supporting an **Admin (Teacher)** portal and a **Student** portal.

Rebuilt entirely using **Laravel 11**, **Blade templates**, **Eloquent ORM**, and **Tailwind CSS**.

---

## 🌟 Key Features

### 🔐 Authentication & Security
- **Role-based access control** (Admin vs. Student) enforced via Laravel custom middleware.
- Secure logins backed by Laravel's built-in authentication services.
- **Login Alerts:** Automatic SMTP email notifications dispatched to both the student and admin on successful logins to audit account security.

### 👨‍🏫 Admin (Teacher) Dashboard
- **Student CRUD Directory:** Add, update, view, and remove student accounts, complete with profile picture uploads.
- **Direct Material Uploads:** Upload PDFs and assignment worksheets directly to the server's local public storage (segregated by student).
- **Google Drive Media Gallery:** Share weekly class photos and session recording links by pasting Google Drive links (configured to "Anyone with the link can view") without hosting large video files directly.
- **Tuition Payment Board:** Log monthly tuition payments, track invoice statuses (`paid`, `unpaid`, `pending`), and append remarks.
- **Auto-provisioning Emails:** On creation, students receive a Welcome Email containing their system login details and access links.

### 🎓 Student Portal
- **Dashboard Hub:** View class resources, media links count, and current month's tuition fee status.
- **My Materials:** View and download PDFs, guides, and assignments uploaded by the instructor.
- **Media Gallery:** Access a gallery card interface displaying class photos and video recordings with single-click access to the Google Drive links.
- **Submissions Desk:** Upload completed homework assignments with custom comments, with options to delete or replace them.
- **Academic Profile & Ledger:** Check demographic details, guardian information, and see a historical billing ledger of tuition fee payments.

---

## 🛠️ Technology Stack
- **Framework:** Laravel 11 (PHP 8.2+)
- **Database:** MySQL / MariaDB (or SQLite/PostgreSQL)
- **Frontend Engine:** Blade Templates, Tailwind CSS (loaded via CDN for instant execution, with optional Vite support)
- **Asset Icons:** Lucide Icons
- **Storage:** Local Filesystem (`storage/app/public`)
- **Email Dispatch:** Laravel Mail (SMTP configuration)

---

## 📁 Directory Structure
```
stem_system/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/         # Admin dashboards, students CRUD, files, payments
│   │   │   ├── Student/       # Student dashboard, downloads, uploads, profile
│   │   │   └── Auth/          # Login/Logout controller
│   │   └── Middleware/        # RedirectIfNotRole (Role restriction)
│   ├── Mail/                  # StudentWelcome, LoginAlert notifications
│   └── Models/                # User, Student, Material, MediaLink, Submission, Payment
├── bootstrap/                 # Application bootstrap & middleware registry
├── config/                    # Config files (database, mail, filesystems, auth)
├── database/
│   ├── migrations/            # Table schemas
│   └── seeders/               # DatabaseSeeder for default Admin creation
├── public/                    # Web root, compiled assets
├── resources/
│   └── views/                 # Blade templates
│       ├── layouts/           # layouts.admin and layouts.student shells
│       ├── admin/             # Admin management layouts
│       ├── student/           # Student portal dashboards & upload forms
│       ├── auth/              # Login screen
│       └── emails/            # HTML Welcome & Alert emails
├── routes/
│   └── web.php                # Core routing system
├── .env.example               # Configuration template
└── composer.json              # Backend dependencies
```

---

## 🚀 Getting Started

Since you are running Windows without pre-configured local command-line tools like PHP or Composer, we recommend installing a local server environment bundle:
* **[Laragon](https://laragon.org/)** (Recommended: includes PHP, Composer, MySQL, Apache, and Git in a single installer)
* **[XAMPP](https://www.apachefriends.org/)**

### 1. Project Configuration
Clone the template `.env.example` to create your active `.env` file:
```bash
cp .env.example .env
```
Open `.env` and fill out:
* **Database config:** `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`.
* **SMTP Mailer:** Add your Gmail/Outlook SMTP username (`MAIL_USERNAME`) and an [App Password](https://myaccount.google.com/apppasswords) (`MAIL_PASSWORD`).

### 2. Dependency Installation
Run the following commands inside your Laragon/XAMPP terminal in the project directory:
```bash
composer install
npm install
```

### 3. Generate Encryption Key & Create Storage Link
Generate the Laravel application key and symlink the storage directory to allow student downloads:
```bash
php artisan key:generate
php artisan storage:link
```

### 4. Run Migrations & Seed Database
Create database tables and provision the default Admin account:
```bash
php artisan migrate --seed
```
*Note: The default administrator credentials are `admin@stem.local` and `Admin@1234` (unless customized in `.env`). Please log in and update these credentials immediately.*

### 5. Start Local Server
Start Laragon's Apache/MySQL services, or run Laravel's internal server:
```bash
php artisan serve
```
Open **`http://localhost:8000`** in your browser to access the portal.

---

## 📄 License
This project is open-source and available under the MIT License.
