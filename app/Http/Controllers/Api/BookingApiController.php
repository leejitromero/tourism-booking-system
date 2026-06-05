<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\TourPackage;
use Illuminate\Http\Request;

class BookingApiController extends Controller
{
    public function index() { return Booking::with(['user','package','payment'])->get(); }
    public function store(Request $request) {
        $data = $request->validate(['user_id'=>'required|exists:users,id','tour_package_id'=>'required|exists:tour_packages,id','booking_date'=>'required|date','people_count'=>'required|integer|min:1']);
        $package = TourPackage::findOrFail($data['tour_package_id']);
        $data['total_amount'] = $package->price * $data['people_count'];
        return Booking::create($data);
    }
    public function show(Booking $booking) { return $booking->load(['user','package','payment']); }
    public function update(Request $request, Booking $booking) { $booking->update($request->all()); return $booking; }
    public function destroy(Booking $booking) { $booking->delete(); return response()->json(['message'=>'Booking deleted']); }
}
