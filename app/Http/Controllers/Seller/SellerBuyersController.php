<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\ApiController;
use App\Models\Seller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SellerBuyersController extends ApiController
{
    public function index(Seller $seller): JsonResponse
    {
        $buyers = $seller->products()
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
