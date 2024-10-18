<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Exception;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Illuminate\Http\JsonResponse;


class CustomJWTMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param  Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        try {
            // Attempt to authenticate the user via the token
            $user = JWTAuth::parseToken()->authenticate();
        } catch (TokenExpiredException $e) {
            // Token has expired
            return new JsonResponse([
                'success' => false,
                'message' => 'Token has expired',
            ], 401);
        } catch (TokenInvalidException $e) {
            // Token is invalid
            return new JsonResponse([
                'success' => false,
                'message' => 'Token is invalid',
            ], 401);
        } catch (Exception $e) {
            // No token was found
            return new JsonResponse([
                'success' => false,
                'message' => 'Authorization token not found',
            ], 401);
        }

        // If token is valid, proceed with the request
        return $next($request);
    }
}
