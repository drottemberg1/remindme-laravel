<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Cache;
use App\Models\User;


class ValidateAccessToken
{
    public function handle(Request $request, Closure $next)
    {
        // Extract Bearer token
        $token = $request->bearerToken();

        if (!$token) {
            return response()->json([
                'ok' => false,
                'err' => 'ERR_MISSING_ACCESS_TOKEN',
                'msg' => 'Access token is missing',
            ], 401);
        }

        // Retrieve user ID from cache
        $userId = Cache::get("access_token:{$token}");

        if (!$userId) {
            return response()->json([
                'ok' => false,
                'err' => 'ERR_INVALID_ACCESS_TOKEN',
                'msg' => 'Access token is invalid or expired',
            ], 401);
        }

        // Find the user
        $user = User::find($userId);

        if (!$user) {
            return response()->json([
                'ok' => false,
                'err' => 'ERR_USER_NOT_FOUND',
                'msg' => 'User not found for the provided token',
            ], 404);
        }

        // Attach the user to the request
        $request->setUserResolver(function () use ($user) {
            return $user;
        });

        return $next($request);
    }
}
