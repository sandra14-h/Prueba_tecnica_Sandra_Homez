<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Firebase\JWT\JWT;
use Firebase\JWT\Key; 

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Credenciales incorrectas'
            ], 401);
        }

        $payload = [
        'sub' => $user->id,
        'email' => $user->email,
        'iat' => time(),
        'exp' => time() + (60 * 60)
    ];

    $key = env('JWT_SECRET');
    
    $token = JWT::encode($payload, $key, 'HS256');

    return response()->json([
        'token' => $token,
        'type' => 'Bearer'
    ]);
    }
}
