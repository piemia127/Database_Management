# DB_FinalProject_B10905104 - Customer Management System

This is a Database Design course final project (Student ID: B10905104), a simple Customer Management System based on PHP and MySQL.

## Prerequisites

Before running this project, please ensure your computer has the following environment installed:

*   **Web Server**: Apache (XAMPP or WAMP is recommended)
*   **PHP**: Recommended version 7.4 or higher
*   **Database**: MySQL or MariaDB

## Installation and Execution Steps

### 1. Deploy Project Files
Copy the entire project folder to your Web Server's root directory.
*   For **XAMPP**, the path is typically `C:\xampp\htdocs\`.
*   For **MAMP**, the path is typically `/Applications/MAMP/htdocs/`.

### 2. Database Setup
This project includes a database structure file named `SQL`. Please follow these steps to set up the database:

1.  Start your Web Server and MySQL service.
2.  Open your database management interface (e.g., phpMyAdmin, usually at `http://localhost/phpmyadmin`).
3.  Click on the "Import" tab.
4.  Select the `SQL` file from the project root directory and execute the import.
    *   *Note: The SQL file already contains the `CREATE DATABASE CustomerManagement;` command, so you do not need to create the database manually; just import it directly.*

### 3. Verify Database Connection Settings
Open the `db.php` file in the project and verify if the database connection information matches your environment:

```php
$servername = "localhost";
$username = "root";      // Default username
$password = "";          // Default password (XAMPP default is empty)
$dbname = "CustomerManagement"; // Database name
```

If your MySQL configuration has a password set, make sure to update `$password` in this file.

### 4. Run the Project
Open your browser and enter the following URL to start using the system:

```
http://localhost/DB_FinalProject_B10905104/index.php
```
(Please adjust the URL according to your actual folder name)

## Features Overview
*   **db.php**: Database connection settings.
*   **index.php**: System homepage.
*   **customer.php**: Customer data management.
*   **order.php**: Order management.
*   **product.php**: Product management.
*   **interaction.php**: Customer interaction records.
*   **service_request.php**: Service request management.
