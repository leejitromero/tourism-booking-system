@extends('layouts.app')
@section('title','Select Dates')
@section('content')
<h2 class="fw-bold mb-4">Select dates and complete your booking</h2>
<form method="POST" action="{{ route('bookings.store') }}" class="card card-body" id="bookingForm">
@csrf
<div class="row g-4">
    <div class="col-lg-7">
        <div class="row g-3">
            <div class="col-12">
                <label class="form-label fw-semibold">Accommodation</label>
                <select name="tour_package_id" id="packageSelect" class="form-select form-select-lg" required>
                    @foreach($packages as $item)
                        <option value="{{ $item->id }}" data-price="{{ $item->price }}" data-img="{{ $item->image_src }}" data-title="{{ $item->title }}" @selected(old('tour_package_id',$selectedPackage)==$item->id)>{{ $item->title }} - ₱{{ number_format($item->price,2) }} / night ({{ $item->slots }} slots)</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold">Check-in date</label>
                <input class="form-control form-control-lg" type="date" id="checkIn" name="check_in_date" value="{{ old('check_in_date') }}" min="{{ now()->toDateString() }}" required>
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold">Check-out date</label>
                <input class="form-control form-control-lg" type="date" id="checkOut" name="check_out_date" value="{{ old('check_out_date') }}" min="{{ now()->addDay()->toDateString() }}" required>
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold">Guests / people count</label>
                <input class="form-control form-control-lg" type="number" min="1" id="peopleCount" name="people_count" value="{{ old('people_count',1) }}" required>
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold">Payment Method</label>
                <select class="form-select form-select-lg" name="payment_method"><option>Cash</option><option>GCash</option><option>Bank Transfer</option><option>Stripe</option></select>
            </div>
            <div class="col-12"><label class="form-label fw-semibold">Reference Number</label><input class="form-control" name="reference_number" value="{{ old('reference_number') }}" placeholder="Optional for GCash/Bank Transfer. Not needed for Stripe."></div>
            <div class="col-12"><label class="form-label fw-semibold">Notes / Special Request</label><textarea class="form-control" name="notes" rows="3" placeholder="Optional request">{{ old('notes') }}</textarea></div>
        </div>
    </div>
    <div class="col-lg-5">
        <div class="booking-summary card border h-100">
            <img id="summaryImg" class="summary-img" src="{{ $package?->image_src }}" alt="Accommodation image">
            <div class="card-body">
                <h4 id="summaryTitle" class="fw-bold">{{ $package?->title ?? 'Selected accommodation' }}</h4>
                <div class="d-flex justify-content-between"><span>Price per night</span><strong id="summaryPrice">₱0.00</strong></div>
                <div class="d-flex justify-content-between"><span>Nights</span><strong id="summaryNights">1</strong></div>
                <div class="d-flex justify-content-between"><span>Guests</span><strong id="summaryGuests">1</strong></div>
                <hr>
                <div class="d-flex justify-content-between fs-4"><span>Total</span><strong class="text-primary" id="summaryTotal">₱0.00</strong></div>
                <button class="btn btn-main btn-lg w-100 mt-4">Submit Booking</button>
            </div>
        </div>
    </div>
</div>
</form>
<script>
function money(n){return new Intl.NumberFormat('en-PH',{style:'currency',currency:'PHP'}).format(n)}
function daysBetween(a,b){if(!a||!b)return 1; const d1=new Date(a),d2=new Date(b); return Math.max(1, Math.round((d2-d1)/(1000*60*60*24)));}
function updateSummary(){
    const sel=document.getElementById('packageSelect'); const opt=sel.options[sel.selectedIndex];
    const price=parseFloat(opt.dataset.price||0); const guests=parseInt(document.getElementById('peopleCount').value||1);
    const nights=daysBetween(document.getElementById('checkIn').value, document.getElementById('checkOut').value);
    document.getElementById('summaryImg').src=opt.dataset.img; document.getElementById('summaryTitle').textContent=opt.dataset.title;
    document.getElementById('summaryPrice').textContent=money(price); document.getElementById('summaryNights').textContent=nights;
    document.getElementById('summaryGuests').textContent=guests; document.getElementById('summaryTotal').textContent=money(price*guests*nights);
    const ci=document.getElementById('checkIn'), co=document.getElementById('checkOut'); if(ci.value){let min=new Date(ci.value); min.setDate(min.getDate()+1); co.min=min.toISOString().split('T')[0]; if(co.value && co.value<=ci.value) co.value=co.min;}
}
['packageSelect','checkIn','checkOut','peopleCount'].forEach(id=>document.getElementById(id).addEventListener('input',updateSummary));
updateSummary();
</script>
@endsection
