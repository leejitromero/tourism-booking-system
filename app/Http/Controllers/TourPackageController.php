<?php

namespace App\Http\Controllers;

use App\Models\TourPackage;
use Illuminate\Http\Request;

class TourPackageController extends Controller
{
    public function manage()
    {
        abort_unless(auth()->user()?->is_admin, 403);

        $packages = TourPackage::latest()->get();

        return view('admin.packages.index', compact('packages'));
    }

    public function index(Request $request)
    {
        $categories = TourPackage::query()->select('category')->distinct()->whereNotNull('category')->orderBy('category')->pluck('category');
        $query = TourPackage::with('reviews');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        match ($request->get('sort')) {
            'price_low' => $query->orderBy('price'),
            'price_high' => $query->orderByDesc('price'),
            'rating' => $query->withAvg('reviews', 'rating')->orderByDesc('reviews_avg_rating'),
            default => $query->latest(),
        };

        $packages = $query->get();
        return view('packages.index', compact('packages', 'categories'));
    }

    public function create()
    {
        abort_unless(auth()->user()?->is_admin, 403);
        return view('packages.create');
    }

    public function store(Request $request)
    {
        abort_unless(auth()->user()?->is_admin, 403);
        TourPackage::create($this->validated($request));
        return redirect()->route('admin.packages.manage')->with('success', 'Package added successfully.');
    }

    public function show(TourPackage $package)
    {
        $package->load(['reviews.user']);
        return view('packages.show', compact('package'));
    }

    public function edit(TourPackage $package)
    {
        abort_unless(auth()->user()?->is_admin, 403);
        return view('packages.edit', compact('package'));
    }

    public function update(Request $request, TourPackage $package)
    {
        abort_unless(auth()->user()?->is_admin, 403);
        $package->update($this->validated($request));
        return redirect()->route('admin.packages.manage')->with('success', 'Package updated successfully.');
    }

    public function destroy(TourPackage $package)
    {
        abort_unless(auth()->user()?->is_admin, 403);
        $package->delete();
        return redirect()->route('admin.packages.manage')->with('success', 'Package deleted successfully.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'description' => 'required|string',
            'location' => 'required|string|max:255',
            'duration' => 'nullable|string|max:100',
            'price' => 'required|numeric|min:0',
            'slots' => 'required|integer|min:1',
            'image_url' => 'nullable|string|max:500',
            'distance' => 'nullable|string|max:100',
            'beach_info' => 'nullable|string|max:100',
            'stars' => 'nullable|integer|min:0|max:5',
            'review_score' => 'nullable|numeric|min:0|max:10',
            'review_count' => 'nullable|integer|min:0',
            'amenities' => 'nullable|string',
        ]);
    }
}
