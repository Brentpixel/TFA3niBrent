# IT0049 Technical Summative Assessment 3: Forms, Validation, and File Upload

**Course:** IT0049 — Web Development with Framework  
**Project:** CodeIgniter 4 POS System — Making It Editable  
**Developer:** Brent Verdera

---

## About the Project

A web-based **Point-of-Sale (POS) system** built with **CodeIgniter 4 (PHP)** and **MySQL/MariaDB**.  
TFA3 extends the TFA2 project by adding full CRUD forms, server-side validation, and a secure
profile-picture upload feature for users. The design is a minimalist dark interface consistent
across all pages.

---

## Features

### TFA1/TFA2 (carried forward)
- **Dynamic homepage** — tasks filtered by Asia/Manila date
- **All Tasks listing** — full task list ordered by date
- **Profile page** — demo user fetched from database
- **About page** — static developer/project info

### TFA3 (new in this submission)
- **Customer CRUD** — list, add, and edit customers with validation
- **User CRUD** — list, add, and edit users with validation and unique-username check
- **Avatar upload** — JPG/PNG, max 2 MB, stored in `public/uploads/avatars/`, filename saved to DB
- **Validation errors** — shown inline and summarised; entered values preserved on redirect
- **Flash messages** — success confirmation after add or update
- **Responsive dark UI** — no emojis, consistent across all pages

---

## Database Schema

### `customers` Table
```sql
CREATE TABLE customers (
  id         INT(11)      NOT NULL AUTO_INCREMENT PRIMARY KEY,
  full_name  VARCHAR(100) NOT NULL,
  email      VARCHAR(100) NOT NULL,
  phone      VARCHAR(20)  DEFAULT NULL,
  created_at DATETIME     NOT NULL
);
```

### `users` Table (TFA3: `avatar` and `email` columns added)
```sql
CREATE TABLE users (
  id         INT(11)      NOT NULL AUTO_INCREMENT PRIMARY KEY,
  username   VARCHAR(50)  NOT NULL UNIQUE,
  full_name  VARCHAR(100) NOT NULL,
  avatar     VARCHAR(100) DEFAULT NULL,   -- filename only, e.g. "avatar_1_abc.jpg"
  email      VARCHAR(100) DEFAULT NULL,
  created_at DATETIME     NOT NULL
);
```

> **avatar column note:** Added by migration `2026-09-29-000003_AddAvatarToUsers.php`.
> Stores **only the filename**, not a full path.  
> The file itself lives at `public/uploads/avatars/<filename>`.

---

## Local Setup Instructions

### Prerequisites
- **XAMPP** with PHP 8.1+ and MySQL/MariaDB enabled
- **Composer** (dependencies already included in the `vendor/` directory)

---

### Step 1 — Start XAMPP Services

Open XAMPP Control Panel and **Start** both **Apache** and **MySQL**.

---

### Step 2 — Place the Project

Copy or clone this folder to:
```
C:\xampp\htdocs\studinfo\TFA3codigniter\
```

---

### Step 3 — Create the Database

In **phpMyAdmin** (`http://localhost/phpmyadmin`) or via MySQL CLI:

```sql
CREATE DATABASE codeigniter_pos
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;
```

---

### Step 4 — Import the Database

**Option A — Import the SQL file (recommended for a fresh start):**

1. Open phpMyAdmin and select the `codeigniter_pos` database.
2. Click **Import** and choose `database/codeigniter_pos.sql`.
3. Click **Go**.

> This file includes the `customers` and `users` tables, seed data, the CodeIgniter
> `migrations` tracking table, and the `avatar` column already applied.

**Option B — Run migrations from the terminal:**

```bash
cd C:\xampp\htdocs\studinfo\TFA3codigniter
C:\xampp\php\php.exe spark migrate
C:\xampp\php\php.exe spark db:seed DatabaseSeeder
```

---

### Step 5 — Configure the Environment

Open `.env` in the project root and verify:

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

> **Important:** The database name in `.env` must match the database you created in Step 3.
> If you use XAMPP's default root with no password, leave `password` blank.

---

### Step 6 — Upload Directory

The avatar upload directory must exist and be writable. It is included in this repository:

```
public/uploads/avatars/
```

If it is missing, create it manually:

```bash
mkdir C:\xampp\htdocs\studinfo\TFA3codigniter\public\uploads\avatars
```

The `.htaccess` file at `public/uploads/.htaccess` blocks PHP execution inside the upload folder for security.

---

### Step 7 — Start the Development Server

```bash
cd C:\xampp\htdocs\studinfo\TFA3codigniter
C:\xampp\php\php.exe spark serve
```

Then open your browser at: **http://localhost:8080**

> Alternatively, serve through Apache: **http://localhost/studinfo/TFA3codigniter/public/**

---

## Route Summary

| Method | Route | Controller Action | Description |
|--------|-------|-------------------|-------------|
| GET | `/` | `Home::index` | Today's tasks (Asia/Manila) |
| GET | `/tasks` | `Tasks::index` | All tasks |
| GET | `/profile` | `Profile::index` | Demo user profile |
| GET | `/about` | `About::index` | Project info |
| GET | `/customers` | `Customers::index` | Customer list |
| GET | `/customers/new` | `Customers::newForm` | New customer form |
| POST | `/customers/new` | `Customers::create` | Save new customer |
| GET | `/customers/:id/edit` | `Customers::edit` | Edit customer form |
| POST | `/customers/:id/edit` | `Customers::update` | Save customer edits |
| GET | `/users` | `Users::index` | User list with avatars |
| GET | `/users/new` | `Users::newForm` | New user form |
| POST | `/users/new` | `Users::create` | Save new user |
| GET | `/users/:id/edit` | `Users::edit` | Edit user form (with avatar upload) |
| POST | `/users/:id/edit` | `Users::update` | Save user edits + avatar |

---

## Avatar Upload Specifications

| Property | Value |
|----------|-------|
| Accepted types | `image/jpeg`, `image/png` |
| Maximum size | 2 MB (2,097,152 bytes) |
| Validation | MIME type checked from file content (not extension) |
| Filename | `avatar_{id}_{8-byte-hex}.{ext}` — safe and unique |
| Storage | `public/uploads/avatars/` |
| Database column | `users.avatar` — filename only |
| Old file | Deleted automatically when replaced |

---

## Validation Rules

### Customer Form
| Field | Rules |
|-------|-------|
| Full Name | Required, min 2 chars, max 100 chars |
| Email | Required, valid email format, max 100 chars |
| Phone | Optional, max 20 chars |

### User Form
| Field | Rules |
|-------|-------|
| Username | Required, 3–50 chars, letters/numbers/underscore/dash, **unique** |
| Full Name | Required, min 2 chars, max 100 chars |
| Email | Optional, valid email format, max 100 chars |

> On **edit**, if the username is unchanged, the unique check is skipped for that record.

---

## Database Export Information

The file `database/codeigniter_pos.sql` is a complete, importable SQL dump that includes:
- Table structures for `customers` and `users` (with `avatar` column)
- Five seed rows for each table
- The CodeIgniter `migrations` tracking table
- All indexes and AUTO_INCREMENT values

---

## Known Manual Steps

1. **Create the database** — must be done manually in phpMyAdmin or MySQL CLI before importing.
2. **Run the migration for avatar** (`2026-09-29-000003_AddAvatarToUsers.php`) — already included in
   the SQL import file; only needed if you used Option B (migrations) and started from an older dump
   without the `avatar` column.
3. **GitHub push** — not performed. Push manually with `git push` after your own review.
