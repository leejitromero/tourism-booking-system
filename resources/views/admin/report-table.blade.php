<div class="table-responsive">
    <table class="table table-bordered bg-white table-sm">
        <thead>
            <tr>
                <th>Tourist</th>
                <th>Email</th>
                <th>Accommodation</th>
                <th>Location</th>
                <th>Check-in</th>
                <th>Check-out</th>
                <th>Nights</th>
                <th>Guests</th>
                <th>Total</th>
                <th>Booking Status</th>
                <th>Payment</th>
            </tr>
        </thead>
        <tbody>
            @foreach($bookings as $booking)
                <tr>
                    <td>{{ $booking->user->name }}</td>
                    <td>{{ $booking->user->email }}</td>
                    <td>{{ $booking->package->title }}</td>
                    <td>{{ $booking->package->location }}</td>
                    <td>{{ optional($booking->check_in_date ?? $booking->booking_date)->format('Y-m-d') }}</td>
                    <td>{{ optional($booking->check_out_date)->format('Y-m-d') ?? 'N/A' }}</td>
                    <td>{{ $booking->nights ?? 1 }}</td>
                    <td>{{ $booking->people_count }}</td>
                    <td>₱{{ number_format($booking->total_amount,2) }}</td>
                    <td>{{ $booking->status }}</td>
                    <td>{{ $booking->payment->payment_status ?? 'No Payment' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

