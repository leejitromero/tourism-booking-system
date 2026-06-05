@extends('layouts.app')
@section('title','Printable Booking Report')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 no-print"><h2 class="fw-bold">Printable Booking Report</h2><button class="btn btn-outline-danger" onclick="window.print()">Print / Save as PDF</button></div>
<h2 class="fw-bold print-only">Lingayen Tourism Booking Report</h2>
<p class="print-only">Generated: {{ now()->format('F d, Y h:i A') }}</p>
@include('admin.report-table',['bookings'=>$bookings])
<script>window.addEventListener('load', () => setTimeout(() => window.print(), 500));</script>
@endsection
