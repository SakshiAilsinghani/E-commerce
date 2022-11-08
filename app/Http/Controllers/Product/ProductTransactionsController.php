<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\ApiController;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductTransactionsController extends ApiController
{
    public function index(Product $product): JsonResponse
    {
        $transactions = $product->transactions;
        return $this->showAll($transactions);
    }
}
