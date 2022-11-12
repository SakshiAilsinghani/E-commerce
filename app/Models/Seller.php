<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Scopes\SellerScope;
use App\Transformers\SellerTransformer;
use Illuminate\Database\Eloquent\Model;

class Seller extends User
{
    use HasFactory;
    protected $table = "users";

    public $transformer = SellerTransformer::class;

    protected $hidden = [
        'deleted_at',
    ];


    protected static function boot()
    {
        parent::boot();
        self::addGlobalScope(new SellerScope());
    }


    public function products()
    {
        return $this->hasMany(Product::class);
    }

}
