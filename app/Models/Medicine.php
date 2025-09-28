<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Medicine extends Model
{
    protected $fillable = [
        'name',
        'generic_name',
        'manufacturer',
        'quantity',
        'minimum_stock_level',
        'unit_price',
        'expiry_date',
        'batch_number',
        'description',
        'category',
        'is_public',
        'symptoms_treated',
        'views_count',
        'sales_count'
    ];

    protected $casts = [
        'expiry_date' => 'date',
        'unit_price' => 'decimal:2',
        'is_public' => 'boolean'
    ];

    // Check if medicine is low in stock
    public function isLowStock()
    {
        return $this->quantity <= $this->minimum_stock_level;
    }

    // Check if medicine is expiring soon (within 30 days)
    public function isExpiringSoon()
    {
        return $this->expiry_date <= Carbon::now()->addDays(30);
    }

    // Check if medicine is expired
    public function isExpired()
    {
        return $this->expiry_date < Carbon::now();
    }

    // Get stock status
    public function getStockStatusAttribute()
    {
        if ($this->isExpired()) {
            return 'expired';
        } elseif ($this->isExpiringSoon()) {
            return 'expiring_soon';
        } elseif ($this->isLowStock()) {
            return 'low_stock';
        }
        return 'normal';
    }

    public function sales()
    {
        return $this->hasMany(MedicineSale::class);
    }

    public function incrementViews()
    {
        $this->increment('views_count');
    }
}
