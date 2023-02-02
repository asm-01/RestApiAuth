<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class AuthenticationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login','register']]);
    }

    public function register(RegisterRequest $request)
    {
        $user = User::create([
              'name' => $request->name,
              'email' => $request->email,
              'password' => Hash::make($request->password)
          ]);

        $token = Auth::login($user);

        return $request->wantsJson()
                    ? new JsonResponse([
                          'message' => 'Account created successfully.',
                          'user' => $user,
                          'authorization' => [
                                'token' => $token,
                                'type' => 'bearer',
                            ]
                      ], 200)
                    : null;
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');

        $token = Auth::attempt($credentials);
        if (!$token) {
            return response()->json([
                'message' => 'Something went wrong. The credentials doesn\'t match our records.',
            ], 401);
        }

        $user = Auth::user();

        return $request->wantsJson()
                    ? new JsonResponse([
                          'message' => 'You have been successfully logged in.',
                          'user' => $user,
                          'authorization' => [
                                'token' => $token,
                                'type' => 'bearer',
                            ]
                      ], 200)
                    : null;
    }

    public function logout(Request $request)
    {
        Auth::logout();
        return $request->wantsJson()
                    ? new JsonResponse([
                          'message' => 'Successfully logged out.'
                      ], 200)
                    : null;
    }

    public function refresh(Request $request)
    {
        return $request->wantsJson()
                    ? new JsonResponse([
                          'message' => 'Successfully generated new token.',
                          'user' => Auth::user(),
                          'authorization' => [
                                'token' => Auth::refresh(),
                                'type' => 'bearer',
                            ]
                      ], 200)
                    : null;
    }
    
}
