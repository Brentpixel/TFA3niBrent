# IT0049 - CodeIgniter 4 Activity

## About the Project
This is my CodeIgniter 4 project for the IT0049 laboratory activity. It is a simple web application that contains four basic pages:
- Home
- About
- Customer Accounts
- User Accounts

## Pages
Here is a quick breakdown of what each page in the project does:
- Home: The landing page of the application with some basic project details.
- About: A short explanation of the project and the MVC structure used.
- Customer Accounts: A page that displays a list of registered customers in a table.
- User Accounts: A page that displays a list of system users and staff in a table.

## Technologies Used
- CodeIgniter 4
- PHP
- HTML

## How to Run
To run this project locally, follow these steps:
1. Make sure XAMPP is installed and Apache is running.
2. Put the project folder inside your `htdocs` directory (e.g., `C:\xampp\htdocs\studinfo\codeigniteract1`).
3. Open your browser and go to `http://localhost/studinfo/codeigniteract1/public/`

## Routes
These are the routes I configured for the application:
- /
- /about
- /customers
- /users

## Data
I am not using a database for this activity yet. Instead, the Customer Accounts and User Accounts pages use static PHP arrays for the records. I passed these arrays from the controllers to the views and used foreach loops to display the data in the tables.
