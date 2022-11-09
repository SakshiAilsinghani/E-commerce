<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Models\Buyer;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ProductBuyerTransactionsController extends ApiController
{
    public function index(Product $product, Buyer $buyer): JsonResponse
    {
        // Todo: Homework!
        $transactions = $product->transactions()->where('buyer_id',$buyer->id)->get();
        return $this->showAll($transactions);
    }
    public function store(Request $request, Product $product, User $buyer): JsonResponse
    {
        $rules = [
            'quantity' => 'required|integer|min:1'
        ];
        $this->validate($request, $rules);

        if($buyer->id === $product->seller_id) {
            throw new HttpException(409, "The buyer and seller cannot be same!");
        }

        if(! $buyer->isVerified()) {
            throw new HttpException(409, "The buyer needs to be verified!");
        }
        if(! $product->seller->isVerified()) {
            throw new HttpException(409, "The seller needs to be verified!");
        }

        if(! $product->isAvailable()) {
            throw new HttpException(409, "Product is not available! Sorry!");
        }

        if($product->quantity < $request->quantity) {
            throw new HttpException(409, "Does not meet your requested quantity! Inventory running low!");
        }

//        DB::beginTransaction();
//
//        DB::commit();
        return DB::transaction(function () use($request, $product, $buyer) {
            $product->quantity -= $request->quantity;
            $product->save();

            $transaction = Transaction::create([
                'product_id' => $product->id,
                'buyer_id' => $buyer->id,
                'quantity' => $request->quantity
            ]);

            return $this->showOne($transaction, 201);
        });
    }
}
