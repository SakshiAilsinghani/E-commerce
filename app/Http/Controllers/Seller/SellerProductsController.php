<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\ApiController;
use App\Models\Product;
use App\Models\Seller;
use App\Models\User;
use App\Traits\ApiResponser;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

class SellerProductsController extends ApiController
{
    public function index(Seller $seller): JsonResponse
    {
        $products = $seller->products;
        return $this->showAll($products);
    }

    public function store(Request $request, User $seller): JsonResponse
    {
        $rules = [
            'name' => 'required|min:3',
            'description' => 'required|min:5',
            'quantity' => 'required|integer|min:1',
            'image' => 'required|image'
        ];

        $this->validate($request, $rules);
        $data = $request->all();
        $data['status'] = Product::UNAVAILABLE_PRODUCT;
      //        dd($request);
      $data['image'] = $request->image->store('');

        $data['seller_id'] = $seller->id;

        $product = Product::create($data);
        return $this->showOne($product, 201);
    }

    public function update(Request $request, Seller $seller, Product $product): JsonResponse
    {
        // TODO: Validate the $seller is same as requesting user
        $rules = [
            'quantity' => 'integer|min:1',
            'status' => 'in:' . Product::UNAVAILABLE_PRODUCT . ',' . Product::AVAILABLE_PRODUCT,
            'image' => 'image'
        ];

        $this->validate($request, $rules);

        $this->verifySeller($seller, $product);

        $product->fill(
            $request->only([
                'name',
                'description',
                'quantity'
            ])
        );

        if($request->has('status')) {
            $product->status = (int)$request->status;

            if($product->isAvailable() && $product->categories()->count() === 0) {
                throw new HttpException(409, "A product must belong to atleast one category to be available!");
            }
        }

        if($request->hasFile('image')) {
            Storage::delete($product->image);
            $product->image = $request->image->store('');
        }


        if($product->isClean()) {
            throw new HttpException(422,"You have not updated anything");
        }

        $product->save();
        return $this->showOne($product);
    }

    public function destroy(Seller $seller, Product $product)
    {
        $this->verifySeller($seller, $product);
        $product->delete();
        return $this->showOne($product, 204);
    }

    private function verifySeller(Seller $seller, Product $product)
    {
        if($seller->id !== $product->seller_id) {
            throw new HttpException(422, "You are trying to update someone else's product");
        }
    }
}
