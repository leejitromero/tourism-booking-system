<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use Stripe\Checkout\Session;
use Stripe\Stripe;

class StripePaymentController extends Controller
{
    public function checkout(Booking $booking)
    {
        abort_unless($booking->user_id === auth()->id(), 403);

        $booking->load(['package', 'payment']);

        abort_unless($booking->payment, 404, 'Payment record not found.');
        abort_if($booking->payment->payment_status === 'Paid', 400, 'This booking is already paid.');
        abort_if($booking->status === 'Rejected' || $booking->status === 'Completed', 400, 'This booking can no longer be paid.');

        if (!config('services.stripe.secret')) {
            return back()->with('error', 'Stripe secret key is not configured. Please add STRIPE_SECRET in your .env file.');
        }

        Stripe::setApiKey(config('services.stripe.secret'));

        $amountInCentavos = (int) round($booking->total_amount * 100);

        $session = Session::create([
            'mode' => 'payment',
            'payment_method_types' => ['card'],
            'client_reference_id' => (string) $booking->id,
            'customer_email' => auth()->user()->email,
            'line_items' => [[
                'price_data' => [
                    'currency' => config('services.stripe.currency', 'php'),
                    'product_data' => [
                        'name' => $booking->package->title ?? 'Tourism Booking',
                        'description' => 'Booking #' . $booking->id,
                    ],
                    'unit_amount' => $amountInCentavos,
                ],
                'quantity' => 1,
            ]],
            'success_url' => route('stripe.success', ['booking' => $booking->id]) . '&session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('stripe.cancel', ['booking' => $booking->id]),
        ]);

        $booking->payment->update([
            'payment_method' => 'Stripe',
            'reference_number' => $session->id,
            'payment_status' => 'Pending',
        ]);

        return redirect()->away($session->url);
    }

    public function success(Request $request)
    {
        $booking = Booking::with('payment')->findOrFail($request->query('booking'));

        abort_unless($booking->user_id === auth()->id(), 403);

        $sessionId = $request->query('session_id');

        if (!$sessionId) {
            return redirect()->route('bookings.show', $booking)->with('error', 'Stripe session was not found.');
        }

        if (!config('services.stripe.secret')) {
            return redirect()->route('bookings.show', $booking)->with('error', 'Stripe secret key is not configured.');
        }

        Stripe::setApiKey(config('services.stripe.secret'));

        $session = Session::retrieve($sessionId);

        if ((string) $session->client_reference_id !== (string) $booking->id) {
            return redirect()->route('bookings.show', $booking)->with('error', 'Stripe payment does not match this booking.');
        }

        if ($session->payment_status === 'paid') {
            $booking->payment->update([
                'payment_method' => 'Stripe',
                'reference_number' => $session->id,
                'payment_status' => 'Paid',
            ]);

            return redirect()->route('bookings.show', $booking)->with('success', 'Stripe payment successful. Please wait for admin approval.');
        }

        return redirect()->route('bookings.show', $booking)->with('error', 'Stripe payment was not completed.');
    }

    public function cancel(Request $request)
    {
        $booking = Booking::findOrFail($request->query('booking'));

        abort_unless($booking->user_id === auth()->id(), 403);

        return redirect()->route('bookings.show', $booking)->with('error', 'Stripe payment was cancelled. You can try again anytime.');
    }
}
