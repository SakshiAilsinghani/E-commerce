<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Scopes\BuyerScope;
use Illuminate\Database\Eloquent\Model;

class Buyer extends User
{
    use HasFactory;
    protected $table = "users";

    protected $hidden = [
        'deleted_at',
    ];


    protected static function boot()
    {
        parent::boot();
        self::addGlobalScope(new BuyerScope());
    }


    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

}
