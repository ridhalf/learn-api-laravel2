<?php

namespace App\Http\Middleware;

use Closure;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;

class JwtMiddleware
{

    public function handle($request, Closure $next, $guard = null)
    {
        $token      = trim($request->header('x-token'));
        $email   = trim($request->header('x-email'));

        if (!$token) {
            return response()->json([
                'metadata' => [
                    'message' => 'x-token tidak ditemukan pada request header',
                    'code' => 201
                ]
            ], 201);
        } else {
            $request->headers->set('Authorization', 'Bearer ' . $token);
        }

        if (!$email) {
            return response()->json([
                'metadata' => [
                    'message' => 'x-email tidak ditemukan pada request header',
                    'code' => 201
                ]
            ], 201);
        }

        try {
            if (!$user = JWTAuth::parseToken()->authenticate()) {
                return response()->json([
                    'metadata' => [
                        'message' => 'User tidak ditemukan',
                        'code' => 201
                    ]
                ], 201);
            }
        } catch (TokenExpiredException $e) {

            return response()->json([
                'metadata' => [
                    'message' => 'Token Expired',
                    'code' => 201
                ]
            ], 201);
        } catch (TokenInvalidException $e) {

            return response()->json([
                'metadata' => [
                    'message' => 'Token invalid',
                    'code' => 201
                ]
            ], 201);
        } catch (JWTException $e) {

            return response()->json([
                'metadata' => [
                    'message' => 'Token tidak ditemukan',
                    'code' => 201
                ]
            ], 201);
        }

        $request->auth = $user;

        return $next($request);
    }
}
