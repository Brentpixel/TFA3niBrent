# IT0049 Technical Summative Assessment 4: Who's Allowed In? Sessions and Authentication

**Course:** IT0049 — Web Development with Framework  
**Project:** CodeIgniter 4 POS System — Sessions and Authentication  
**Developer:** Brent Verdera  
**GitHub Repository:** https://github.com/Brentpixel/TFA3niBrent  
**Hosted Application:** `[Insert Hosted Application Link Here]`  

---

## About the Project

A web-based **Point-of-Sale (POS) system** built with **CodeIgniter 4 (PHP)** and **MySQL/MariaDB**.  
TFA4 upgrades the system with a complete **Session-based Authentication and Authorization Architecture**. Unauthenticated guests can view public summary and informational pages, but all access to **Customer Accounts** and **User Management (CRUD)** is strictly protected by an authentication filter (`AuthFilter`).

The project preserves all existing functionality from earlier submissions (TFA1, TFA2, TFA3) including server-side validation, secure avatar image uploads with GD thumbnail cropping, and responsive dark minimalist styling without emojis.

---

## Features

### Public Pages
- **Dynamic Homepage (`/`)** — Tasks filtered by Asia/Manila date with status metrics.
- **All Tasks Listing (`/tasks`)** — Full task list ordered by scheduled date.
- **Profile Page (`/profile`)** — Demo user profile loaded from database.
- **About Page (`/about`)** — Developer and project information.
- **Login Page (`/login`)** — Clean sign-in form with server-side validation and generic invalid-credential messaging.

### Protected Pages (Protected by `AuthFilter`)
- **Customer Accounts (`/customers`)** — Listing, adding (`/customers/new`), and editing (`/customers/(:num)/edit`).
- **User Accounts (`/users`)** — Listing with avatar thumbnails, user creation with password confirmation, and profile editing.
- **Avatar Management** — JPG/PNG image upload (max 2 MB), GD 200×200 center-cropping, automatic old-file cleanup upon replacement.

### TFA4 Authentication & Security Architecture
- **Password Column & Hashing** — Added `password VARCHAR(255)` column via CodeIgniter migration; passwords hashed with `password_hash($raw, PASSWORD_DEFAULT)` using bcrypt.
- **User Creation** — Requires password and confirmation (`matches[password]`, min 8 chars); hashes before insertion; never saves confirmation; never preserves passwords on validation failure.
- **User Editing** — Optional password change; leaving password blank preserves existing hash; entering new password validates confirmation and hashes before updating.
- **Session ID Regeneration** — Prevents session fixation attacks upon successful login.
- **AuthFilter Middleware** — Intercepts all unauthenticated requests to protected customer/user routes, flashing `"Please log in to access this page."` and redirecting to `/login`.
- **POST Logout Workflow** — Destroys authenticated session data, regenerates session ID, and redirects to `/login` with success confirmation.
- **Navigation Controls** — Shows logged-in user's name and Logout button when authenticated; displays Login button when unauthenticated.
- **Protection Against Enumeration** — Displays generic `"Invalid username or password."` on login failure.
- **Browser History & Session Protection** — Session destruction prevents unauthorized back-button access to protected operations.

---

## Demo Login Credentials

For local testing and grading evaluation:

| Account Type | Username | Password | Full Name |
|--------------|----------|----------|-----------|
| **Primary Demo** | `bverdera` | `Password123!` | Brent Verdera |
| **Alternative Demo** | `boykanin` | `Password123!` | Boy Kanin |

*(All seeded user accounts are populated with password `Password123!` via `UserPasswordSeeder`.)*

---

## Protected vs. Public Routes

| Method | Route | Protection | Handler | Description |
|--------|-------|------------|---------|-------------|
| GET | `/` | **Public** | `Home::index` | Today's tasks (Asia/Manila) |
| GET | `/tasks` | **Public** | `Tasks::index` | All tasks |
| GET | `/profile` | **Public** | `Profile::index` | Developer profile |
| GET | `/about` | **Public** | `About::index` | Project specifications |
| GET | `/login` | **Public** | `Auth::login` | Sign-in form |
| POST | `/login` | **Public** | `Auth::processLogin` | Authenticates credentials |
| POST | `/logout` | **Public** | `Auth::logout` | Terminates session & redirects |
| GET | `/logout` | **Public** | `Auth::logout` | Safe fallback logout |
| GET | `/customers` | **Protected (`auth`)** | `Customers::index` | Customer list |
| GET | `/customers/new` | **Protected (`auth`)** | `Customers::newForm` | Add customer form |
| POST | `/customers/new` | **Protected (`auth`)** | `Customers::create` | Save new customer |
| GET | `/customers/:id/edit` | **Protected (`auth`)** | `Customers::edit` | Edit customer form |
| POST | `/customers/:id/edit` | **Protected (`auth`)** | `Customers::update` | Save customer changes |
| GET | `/users` | **Protected (`auth`)** | `Users::index` | User list with avatars |
| GET | `/users/new` | **Protected (`auth`)** | `Users::newForm` | Add user form (with password) |
| POST | `/users/new` | **Protected (`auth`)** | `Users::create` | Save new user & hash password |
| GET | `/users/:id/edit` | **Protected (`auth`)** | `Users::edit` | Edit user form |
| POST | `/users/:id/edit` | **Protected (`auth`)** | `Users::update` | Save user changes |

