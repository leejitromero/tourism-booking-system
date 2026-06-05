# Lingayen Tourism Booking System

A Laravel final project for project #19: Tourism Booking System. The system is focused on Lingayen, Pangasinan and is inspired by Booking.com-style browsing: package cards, search/filter, ratings, prices, slots, reservations, payments, and admin reports.

## Main Features

- Tourist registration, login, logout, and session handling
- Admin and tourist roles
- Tour package browsing with search, category filter, sort, price, ratings, and slots
- Booking creation and booking history
- Payment record per booking
- Admin dashboard with booking and revenue statistics
- Admin package CRUD
- Admin booking approval/rejection/completion
- Reviews and ratings after approved/completed booking
- REST API endpoints for packages, bookings, and payments
- Reports with CSV, XLSX, JSON, and printable PDF export
- CSV import for tour packages
- SQLite database for easy local setup

## Default Accounts

Admin:
- Email: `admin@lingayen.test`
- Password: `password`

Tourist:
- Email: `tourist@lingayen.test`
- Password: `password`

## How to Run Locally

Open PowerShell inside the project folder.

```powershell
composer install
npm install
copy .env.example .env
php artisan key:generate
```

Make sure `.env` contains:

```env
APP_URL=http://127.0.0.1:8000
DB_CONNECTION=sqlite
```

If the SQLite file does not exist:

```powershell
New-Item -ItemType File -Path database\database.sqlite
```

If it already exists, that is okay.

Then run:

```powershell
php artisan migrate:fresh --seed
php artisan optimize:clear
```

Start Vite in Terminal 1:

```powershell
npm run dev
```

Start Laravel in Terminal 2:

```powershell
php artisan serve
```

Open:

```text
http://127.0.0.1:8000
```

## Important URLs

- Tourist package list: `/packages`
- Package details: `/packages/{id}`
- My bookings: `/bookings`
- Admin dashboard: `/admin/dashboard`
- Admin bookings: `/admin/bookings`
- Admin reports: `/admin/reports`
- API package list: `/api/packages`
- API package details: `/api/packages/{id}`

## CSV Import Format

Go to Admin Reports, download the sample CSV, then upload a CSV with this header:

```csv
title,category,description,location,duration,price,slots,image_url
```

## Developers

Add your group member names here:

1. Full Name
2. Full Name
3. Full Name

## Hosting Link

Add your deployed project link here after deployment.
