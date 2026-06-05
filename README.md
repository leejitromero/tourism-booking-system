# Lingayen Tourism Booking System

A Laravel final project for project #19: Tourism Booking System. The system is focused on Lingayen, Pangasinan and is inspired by Booking.com-style browsing: package cards, search/filter, ratings, prices, slots, reservations, payments, admin package management, booking approval, reports, REST API, and API token authentication.

## Main Features

* Tourist registration, login, logout, and session handling
* Admin and tourist roles
* Tour package browsing with search, category filter, sort, price, ratings, and slots
* Booking creation and booking history
* Payment record per booking
* Manual payment methods such as GCash, Bank Transfer, and Cash
* Stripe online payment integration
* Admin dashboard with booking and revenue statistics
* Admin package CRUD
* Admin manage packages page with edit and delete actions
* Admin booking approval, rejection, and completion
* Reviews and ratings after approved/completed booking
* REST API endpoints for packages, bookings, and payments
* API token authentication using Laravel Sanctum
* Reports with CSV, XLSX, JSON, and printable PDF export
* CSV import for tour packages
* SQLite database for easy local setup

## Default Accounts

Admin:

* Email: `admin@lingayen.test`
* Password: `password`

Tourist:

* Email: `tourist@lingayen.test`
* Password: `password`

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

* Home page: `/`
* Tourist package list: `/packages`
* Package details: `/packages/{id}`
* My bookings: `/bookings`
* Booking details: `/bookings/{id}`
* Admin dashboard: `/admin/dashboard`
* Admin bookings: `/admin/bookings`
* Admin manage packages: `/admin/packages/manage`
* Admin add package: `/admin/packages/create`
* Admin reports: `/admin/reports`
* API package list: `/api/packages`
* API package details: `/api/packages/{id}`
* API register: `/api/register`
* API login token: `/api/login`
* API authenticated user: `/api/user`
* API logout token: `/api/logout`

## API Authentication with Token

This system includes API token authentication using Laravel Sanctum.

The web system uses normal session-based authentication for user and admin login. The API uses Sanctum token authentication for Postman testing and Bearer Token access.

## API Auth Endpoints

```text
POST /api/register
POST /api/login
GET /api/user
POST /api/logout
```

## Login using Postman

Method:

```text
POST
```

URL:

```text
http://127.0.0.1:8000/api/login
```

Headers:

```text
Accept: application/json
Content-Type: application/json
```

Body → raw → JSON:

```json
{
  "email": "admin@lingayen.test",
  "password": "password"
}
```

Sample successful response:

```json
{
  "message": "Login successful.",
  "user": {
    "id": 1,
    "name": "Admin",
    "email": "admin@lingayen.test"
  },
  "token": "1|sampletokenhere",
  "token_type": "Bearer"
}
```

Copy the token from the response.

## Using the Token in Postman

For protected API routes, go to:

```text
Authorization → Type → Bearer Token
```

Paste the token.

Example protected route:

```text
GET http://127.0.0.1:8000/api/user
```

Expected response:

```json
{
  "user": {
    "id": 1,
    "name": "Admin",
    "email": "admin@lingayen.test"
  }
}
```

## Logout API

Method:

```text
POST
```

URL:

```text
http://127.0.0.1:8000/api/logout
```

Authorization:

```text
Bearer Token
```

Sample response:

```json
{
  "message": "Logout successful."
}
```

## REST API Endpoints

The system includes REST API endpoints for packages, bookings, and payments.

### Packages API

```text
GET /api/packages
POST /api/packages
GET /api/packages/{package}
PUT/PATCH /api/packages/{package}
DELETE /api/packages/{package}
```

### Bookings API

```text
GET /api/bookings
POST /api/bookings
GET /api/bookings/{booking}
PUT/PATCH /api/bookings/{booking}
DELETE /api/bookings/{booking}
```

### Payments API

```text
GET /api/payments
GET /api/payments/{payment}
PUT/PATCH /api/payments/{payment}
DELETE /api/payments/{payment}
```

## Sample Postman Request: Create Package

Method:

```text
POST
```

URL:

```text
http://127.0.0.1:8000/api/packages
```

Headers:

```text
Accept: application/json
Content-Type: application/json
```

Body → raw → JSON:

```json
{
  "title": "API Test Beach Resort",
  "category": "Beach Resort",
  "stars": 4,
  "description": "Created using Postman API testing.",
  "location": "Lingayen, Pangasinan",
  "distance": "Near Lingayen Beach",
  "beach_info": "Beachfront",
  "duration": "2 days, 1 night",
  "price": 2500,
  "slots": 20,
  "image_url": "https://images.unsplash.com/photo-1507525428034-b723cf961d3e",
  "review_score": 4.5,
  "review_count": 10,
  "amenities": "WiFi, Pool, Parking, Breakfast"
}
```

## Stripe Payment Setup

The system supports Stripe as an online payment option.

Add your Stripe test keys in `.env`:

```env
STRIPE_KEY=pk_test_your_publishable_key_here
STRIPE_SECRET=sk_test_your_secret_key_here
STRIPE_CURRENCY=php
```

Use Stripe test mode only during testing.

Sample Stripe test card:

```text
Card Number: 4242 4242 4242 4242
Expiry Date: Any future date
CVC: Any 3 digits
ZIP: Any number
```

Important: Do not commit real Stripe secret keys to GitHub.

## CSV Import Format

Go to Admin Reports, download the sample CSV, then upload a CSV with this header:

```csv
title,category,description,location,duration,price,slots,image_url
```

## Database Tables

Main tables used in the system:

* `users`
* `tour_packages`
* `bookings`
* `payments`
* `reviews`
* `personal_access_tokens`
* `sessions`
* `cache`
* `jobs`

## Entity Relationship Diagram (ERD)

In this ERD, users can create bookings, tour packages can have many bookings, and each booking has one payment record. Users can also write reviews for tour packages. The `personal_access_tokens` table is used by Laravel Sanctum for API login tokens, but it may not show a direct line because Sanctum uses `tokenable_type` and `tokenable_id` instead of a normal foreign key.

<img width="431" height="399" alt="erdd" src="https://github.com/user-attachments/assets/7f3a8397-8633-422c-af24-674317eb0df0" />


## Laravel Features Used

* Routing
* Route groups
* Controllers
* Resource controllers
* Models
* Migrations
* Seeders
* Eloquent ORM
* Relationships
* Blade templates
* Forms
* Middleware
* Session authentication
* Laravel Sanctum API token authentication
* RESTful API
* Git and GitHub
* Deployment through Railway

## Project Requirement Mapping

### Tourist Module

* Browse tour packages
* View package details
* Book tours/accommodations
* View booking history
* Cancel pending reservations
* Add reviews after approved/completed booking

### Admin Module

* View dashboard statistics
* Manage packages
* Add packages
* Edit packages
* Delete packages
* Manage bookings
* Approve bookings
* Reject bookings
* Mark bookings as completed
* Generate reports
* Export reports as CSV, XLSX, JSON, and printable PDF
* Import packages using CSV

### Relationships

* User has many bookings
* Tour package has many bookings
* Booking belongs to user
* Booking belongs to tour package
* Booking has one payment
* Tour package has many reviews
* User has many reviews

## Deployment

The system is deployed using Railway.

Production link:

```text
https://tourism-booking-system-production.up.railway.app
```

## Developers

1. Cristopherson
2. Kent Winsley
3. Jitlee Romero

## Notes

This system is a school final project. Some payment methods such as GCash and Bank Transfer are manually recorded using reference numbers. Stripe is used as an online payment integration for testing/demo purposes.
