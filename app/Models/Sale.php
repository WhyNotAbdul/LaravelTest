<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;

    protected $fillable = [
        'quantity',
        'unit_cost',
        'selling_price',
        'product_id',
        'sold_at',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
