# Stripe Setup Notes

Your Stripe secret key must stay private. If you pasted a secret key in chat or uploaded it publicly, revoke/roll it in Stripe Dashboard and create a new test secret key.

## 1. Install Stripe PHP package

```bash
composer require stripe/stripe-php
```

## 2. Add keys to `.env`

Use new sandbox/test keys from Stripe Dashboard → Developers → API keys:

```env
STRIPE_KEY=pk_test_your_new_publishable_key
STRIPE_SECRET=sk_test_your_new_secret_key
STRIPE_CURRENCY=php
```

Then clear config cache:

```bash
php artisan config:clear
```

## 3. Test flow

1. Run the app: `php artisan serve`
2. Login as a normal user.
3. Create a booking and choose `Stripe` as payment method.
4. Open the booking details page.
5. Click `Pay with Stripe`.
6. Use test card: `4242 4242 4242 4242`, any future expiry, any CVC.
7. After success, payment status becomes `Paid`.
8. Admin can still approve the booking manually.

## Files changed

- `composer.json`
- `config/services.php`
- `routes/web.php`
- `app/Http/Controllers/StripePaymentController.php`
- `resources/views/bookings/create.blade.php`
- `resources/views/bookings/show.blade.php`
- `.env.example`
