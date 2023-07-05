<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class JsonRequestMiddleware
{

    public function handle(Request $request, Closure $next)
    {

        if (
            in_array($request->method(), ['POST', 'PUT', 'PATCH'])
            && $request->isJson()
        ) {
            $data = $request->json()->all();
            $request->request->replace(is_array($data) ? $data : []);
        } else {
            return response()->json(['metadata' => ['message' => 'Request body harus dalam format json', 'code' => 201]], 201);
        }

        return $next($request);
    }
}
