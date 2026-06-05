@extends('layouts.app')
@section('content')
<h2 class="fw-bold mb-4">Edit Tour Package</h2>
@include('packages.form',['action'=>route('admin.packages.update',$package),'method'=>'PATCH','package'=>$package])
@endsection
