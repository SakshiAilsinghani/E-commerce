<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductBuyersController extends ApiController
{
    public function index(Product $product): JsonResponse
    {
        $buyers = $product->transactions()
                        ->with('buyer')
                        ->get()
                        ->pluck('buyer')
                        ->unique()
                        ->values();

        return $this->showAll($buyers);
    }
}