---

## Software Requirements

- **PHP:** 8.1 or newer (PHP 8.2 recommended)
- **Required PHP Extensions:**
  - `ext-mysqli` (Database connectivity)
  - `ext-gd` (Avatar image thumbnail center-cropping)
  - `ext-intl` (Internationalization support)
  - `ext-mbstring` (Multibyte string handling)
  - `ext-curl` (HTTP requests / automated test suite)
- **Database:** MySQL 5.7+ or MariaDB 10.4+
- **Web Server:** Apache (via XAMPP) or CodeIgniter Spark CLI server

---

## Local Setup Instructions

### Step 1 — Start Services in XAMPP
Open XAMPP Control Panel and start **Apache** and **MySQL**.

### Step 2 — Place the Project Folder
Ensure the project is located at:
```
C:\xampp\htdocs\studinfo\TFA3codigniter
```

### Step 3 — Create Database
In **phpMyAdmin** (`http://localhost/phpmyadmin`) or MySQL CLI:
```sql
CREATE DATABASE codeigniter_pos
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;
```

### Step 4 — Database Setup Options

#### Option A: Import Updated SQL File (Fastest)
Import `database/codeigniter_pos.sql` into `codeigniter_pos`:
```bash
C:\xampp\mysql\bin\mysql.exe -u root codeigniter_pos < database\codeigniter_pos.sql
```
*(This file includes all updated tables, bcrypt-hashed user passwords, and the migrations log table).*

#### Option B: Run Spark Migrations & Seeders
```bash
cd C:\xampp\htdocs\studinfo\TFA3codigniter
C:\xampp\php\php.exe spark migrate
C:\xampp\php\php.exe spark db:seed DatabaseSeeder
```

### Step 5 — Environment Configuration
Check `.env` in the project root:
```env
CI_ENVIRONMENT = development

app.baseURL = 'http://localhost:8080/'

database.default.hostname = localhost
database.default.database = codeigniter_pos
database.default.username = root
database.default.password =
database.default.DBDriver = MySQLi
database.default.DBPrefix =
database.default.port     = 3306
```

### Step 6 — Run the Server
Run CodeIgniter's development server:
```bash
C:\xampp\php\php.exe spark serve --port 8080
```
Open your browser at: **http://localhost:8080**

---

## Automated Verification & Testing

An automated test suite is provided to verify all assessment requirements.

Run the test suite from the project root:
```bash
C:\xampp\php\php.exe tests/test_tfa4_suite.php
```

### Test Coverage Summary:
- **Case A (Logged-Out Access):** Confirms unauthenticated visits to `/customers`, `/customers/new`, `/customers/:id/edit`, `/users`, `/users/new`, and `/users/:id/edit` all redirect (HTTP 302) to `/login` with `"Please log in to access this page."`
- **Case B (Login Validation):** Confirms empty fields produce validation errors; invalid credentials trigger `"Invalid username or password."`; passwords are never echoed back; valid credentials successfully log in and redirect to dashboard; logged-in users visiting `/login` redirect to dashboard.
- **Case C (Logged-In Access):** Confirms authenticated users can access all Customer and User CRUD views; navbar renders user badge and Logout button; Login link is hidden.
- **Case D (Password Handling):** Confirms database stores bcrypt password hashes (`$2y$`); new user creation requires matching password confirmation; passwords are saved hashed; editing user with blank password preserves existing hash; changing password allows sign-in with updated credentials.
- **Case E (Logout Workflow):** Confirms `POST /logout` terminates session; displays `"You have been logged out successfully."`; re-secures all protected routes against session replay.

---

## Security Implementation Highlights

1. **Password Hashing:** Implemented with `password_hash($raw, PASSWORD_DEFAULT)` and verified strictly with `password_verify($raw, $hash)`. Plain-text passwords are never stored or logged.
2. **Session Security:** `session()->regenerate()` is executed upon successful login to prevent session fixation attacks.
3. **Filter Enforcement:** `AuthFilter` guards route endpoints at the routing layer so direct URL access is prevented regardless of client UI state.
4. **Output Escaping:** All user-controlled fields and error outputs are sanitized using CodeIgniter's `esc()` helper.
5. **CSRF Protection:** Form actions across authentication, customer management, and user management include `<?= csrf_field() ?>`.
6. **Upload Hardening:** User avatars are validated by byte inspection MIME type (`image/jpeg`, `image/png`), resized via GD to $200 \times 200$ square thumbnails, and protected against script execution with `public/uploads/.htaccess`.
