# KaAni: An AI-Powered Crop Advisory and Farm Decision Support System for Cagayan Province

Midterm prototype.

## Group Members

- Rion Poblete

## Description

KaAni helps agricultural personnel in Cagayan Province monitor weather-based crop risk per municipality and release farm advisories. Weather data is pulled automatically, the system assigns a Low, Moderate, or High risk level per town, and drafts an advisory that an officer can edit and submit. A provincial admin reviews each advisory before it is approved.

## User Roles

| Role | What they can do in the prototype |
|---|---|
| Admin (Provincial Agriculture Office) | View the admin dashboard (weather risk per town, advisory counts). Review advisories submitted by officers and approve or reject them with a note. |
| Officer (Municipal Agriculture Officer) | View the officer dashboard (weather risk per town, own advisory counts). Generate an AI draft, edit it, and submit an advisory. See the status of their own advisories (pending, approved, rejected) and the admin's note. |

Each role has its own pages. Opening the other role's pages returns 403 Access denied. Protected pages redirect to the login page when logged out.

## Core Feature: Advisory Submission and Approval

1. Officer selects a municipality and crop, clicks Generate AI draft, edits the message, and submits it.
2. The server validates the input (municipality must exist, crop must be Rice or Corn, message must be 20 to 2000 characters). Errors are shown under each field.
3. The advisory is saved with status `pending` and linked to the officer who submitted it.
4. Admin sees it under Review Advisories and approves it, or rejects it with a required note.
5. Officer sees the updated status and note under My Advisories.

## Demo Accounts

| Role | Email | Password |
|---|---|---|
| Admin | admin@kaani.test | password |
| Officer | officer@kaani.test | password |
| Officer | officer2@kaani.test | password |

## Setup

Requirements: PHP 8.3 or higher, Composer, internet connection (for weather data).

macOS / Linux:

```
git clone <repository-url> kaani
cd kaani
composer install
cp .env.example .env
php artisan key:generate
touch database/database.sqlite
php artisan migrate:fresh --seed
php artisan serve
```

Windows (PowerShell):

```
git clone <repository-url> kaani
cd kaani
composer install
copy .env.example .env
php artisan key:generate
New-Item database\database.sqlite
php artisan migrate:fresh --seed
php artisan serve
```

Open http://127.0.0.1:8000

No `npm` step is needed. The pages use plain CSS in `public/css/kaani.css`.

Run the tests:

```
php artisan test
```

## Code Guide

| Part | File |
|---|---|
| Routes (grouped by role) | `routes/web.php` |
| Role check | `app/Http/Middleware/EnsureRole.php`, registered as `role` in `bootstrap/app.php` |
| Login and logout | `app/Http/Controllers/AuthController.php` |
| Role-based landing and dashboards | `app/Http/Controllers/DashboardController.php` |
| Core feature (officer side) | `app/Http/Controllers/OfficerAdvisoryController.php` |
| Core feature (admin side) | `app/Http/Controllers/AdminAdvisoryController.php` |
| Risk rules and AI draft | `app/Services/RiskAssessmentService.php` |
| Weather data (Open-Meteo) | `app/Services/WeatherService.php` |
| Migrations | `database/migrations/` |
| Demo accounts and towns | `database/seeders/DatabaseSeeder.php` |
| Pages | `resources/views/` |

## Notes

- Weather data comes from Open-Meteo, a free model-based weather API, as a substitute until PAGASA data access is granted.
- The AI draft is rule-based in this prototype (thresholds on rainfall, temperature, and humidity).
