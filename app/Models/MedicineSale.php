<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicineSale extends Model
{
    use HasFactory;

    protected $fillable = [
        'medicine_id',
        'quantity',
        'sale_date',
        'total_price',
        'pharmacist_id',
    ];

    protected $casts = [
        'sale_date' => 'date',
        'total_price' => 'decimal:2',
    ];

    public function medicine()
    {
        return $this->belongsTo(Medicine::class);
    }

    public function pharmacist()
    {
        return $this->belongsTo(User::class, 'pharmacist_id');
    }
}
