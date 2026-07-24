# 🌅 D'Mario Sunset Resto

![PHP](https://img.shields.io/badge/PHP-8.2%2B-777BB4?style=flat-square&logo=php&logoColor=white)
![Laravel](https://img.shields.io/badge/Laravel-12.x-FF2D20?style=flat-square&logo=laravel&logoColor=white)
![Vue.js](https://img.shields.io/badge/Vue-3.x-4FC08D?style=flat-square&logo=vuedotjs&logoColor=white)
![Testing](https://img.shields.io/badge/Tests-Passing-brightgreen?style=flat-square&logo=vitest&logoColor=white)
![License](https://img.shields.io/badge/License-MIT-blue?style=flat-square)

## 📖 Project Overview & Features Summary

**D'Mario Sunset Resto** is a modern, high-performance QR-based digital restaurant menu, ordering, and table reservation web application. It combines a sophisticated monolithic hybrid architecture using **Laravel 12** and **Inertia.js** with **Vue 3**. For seamless back-office operations and robust management, it integrates the powerful **Filament v5 Admin Panel**.

### ✨ Key Features
- **AI Business Intelligence Agent**: A highly advanced, context-aware LLM Copilot integrated directly into the core data layer. It uses native tool calling (ReAct loop) to run complex analytics on revenue, menu performance, and orders in real-time. Features fluid streaming and a secure, OWASP-compliant architecture. [Read the full AI Services Documentation here](AI_SERVICES.md).
- **QR-Based Digital Menu & Ordering**: Scan a QR code to bind dynamically to a table. Features real-time order status tracking with strict session isolation.
- **Advanced Table Reservation System**: Interactive layout selection and an hourly time-slot picker (10:00 - 22:00 WIB). Prevents double-booking natively and bridges directly to WhatsApp for manual confirmation.
- **Back-Office Admin Panel**: Fully featured role-based Filament dashboard to manage categories, menus, tables, orders, and reservations seamlessly.
- **Enterprise-Grade Security**: Server-side price recalculations, strict DB atomic transactions, and Fortify 2FA integration.

---

## 🛠 Tech Stack Matrix

| Category | Technologies / Tools |
| :--- | :--- |
| **Backend Core** | Laravel 12, PHP 8.2+, MySQL / PostgreSQL |
| **Frontend Framework** | Vue 3 (Composition API + TypeScript), Inertia.js v2 |
| **Styling & UI Components** | TailwindCSS v4, Reka UI, Lucide Icons |
| **Back-Office Panel** | Filament v5, Laravel Fortify (2FA) |
| **Testing Infrastructure** | Pest PHP v4 (Backend), Vitest & @vue/test-utils (Frontend) |
| **Build Tooling** | Vite |

---

## 🏗 Architecture & Security Highlights

This repository embraces a **Monolithic Hybrid Architecture**, rendering Vue SPA components directly from Laravel controllers via Inertia.js, effectively bypassing the need for a separate API routing layer while maintaining incredible SPA speeds.

- **Strict Database Transactions**: Order creation and order detail insertions are tightly wrapped in `DB::transaction`. If one fails, the entire payload rolls back automatically.
- **Server-Side Price Integrity**: The backend strictly ignores payload pricing from the frontend. It fetches the true `MenuItem::price` from the database directly during recalculation to eliminate client-side tampering risks.
- **Automated Cache Invalidation**: Leveraging Laravel's `Cache::remember` for lightning-fast menu loads. Utilizing Eloquent Model Observers (`static::saved` and `static::deleted` in the `MenuItem` model) to automatically trigger `Cache::forget` during menu updates.
- **Smart Middleware Authorization**: `EnsureTableSelected` gracefully handles missing session states, preventing rogue checkouts on disconnected devices.

---

## 💻 Prerequisites & System Requirements

Before getting started, ensure you have the following installed on your local environment:
- **PHP** `^8.2`
- **Composer** `^2.2`
- **Node.js** `^20.0` & **npm** `^10.0`
- **MySQL** `^8.0` (or compatible relational database)

---

## 🚀 Local Development Setup Guide

Follow these steps strictly to run the application locally:

**1. Clone the repository and enter the directory:**
```bash
git clone https://github.com/your-repo/dmario-sunset-resto.git
cd dmario-sunset-resto
```

**2. Install PHP and Node.js dependencies:**
```bash
composer install
npm install
```

**3. Configure Environment Variables:**
```bash
cp .env.example .env
php artisan key:generate
```
*Make sure to update your `.env` file with the correct `DB_DATABASE`, `DB_USERNAME`, and `DB_PASSWORD`.*

**4. Run Database Migrations and Seeders:**
```bash
php artisan migrate --seed
```
*The seeders will populate default tables, categories, menu items, and admin user credentials.*

**5. Create Storage Symbolic Link:**
```bash
php artisan storage:link
```
*Required for loading dynamically uploaded menu item images and QR codes.*

**6. Start the Development Servers (Run in separate terminal tabs):**
```bash
php artisan serve
```
```bash
npm run dev
```
You can now access the application at `http://localhost:8000`.

---

## 🧪 Automated Testing Suite Guide

This project takes code quality seriously with **100% Automated Test Coverage** for critical business logic across both stacks.

### Backend Testing (Pest PHP v4)
Run feature and unit tests to validate controllers, cache invalidation, and transactional DB logic safely utilizing the `RefreshDatabase` trait.
```bash
php artisan test
# OR
./vendor/bin/pest
```

### Frontend Testing (Vitest)
Run component, page, and composable unit tests. Validates reactive UI changes, Form validations, DOM updates, and Alert Modals seamlessly via `jsdom` (no browser needed).
```bash
npm run test:js
# OR
npx vitest run
```

---

## 📂 Key Project Directory Structure

```text
dmario-sunset-resto/
├── app/
│   ├── Filament/          # Admin Back-office Panel (Resources, Widgets)
│   ├── Http/
│   │   ├── Controllers/   # Business Logic (CheckoutController, LandingPageController)
│   │   └── Middleware/    # Session & Access Control (EnsureTableSelected)
│   └── Models/            # Eloquent Models & Cache Boot Hooks
├── database/
│   ├── factories/         # Model Factories for robust automated testing
│   ├── migrations/        # Database Schema Definitions
│   └── seeders/           # Initial Database Seeders
├── resources/
│   └── js/
│       ├── components/    # Reusable Vue 3 Components (Reka UI, Modals)
│       ├── composables/   # Extracted Vue Logic (useAppearance, useTwoFactorAuth)
│       └── pages/         # Inertia.js Page Views (Landing Page, Menu, Reservation)
├── routes/
│   └── web.php            # Primary Route Definitions
└── tests/
    ├── Feature/           # Backend API & Controller Pest Tests
    ├── Unit/              # Backend Model & General Unit Pest Tests
    └── js/                # Frontend Vitest Specs & Mocks
```

---

## 📄 License & Author

- **Author**: D'Mario Engineering Team
- **License**: This software is proprietary and confidential. Ensure proper organizational clearance before distributing or deploying.
