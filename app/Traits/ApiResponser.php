<?php
namespace App\Traits;


use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;

trait ApiResponser
{
    private function successResponse($responseParams, $statusCode = 200)
    {
        return response()->json($responseParams, $statusCode);
    }
    protected function errorResponse($message, $statusCode)
    {
        return response()->json(['error' => $message, 'code' => $statusCode], $statusCode);
    }

    protected function showAll(Collection $collection, $statusCode = 200)
    {
        if($collection->isEmpty()) {
            return $this->successResponse(['data' => $collection], $statusCode);
        }

        $transformer = $collection->first()->transformer;
        $collection = $this->filterData($collection, $transformer);
        $collection = $this->sort($collection, $transformer);
        $collection = $this->transformData($collection, $transformer);

        $responseParams = ['data' => $collection, 'count' => $collection->count()];
        return $this->successResponse($responseParams, $statusCode);
    }
    protected function showOne(Model $model, $statusCode = 200)
    {
        $transformer = $model->transformer;
        $model = $this->transformData($model, $transformer);
        return $this->successResponse(['data' => $model], $statusCode);

    }

    protected function showMessage(string $message, int $statusCode = 200)
    {
        $responseParams = ['data' => $message];
        return $this->successResponse($responseParams, $statusCode);
    }

    # Note: 'data' key is passed automatically by transformer.
    private function transformData($data, $transformer)
    {
        $transformation = fractal($data, new $transformer);
        return collect($transformation->toArray()['data']);
    }

    private function filterData(Collection $collection, $transformer): Collection
    {
        foreach(request()->query() as $key => $value) {
            $actualAttribute = $transformer::attributeMapper($key);
            if(isset($actualAttribute, $value)) {
                $collection = $collection->where($actualAttribute, $value);
            }
        }

        return $collection;
    }


    private function sort(Collection $collection, $transformer): Collection
    {
        if(request()->has('sort_by')) {
            $transformedAttribute = request()->sort_by;
            $sortAttribute = $transformer::attributeMapper($transformedAttribute);
            if(request()->has('order_by') && request()->order_by === 'desc') {
                $collection = $collection->sortByDesc($sortAttribute);
            } else {
                $collection = $collection->sortBy($sortAttribute);
            }
        }
        return $collection;
    }

    



}

