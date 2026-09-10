# IT0049 – Web System Technologies
## Technical Formative Assessment: "From Zero to Four Pages: Your First CodeIgniter Application"

---

## 📌 Project Purpose

This project is a simple **Point-of-Sale (POS) foundation** web application. It was built to practice:
- Setting up a **CodeIgniter 4** project
- Creating **Routes**, **Controllers**, and **Views** (MVC pattern)
- Passing data from a **Controller** to a **View** using PHP arrays
- Displaying records using a **`foreach` loop** in a View

> **No database is used.** All data comes from static PHP arrays inside the controllers.

---

## ✅ Requirements

- **XAMPP** with PHP 8.0 or higher
- **CodeIgniter 4** (already installed in this project)
- A web browser

---

## 📁 Project Structure

```
codeigniteract1/
├── app/
│   ├── Config/
│   │   └── Routes.php          ← Defines all URL routes
│   ├── Controllers/
│   │   ├── Pages.php           ← Handles / and /about
│   │   ├── Customers.php       ← Handles /customers (with static array)
│   │   └── Users.php           ← Handles /users (with static array)
│   └── Views/
│       ├── templates/
│       │   ├── header.php      ← Shared navigation + CSS
│       │   └── footer.php      ← Shared footer
│       ├── pages/
│       │   ├── home.php        ← Landing page view
│       │   └── about.php       ← About page view
│       ├── customers/
│       │   └── index.php       ← Customer table view (uses foreach)
│       └── users/
│           └── index.php       ← Users table view (uses foreach)
├── .env                        ← App configuration (baseURL, environment)
├── public/                     ← Entry point (index.php lives here)
└── system/                     ← CodeIgniter core (do not edit)
```

---

## ⚙️ How to Configure `.env`

The `.env` file tells CodeIgniter where your app is running.

Open `.env` and confirm this is set:

```
CI_ENVIRONMENT = development
app.baseURL = 'http://localhost/studinfo/codeigniteract1/public/'
```

> **`development` mode** shows detailed error messages — helpful for learning.

---

## 🚀 How to Run

1. Make sure **XAMPP is running** (Apache must be ON).
2. Place this folder inside:
   ```
   C:\xampp\htdocs\studinfo\codeigniteract1\
   ```
3. Open your browser and visit:

| Page | URL |
|---|---|
| Home | http://localhost/studinfo/codeigniteract1/public/ |
| About | http://localhost/studinfo/codeigniteract1/public/about |
| Customers | http://localhost/studinfo/codeigniteract1/public/customers |
| Users | http://localhost/studinfo/codeigniteract1/public/users |

---

## 🗺️ Available Routes

| Route | Controller | Method | Description |
|---|---|---|---|
| `/` | Pages | `index()` | Landing / Home Page |
| `/about` | Pages | `about()` | About the application |
| `/customers` | Customers | `index()` | Customer accounts list |
| `/users` | Users | `index()` | Staff/user accounts list |

---

## 🔄 How the MVC Flow Works

```
Browser visits URL
     ↓
Routes.php → decides which Controller::method() runs
     ↓
Controller → prepares data (static array), calls view()
     ↓
View → uses foreach to display data as HTML
     ↓
Browser shows the page
```

---

## 📚 Key Concepts Demonstrated

| Concept | Where to Look |
|---|---|
| Routes | `app/Config/Routes.php` |
| Controller with static array | `app/Controllers/Customers.php` |
| Passing data to a View | `$data['customers'] = $customers;` |
| `foreach` loop in a View | `app/Views/customers/index.php` |
| Shared templates | `app/Views/templates/` |

---

*IT0049 Web System Technologies – Technical Formative Assessment*
