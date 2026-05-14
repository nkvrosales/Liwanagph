# 💡 Liwanag.ph - Electric Billing System

![PHP](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-005C84?style=for-the-badge&logo=mysql&logoColor=white)
![JavaScript](https://img.shields.io/badge/JavaScript-F7DF1E?style=for-the-badge&logo=javascript&logoColor=black)

**Liwanag.ph** is a modern, web-based Electric Billing Management System designed to streamline the process of billing, user management, and payment tracking. Built with PHP and MySQL, it features a clean, responsive interface powered by custom CSS and modern UI principles.

---

## 🚀 Key Features

### 👤 User Side

- **Secure Authentication:** Secure login and registration with real-time validation.
- **Dashboard:** Overview of current billing status and usage history.
- **Bill Viewing:** Access detailed breakdowns of monthly electricity bills.
- **Profile Management:** Update personal information and account settings.

### 🔐 Admin Side

- **User Management:** View, edit, and manage registered consumers.
- **Billing Engine:** Generate, update, and delete billing records for users.
- **Data Control:** Full CRUD capabilities for billing and system data.
- **System Analytics:** Quick overview of total users and billing metrics.

---

## 🛠️ Technology Stack

- **Backend:** PHP
- **Database:** MySQL
- **Frontend:** HTML5, Bootstrap, JavaScript/jQuery
- **Server Environment:** XAMPP

---

## 📦 Installation Guide

To get this project running on your local machine, follow these steps:

1.  **Clone the Repository:**

    ```bash
    git clone https://github.com/yourusername/liwanagph.git
    ```

2.  **Move to Web Directory:**
    Move the `ebill` folder to your local server's root directory (e.g., `C:/xampp/htdocs/` for XAMPP).

3.  **Database Setup:**
    - Open **phpMyAdmin** (`http://localhost/phpmyadmin`).
    - Create a new database named `ebillsystem`.
    - Import the SQL file located in: `DATABASE FILE/ebillsystem.sql`.

4.  **Configure Connection:**
    Check the database connection settings in `Includes/db_connect.php` (or equivalent file) to ensure the credentials match your local setup.

5.  **Run the Application:**
    Navigate to `http://localhost/ebill` in your browser.

---

## 🔑 Default Login Credentials

### Admin Access

- **Username:** `admin`
- **Password:** `password`
- **Email:** `admin@gmail.com`

---

## 🎨 UI & Design

The system uses the **Liwanag.ph** brand identity, featuring:

- A "Modern Dark" aesthetic with glassmorphism elements.
- High-contrast typography for readability.
- Responsive layouts for mobile and desktop usage.

---
