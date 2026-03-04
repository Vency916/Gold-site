<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $guarded = [];

    /**
     * Scope a query to only include physical products (exclude digital proxies).
     */
    public function scopePhysical($query)
    {
        return $query->where('name', 'NOT LIKE', '%Institutional Digital%');
    }

    /**
     * Get the valuation of the asset (Static Admin Price).
     */
    public function getCurrentPriceAttribute()
    {
        return $this->base_price;
    }
}
