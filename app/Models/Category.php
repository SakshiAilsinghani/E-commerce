<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Transformers\CategoryTransformer;

class Category extends Model
{
    use HasFactory,SoftDeletes;

    protected $hidden = [
        'deleted_at',
        'pivot'
    ];

    public $transformer = CategoryTransformer::class;

    protected $fillable = [
        'name',
        'description'
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class);
    }

}
