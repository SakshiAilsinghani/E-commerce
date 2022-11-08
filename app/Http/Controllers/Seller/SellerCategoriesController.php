<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\ApiController;

use App\Models\Seller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SellerCategoriesController extends ApiController
{
    public function index(Seller $seller): JsonResponse
    {
        $categories = $seller->products()
                            ->with('categories')
                            ->get()
                            ->pluck('categories')
                            ->flatten()
                            ->unique()
                            ->values();

        return $this->showAll($categories);
    }
}
