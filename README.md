# IT Help Desk Ticketing System

A web-based IT Help Desk application built with **PHP 8.1 / Laravel 10 / MySQL**, served on **XAMPP** with a **Bootstrap 5** admin interface.

Built as a BIT final project for the University of Colombo School of Computing (2025).

---

## Prerequisites — install these before you begin

| Tool | Download |
|------|----------|
| XAMPP (PHP 8.1+, Apache, MySQL) | https://www.apachefriends.org |
| Composer | https://getcomposer.org/download |
| Node.js + npm (optional — only needed to compile CSS/JS) | https://nodejs.org |

After installing XAMPP, open the **XAMPP Control Panel** and **Start** both **Apache** and **MySQL**.

---

## Step-by-Step Setup

Open a terminal (Command Prompt or PowerShell) and follow these steps **in order**.

### 1 — Open the XAMPP Control Panel
Start **Apache** and **MySQL** from the XAMPP Control Panel.

### 2 — Create the database
1. Open your browser and go to `http://localhost/phpmyadmin`
2. Click **New** (left sidebar)
3. Enter database name: `it_helpdesk`
4. Collation: `utf8mb4_unicode_ci`
5. Click **Create**

### 3 — Navigate to the project folder
```bash
cd "d:\Demo Projects\Other Projects\IT HELP Desk Ticketing System\helpdesk"
```

### 4 — Install PHP dependencies
```bash
composer install
```
This downloads Laravel and all packages into the `vendor/` folder. It may take a few minutes the first time.

### 5 — Create your environment file
```bash
copy .env.example .env
```
On Mac/Linux:
```bash
cp .env.example .env
```

### 6 — Generate the application key
```bash
php artisan key:generate
```

### 7 — Configure the database connection
Open `.env` in any text editor (Notepad, VS Code, PhpStorm) and confirm these lines:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=it_helpdesk
DB_USERNAME=root
DB_PASSWORD=
```
> If your XAMPP MySQL has a password, fill it in on `DB_PASSWORD=`. Most XAMPP installations have no password for the root user.

### 8 — Run the database migrations and seeders
```bash
php artisan migrate --seed
```
This creates all 11 tables and seeds:
- Three roles (User, IT Staff, Admin)
- Five categories (Network, Security, Application, Hardware, General)
- Four priorities (Low, Medium, High, Urgent) with SLA policies
- **Three demo accounts** (see below)

### 9 — Create the storage symlink (for file attachments)
```bash
php artisan storage:link
```

### 10 — Start the development server
```bash
php artisan serve
```
Open your browser and go to: **http://localhost:8000**

---

## Demo Accounts

| Role | Email | Password |
|------|-------|----------|
| **User** | user@helpdesk.test | password |
| **IT Staff** | staff@helpdesk.test | password |
| **Admin** | admin@helpdesk.test | password |

Log in with each account to explore the different role interfaces.

---

## Running the SLA Breach Checker

The SLA countdown and breach detection requires a background command to run periodically.

**During development** — run this in a second terminal window (keep it running):
```bash
php artisan schedule:work
```

**Run once manually** (useful for demos and testing):
```bash
php artisan tickets:check-sla
```

---

## Email Notifications

By default, `MAIL_MAILER=log` in `.env`, which means:
- **No real emails are sent** — no SMTP account needed.
- Email content is written to `storage/logs/laravel.log` instead.
- In-app notifications (the bell icon) always work regardless.

To see the logged emails, open `storage/logs/laravel.log` and search for `To:`.

---

## Project Structure

```
helpdesk/
├── app/
│   ├── Console/Commands/    CheckSlaBreaches.php — SLA Artisan command
│   ├── Http/
│   │   ├── Controllers/     One controller per feature
│   │   ├── Middleware/      CheckRole.php — role gate
│   │   └── Requests/        Form Requests for validation
│   ├── Models/              Eloquent models (Ticket, User, etc.)
│   ├── Policies/            TicketPolicy — authorisation rules
│   └── Services/            AuditLogger, NotificationService
├── database/
│   ├── migrations/          11 migration files (3NF schema)
│   └── seeders/             DatabaseSeeder + individual seeders
├── resources/views/
│   ├── layouts/app.blade.php   Master Bootstrap layout
│   ├── partials/               Sidebar, topbar, footer
│   ├── tickets/                Ticket list, create, detail views
│   ├── categories/             Category management
│   ├── priorities/             Priority management
│   ├── reports/                Three report views
│   ├── audit/                  Audit log viewer
│   ├── knowledge-base/         KB article views
│   └── notifications/          Notification list
└── routes/web.php              All application routes
```

---

## Features

| # | Feature | Who can use it |
|---|---------|----------------|
| 1 | Register / Login / Logout | Everyone |
| 2 | Role-based access (User / IT Staff / Admin) | Enforced by middleware |
| 3 | Submit a ticket (with file attachment) | Users, Staff, Admin |
| 4 | Staff raises ticket on behalf of a user | IT Staff, Admin |
| 5 | Ticket status lifecycle (Open→Resolved→Closed) | IT Staff, Admin |
| 6 | Ticket assignment & self-assign | IT Staff, Admin |
| 7 | Internal notes (hidden from requesting user) | IT Staff, Admin |
| 8 | SLA countdown per priority + breach detection | Automatic (Artisan command) |
| 9 | SLA escalation notifications | Automatic on breach |
| 10 | In-app notifications + email log | All logged-in users |
| 11 | Dashboard stats (role-scoped) | All logged-in users |
| 12 | Reports (tickets per user, technician performance, problem areas) | Admin |
| 13 | Append-only Audit Log | Admin |
| 14 | Knowledge Base (browse, search, author articles, link to resolution) | All / Staff for authoring |
| 15 | Manage categories & priorities | Admin |

---

## Troubleshooting

**`php` is not recognized** — PHP is not on your PATH. In XAMPP, go to *Config → Environment variables* and add `C:\xampp\php` to PATH.

**`composer` is not recognized** — Composer installer wasn't added to PATH. Re-run the Composer installer and tick *"Add to PATH"*.

**"Access denied for user 'root'"** — Check `DB_PASSWORD` in `.env`. Try leaving it empty or set it to your XAMPP MySQL password.

**500 Server Error** — Run `php artisan config:clear` then `php artisan cache:clear`, then try again.

**File uploads not working** — Run `php artisan storage:link` if you haven't already.
