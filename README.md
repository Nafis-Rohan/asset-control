
# Asset Manager

A multi-role procurement and asset management system built with **Laravel 12**, **MySQL**, **Tailwind CSS**, **Alpine.js**, and **Laravel Breeze**.

## Project Overview 

**The Problem:** In many companies, keeping track of physical equipment (like laptops, monitors, or phones) is messy. Employees ask for things over email or chat, managers lose track of approvals, and the IT department doesn't know who has what device. 

**The Solution:** Asset Manager is a central hub that organizes this entire process from start to finish. It ensures nothing gets lost in the shuffle and everyone is held accountable for company property. 

**Who Uses It?**
* **Employees:** When an employee needs a new piece of equipment, they log in and submit a simple request explaining what they need and why. They can also see what company gear is currently assigned to them.
* **Managers:** A manager logs in to see requests specifically from their team. They act as the gatekeeper, deciding whether to approve or deny the request based on the team's needs or budget.
* **IT Admins:** The IT team has a global view of everything. They manage the "inventory" (a master list of all company equipment). When a manager approves a request, the IT Admin physically prepares the item, updates the system to mark the request as "fulfilled," and assigns the specific device to the employee.

**The Workflow:**
1. An **Employee** submits a request for a new monitor.
2. Their **Manager** reviews the request and clicks "Approve."
3. The **Admin** sees the approved request, grabs a monitor from the storage room, records its serial number in the system, hands it to the employee, and marks the request as "Fulfilled."

---

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
