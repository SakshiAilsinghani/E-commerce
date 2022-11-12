<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Transformers\TransactionTransformer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    use HasFactory,SoftDeletes;
    public $transformer = TransactionTransformer::class;
    protected $fillable = [
        'quantity',
        'buyer_id',
        'product_id'
    ];

    protected $hidden = [
        'deleted_at',
    ];


    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    public function buyer()
    {
        return $this->belongsTo(Buyer::class);
    }

}
