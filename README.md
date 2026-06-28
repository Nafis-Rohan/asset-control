# Asset Manager

A multi-role procurement and asset management system built with **Laravel 12**, **MySQL**, **Tailwind CSS**, **Alpine.js**, and **Laravel Breeze**.

Employees request equipment, managers approve or deny requests by department, and IT admins manage the global asset inventory.

## Tech Stack

| Layer | Technology |
|-------|------------|
| Backend | Laravel 12 (strict MVC) |
| Database | MySQL |
| Auth | Laravel Breeze (Blade) |
| Frontend | Blade, Tailwind CSS, Alpine.js |
| Validation | Form Request classes |

## Roles & Permissions

| Role | Capabilities |
|------|--------------|
| **Admin** | Full CRUD on assets; view all requests; approve, deny, or mark requests as fulfilled |
| **Manager** | View and approve/deny pending requests from employees in their department |
| **Employee** | Submit asset requests; view own requests and assigned assets |

## Requirements

- PHP 8.2+
- Composer
- Node.js & npm
- MySQL (WAMP/XAMPP or standalone)
- Apache/Nginx or `php artisan serve`

## Installation

### 1. Clone and install dependencies

```bash
cd c:\wamp64\www\asset
composer install
npm install
```

### 2. Environment configuration

Copy `.env.example` to `.env` if needed, then configure MySQL:

```env
APP_NAME="Asset Manager"
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=asset_manager
DB_USERNAME=root
DB_PASSWORD=
```

Generate the application key:

```bash
php artisan key:generate
```

### 3. Create the database

In MySQL (phpMyAdmin or CLI):

```sql
CREATE DATABASE asset_manager CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### 4. Run migrations and seed demo data

```bash
php artisan migrate --seed
```

### 5. Build frontend assets

```bash
npm run build
```

For development with hot reload:

```bash
npm run dev
```

### 6. Start the application

**Option A — WAMP:** Point your virtual host or access `http://localhost/asset/public`

**Option B — Artisan:**

```bash
php artisan serve
```

Visit `http://127.0.0.1:8000`

## Demo Accounts

All accounts use password: `password`

| Role | Email | Department |
|------|-------|------------|
| Admin | admin@assetmanager.test | IT |
| Manager | manager@assetmanager.test | IT |
| Employee | employee@assetmanager.test | IT |
| Employee | john@assetmanager.test | HR |

## Project Structure

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── AssetController.php          # Admin inventory CRUD
│   │   ├── AssetRequestController.php   # Request listing & status updates
│   │   └── DashboardController.php      # Role-based dashboard stats
│   ├── Middleware/
│   │   └── EnsureUserHasRole.php        # Role-based route protection
│   └── Requests/                        # Form validation
├── Models/
│   ├── Asset.php
│   ├── AssetRequest.php
│   └── User.php
database/migrations/                     # users, assets, asset_requests
resources/views/
├── assets/                              # Inventory management (admin)
├── requests/                            # Request list & approval UI
├── dashboard.blade.php                  # Role-based quick stats
└── layouts/                             # Breeze authenticated layout
```

## Database Schema

### users
- `role` — enum: `admin`, `manager`, `employee` (default: `employee`)
- `department` — nullable string

### assets
- `name`, `serial_number` (unique), `category`
- `status` — enum: `available`, `assigned`, `maintenance`, `retired`
- `user_id` — nullable FK to current holder

### asset_requests
- `user_id` — requesting employee
- `requested_item`, `reason`
- `status` — enum: `pending`, `approved`, `denied`, `fulfilled`
- `manager_id` — nullable FK to reviewing manager

## Routes

All application routes require authentication (`auth` + `verified` middleware).

| Route | Access | Description |
|-------|--------|-------------|
| `/dashboard` | All roles | Role-based statistics |
| `/assets` | Admin | Inventory CRUD |
| `/requests` | All roles | View requests (scoped by role) |
| `POST /requests` | Employee | Submit new request |
| `PATCH /requests/{id}/status` | Manager, Admin | Approve / deny / fulfill |

## Authorization

- **Middleware:** `role:admin`, `role:manager,admin`, `role:employee` on route groups
- **Gates:** Defined in `AppServiceProvider` for `manage-assets`, `review-requests`, `create-requests`, and department-scoped request viewing
- **Form Requests:** Authorization enforced in `authorize()` methods

## Development

Run the full dev stack (server, queue, logs, Vite):

```bash
composer dev
```

Run tests:

```bash
php artisan test
```

## License

MIT
