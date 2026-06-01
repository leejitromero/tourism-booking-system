<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Payment;
use App\Models\Review;
use App\Models\TourPackage;
use Carbon\Carbon;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index()
    {
        $bookings = Booking::with(['package', 'payment'])
            ->where('user_id', auth()->id())
            ->latest()
            ->get();

        return view('bookings.index', compact('bookings'));
    }

    public function create(Request $request)
    {
        $packages = TourPackage::orderBy('title')->get();

        $selectedPackage = $request->package_id;

        $package = $selectedPackage
            ? TourPackage::find($selectedPackage)
            : $packages->first();

        return view('bookings.create', compact('packages', 'selectedPackage', 'package'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'tour_package_id' => 'required|exists:tour_packages,id',
            'check_in_date' => 'required|date|after_or_equal:today',
            'check_out_date' => 'required|date|after:check_in_date',
            'people_count' => 'required|integer|min:1',
            'payment_method' => 'required|string|max:50',
            'reference_number' => 'nullable|string|max:100',
            'notes' => 'nullable|string',
        ]);

        $package = TourPackage::findOrFail($data['tour_package_id']);

        if ($data['people_count'] > $package->slots) {
            return back()
                ->withInput()
                ->with('error', 'Not enough available slots for this accommodation.');
        }

        $checkIn = Carbon::parse($data['check_in_date']);
        $checkOut = Carbon::parse($data['check_out_date']);

        $nights = max(1, $checkIn->diffInDays($checkOut));

        $total = $package->price * $data['people_count'] * $nights;

        $booking = Booking::create([
            'user_id' => auth()->id(),
            'tour_package_id' => $package->id,
            'booking_date' => $data['check_in_date'],
            'check_in_date' => $data['check_in_date'],
            'check_out_date' => $data['check_out_date'],
            'nights' => $nights,
            'people_count' => $data['people_count'],
            'total_amount' => $total,
            'status' => 'Pending',
            'notes' => $data['notes'] ?? null,
        ]);

        Payment::create([
            'booking_id' => $booking->id,
            'amount' => $total,
            'payment_method' => $data['payment_method'],
            'reference_number' => $data['reference_number'] ?? null,
            'payment_status' => 'Pending',
        ]);

        return redirect()
            ->route('bookings.show', $booking)
            ->with('success', 'Booking submitted. Please wait for admin approval.');
    }

    public function show(Booking $booking)
    {
        abort_unless($booking->user_id === auth()->id() || auth()->user()?->is_admin, 403);

        $booking->load(['package.reviews.user', 'payment', 'user']);

        return view('bookings.show', compact('booking'));
    }

    public function destroy(Booking $booking)
    {
        abort_unless($booking->user_id === auth()->id(), 403);

        abort_if(
            $booking->status === 'Approved' || $booking->status === 'Completed',
            403,
            'Approved or completed bookings cannot be cancelled here.'
        );

        if ($booking->payment) {
            $booking->payment->delete();
        }

        $booking->delete();

        return redirect()
            ->route('bookings.index')
            ->with('success', 'Reservation cancelled successfully.');
    }

    public function review(Request $request, Booking $booking)
    {
        abort_unless($booking->user_id === auth()->id(), 403);

        abort_unless(in_array($booking->status, ['Approved', 'Completed']), 403);

        $data = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        Review::updateOrCreate(
            [
                'user_id' => auth()->id(),
                'tour_package_id' => $booking->tour_package_id,
            ],
            [
                'rating' => $data['rating'],
                'comment' => $data['comment'] ?? null,
            ]
        );

        return back()->with('success', 'Thank you for reviewing this accommodation.');
    }
}