<?php
namespace App\Traits;

use Illuminate\Database\Eloquent\Collection;
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
        $responseParams = ['data' => $collection, 'count' => $collection->count()];
        return $this->successResponse($responseParams, $statusCode);
    }
    protected function showOne(Model $model, $statusCode = 200)
    {
        $responseParams = ['data' => $model];
        return $this->successResponse($responseParams, $statusCode);
    }

}

