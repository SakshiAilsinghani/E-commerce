<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory,SoftDeletes;
    const UNAVAILABLE_PRODUCT = 0;
    const AVAILABLE_PRODUCT = 1;

    protected $hidden = [
        'deleted_at',
        'pivotbu'
    ];


    protected $fillable = [
        'name',
        'description',
        'quantity',
        'image',
        'status',
        'seller_id'
    ];


    public static function boot()
    {
        parent::boot();
        self::updated(function (Product $product) {
            if($product->quantity === 0 && $product->isAvailable()) {
                $product->status = Product::UNAVAILABLE_PRODUCT;
                $product->save();
            }
        });
    }


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
