<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class TransformInput
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, $transformer)
    {
        $transformedInput = [];
        foreach ($request->request->all() as $input => $value) {
            $transformedInput[$transformer::attributeMapper($input)] = $value;
        }
        $request->replace($transformedInput);
        $response =  $next($request);

        // After Middleware Code
        // Check whether the response is error response or not!
        // We are doing this because, we only need to transform if there is error and that too only Validation Error as of now!
        if(isset($response->exception) && $response->exception instanceof ValidationException) {
            $data = $response->getData(); // As per our error handler we should get 2 things here. One is 'error' key and other is 'code', where 'error' key will have all the attributes that caused error.

            $transformedErrors = [];
            foreach ($data->error as $field => $error) {
                $transformedAttribute = $transformer::getTransformedAttribute($field);
                $transformedErrors[$transformedAttribute] = str_replace($field, $transformedAttribute, $error);
            }

            $data->error = $transformedErrors;

            // Set the data in the response with new data
            $response->setData($data);
        }

        return $response;

    }
}
