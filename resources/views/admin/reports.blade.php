@extends('layouts.app')
@section('title','Reports')
@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
    <h2 class="fw-bold mb-0">Booking Reports</h2>
    <div class="btn-group no-print flex-wrap">
        <a class="btn btn-outline-primary" href="{{ route('admin.reports.export',['format'=>'csv']) }}">CSV</a>
        <a class="btn btn-outline-success" href="{{ route('admin.reports.export',['format'=>'xlsx']) }}">XLSX</a>
        <a class="btn btn-outline-dark" href="{{ route('admin.reports.export',['format'=>'json']) }}">JSON</a>
        <a class="btn btn-outline-danger" href="{{ route('admin.reports.export',['format'=>'pdf']) }}" target="_blank">PDF/Print</a>
    </div>
</div>

<div class="card card-body mb-4 no-print">
    <h5 class="fw-bold">Import Tour Packages from CSV</h5>
    <p class="text-muted small mb-2">CSV header must be: title, category, description, location, duration, price, slots, image_url</p>
    <form method="POST" action="{{ route('admin.packages.import') }}" enctype="multipart/form-data" class="row g-2 align-items-center">
        @csrf
        <div class="col-12 col-md-6">
            <input type="file" name="file" class="form-control" accept=".csv,.txt" required>
        </div>
        <div class="col-12 col-md-3">
            <button class="btn btn-main w-100">Import CSV</button>
        </div>
        <div class="col-12 col-md-3">
            <a class="btn btn-outline-secondary w-100" href="{{ route('admin.packages.import.sample') }}">Download Sample</a>
        </div>
    </form>
</div>

<div class="print-only">
    <h2>Lingayen Tourism Booking Report</h2>
    <p>Generated: {{ now()->format('F d, Y h:i A') }}</p>
</div>

<div style="overflow-x: auto; margin-bottom: 100px;">
    @include('admin.report-table',['bookings'=>$bookings])
</div>

<style>
    @media (max-width: 768px) {
        .table {
            font-size: 12px;
        }
        .table th, .table td {
            padding: 0.5rem !important;
        }
    }
</style>
@endsection

