<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentApiController extends Controller
{
    public function index() { return Payment::with('booking')->get(); }
    public function show(Payment $payment) { return $payment->load('booking'); }
    public function update(Request $request, Payment $payment) { $payment->update($request->all()); return $payment; }
    public function destroy(Payment $payment) { $payment->delete(); return response()->json(['message'=>'Payment deleted']); }
}
