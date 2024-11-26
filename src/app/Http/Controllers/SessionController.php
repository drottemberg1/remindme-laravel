<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;

class SessionController extends Controller
{

  public function logout(Request $request)
  {

      Cache::delete("access_token:{$request->bearerToken()}");
      return response()->json([
          'ok' => true,
      ]);

  }


    public function login(Request $request)
    {
        // Validate incoming request
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        // Check if the user exists
        $credentials = $request->only('email', 'password');
        $user = Auth::guard('api')->validate($credentials);


        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'ok' => false,
                'err' => 'ERR_INVALID_CREDS',
                'msg' => 'incorrect username or password'
            ], 401);
        }

        // Generate tokens
        $accessToken = Str::uuid();
        $refreshToken = Str::uuid();

        // Store tokens in cache
        Cache::put("access_token:{$accessToken}", $user->id, now()->addSeconds(10)); //20-second lifetime
        Cache::put("refresh_token:{$refreshToken}", $user->id, now()->addDays(30)); // Longer lifetime for refresh_token

        // Return success response
        return response()->json([
            'ok' => true,
            'data' => [
                'user' => $user,
                'access_token' => $accessToken,
                'refresh_token' => $refreshToken,
            ]
        ]);
    }

    public function checkToken(Request $request)
    {

      return response()->json([
          'ok' => true,
          'data' => [
              'user' => $request->user(),
              'access_token' =>  $request->bearerToken(),

          ]
      ]);

    }


    public function refreshToken(Request $request)
    {
        // Extract the refresh token from the Authorization header
        $authHeader = $request->header('Authorization');

        if (!$authHeader || !str_starts_with($authHeader, 'Bearer ')) {
            return response()->json([
                'ok' => false,
                'err' => 'ERR_INVALID_REFRESH_TOKEN',
                'msg' => 'invalid refresh token'
            ], 401);
        }

        $refreshToken = substr($authHeader, 7); // Remove "Bearer " prefix

        // Check if the refresh token exists in cache
        $userId = Cache::get("refresh_token:{$refreshToken}");

        if (!$userId) {
            return response()->json([
                'ok' => false,
                'err' => 'ERR_INVALID_REFRESH_TOKEN',
                'msg' => 'invalid refresh token'
            ], 401);
        }

        // Generate a new access token
        $newAccessToken = Str::uuid();

        // Store the new access token with a 20-second lifetime
        Cache::put("access_token:{$newAccessToken}", $userId, now()->addSeconds(20));

        // Return the new access token
        return response()->json([
            'ok' => true,
            'data' => [
                'access_token' => $newAccessToken
            ]
        ]);
    }
}
