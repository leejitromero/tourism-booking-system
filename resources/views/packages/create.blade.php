@extends('layouts.app')
@section('content')
<h2 class="fw-bold mb-4">Add Tour Package</h2>
@include('packages.form',['action'=>route('admin.packages.store'),'method'=>'POST','package'=>null])
@endsection
