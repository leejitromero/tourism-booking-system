<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TourPackage;
use Illuminate\Http\Request;

class PackageApiController extends Controller
{
    public function index() { return TourPackage::with('reviews')->get(); }
    public function store(Request $request) { return TourPackage::create($request->validate(['title'=>'required','category'=>'required','description'=>'required','location'=>'required','duration'=>'nullable','price'=>'required|numeric','slots'=>'required|integer','image_url'=>'nullable'])); }
    public function show(TourPackage $package) { return $package->load('reviews'); }
    public function update(Request $request, TourPackage $package) { $package->update($request->all()); return $package; }
    public function destroy(TourPackage $package) { $package->delete(); return response()->json(['message'=>'Package deleted']); }
}
