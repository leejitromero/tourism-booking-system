<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TourPackage extends Model
{
    protected $fillable = [
        'title','category','stars','description','location','distance','beach_info','duration','price','slots','image_url','review_score','review_count','amenities'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'review_score' => 'decimal:1',
        'stars' => 'integer',
        'review_count' => 'integer',
    ];

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function getAverageRatingAttribute()
    {
        $actual = $this->reviews()->avg('rating');
        if ($actual) return round($actual, 1);
        return $this->review_score ?: 0;
    }

    public function getAmenitiesListAttribute(): array
    {
        if (!$this->amenities) return [];
        return array_values(array_filter(array_map('trim', explode(',', $this->amenities))));
    }

    public function getImageSrcAttribute(): string
    {
        if (!$this->image_url) {
            return 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?q=80&w=1200&auto=format&fit=crop';
        }
        if (str_starts_with($this->image_url, 'http')) return $this->image_url;
        return asset($this->image_url);
    }
}
