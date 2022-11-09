<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ProductCategoriesController extends ApiController
{
    public function index(Product $product): JsonResponse
    {
        $categories = $product->categories;
        return $this->showAll($categories);
    }
    public function update(Request $request, Product $product, Category $category): JsonResponse
    {
        $product->categories()->syncWithoutDetaching([$category->id]);
        $categories = $product->categories;
        return $this->showAll($categories, 201);
    }
    public function destroy(Request $request, Product $product, Category $category): JsonResponse
    {
        if(! $product->categories()->find($category->id)) {
            throw new HttpException(404, "The product does not belong to specified category!");
        }

        $product->categories()->detach($category->id);
        $categories = $product->categories;
        return $this->showAll($categories);
    }
}
