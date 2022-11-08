<?php

namespace App\Http\Controllers\Category;

use App\Http\Controllers\ApiController;

use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CategoryBuyersController extends ApiController
{
    public function index(Category $category): JsonResponse
    {
        $buyers = $category->products()
            ->whereHas('transactions')
            ->with('transactions.buyer')
            ->get()
            ->pluck('transactions')
            ->flatten()
            ->pluck('buyer')
            ->unique()
            ->values();
        return $this->showAll($buyers);
    }
}
