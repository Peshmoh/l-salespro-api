# 🧠 L-SalesPro API Backend

**Laravel Backend Developer Technical Assessment – Leysco Limited**  
🔒 Confidential | ✅ Feature-Complete | 🛠️ Production-Ready

---

## 📘 Project Overview

**L-SalesPro** is a modular, RESTful API backend built with Laravel for managing sales workflows, inventory, customer data, and warehouse operations. Designed for performance, scalability, and maintainability, it follows industry best practices including layered architecture, service-repository separation, Redis caching, Laravel queues, Sanctum authentication, and PHPUnit testing.

---

## 🛠️ Tech Stack

| Component     | Version     |
|---------------|-------------|
| Laravel       | 10.48.29    |
| PHP           | 8.2.12      |
| MySQL         | 8.0+        |
| Redis         | Optional    |
| PHPUnit       | Laravel Default |
| Composer      | 2.x         |
| Postman       | Latest      |

---

## ✅ Core Features

- 🔐 **Secure Authentication & Authorization**
  - Token-based using Laravel Sanctum
  - Role-based access control (Sales Manager, Sales Rep)
- 📦 **Inventory Management**
  - Product CRUD, stock reservation, low-stock alerts
  - Multi-warehouse tracking and filtering
- 🛒 **Sales Order Management**
  - Full order lifecycle (Pending → Delivered)
  - Discounts, taxes, stock validation, credit checks
- 👥 **Customer Management**
  - Credit limits, order history, geolocation support
- 🏬 **Warehouse Module**
  - Stock transfers and inventory status by location
- 🔔 **Notification System**
  - Queue-based notifications for stock, orders, credit
- 📊 **Dashboard Analytics**
  - Sales performance metrics with Redis caching
- 📂 **Postman Docs + ERD + Seeders**
  - Complete setup and data samples for evaluation

---

## 🚀 Installation & Setup

### Prerequisites

- PHP ≥ 8.2
- MySQL ≥ 8.0
- Composer
- Redis (optional but recommended)

### Steps

```bash
# 1. Clone the repository
git clone https://github.com/Peshmoh/l-salespro-api.git
cd l-salespro-api

# 2. Install dependencies
composer install

# 3. Set environment variables
cp .env.example .env
php artisan key:generate

# 4. Configure DB in .env

# 5. Migrate and seed test data
php artisan migrate --seed

# 6. Run the development server
php artisan serve

🌐 API Base URL
http://127.0.0.1:8000/api/v1/
🔐 Authentication Endpoints
Method	Endpoint	Description
POST	/auth/login	User login
POST	/auth/logout	User logout
POST	/auth/refresh	Refresh token
GET	/auth/user	Get current user
POST	/auth/password/forgot	Request password reset
POST	/auth/password/reset	Reset password

🧱 API Modules Overview
🔍 Inventory
GET /products

POST /products

PUT /products/{id}

GET /products/{id}/stock

POST /products/{id}/reserve

GET /products/low-stock

🛒 Orders
POST /orders

GET /orders/{id}

PUT /orders/{id}/status

POST /orders/calculate-total

GET /orders/{id}/invoice

👥 Customers
GET /customers

POST /customers

GET /customers/{id}/credit-status

GET /customers/{id}/orders

GET /customers/map-data

🏬 Warehouses
GET /warehouses

GET /warehouses/{id}/inventory

POST /stock-transfers

🔔 Notifications
GET /notifications

PUT /notifications/read-all

DELETE /notifications/{id}

📊 Dashboard
GET /dashboard/summary

GET /dashboard/top-products

GET /dashboard/inventory-status

📦 Seeders & Sample Data
Seeders generate test data using provided JSON:

👤 Users (Sales Manager, Sales Rep)

🛒 Products & Categories

👥 Customers with credit info

🏬 Warehouses with capacity

🔔 Notifications

Run with:

php artisan migrate --seed
