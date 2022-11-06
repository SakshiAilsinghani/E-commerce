<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    const UNAVAILABLE_PRODUCT = 0;
    const AVAILABLE_PRODUCT = 1;

    protected $fillable = [
        'name',
        'description',
        'quantity',
        'image',
        'status',
        'seller_id'
    ];

    /*
     * RELATIONSHIP METHODS
     */
    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
    public function seller()
    {
        return $this->belongsTo(Seller::class);
    }

    /*
     * HELPER METHODS
     */
    public function isAvailable()
    {
        return $this->status === Product::AVAILABLE_PRODUCT;
    }

}
