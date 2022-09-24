<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', Password::defaults()],
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json([
                'data' => null,
                'errors' => $errors
            ], 400);
        }

        $validated = $validator->validated();

        if (! Auth::attempt($validated)) {
            return response()->json([
                'data' => null,
                'message' => 'credentials not match'
            ], 401);
        }

        return response()->json([
            'message' => 'success',
            'data' => [
                'access_token' => auth()->user()->createToken('access_token')->plainTextToken
            ],
        ]);
    }

    public function logout()
    {
        auth()->user()->tokens()->delete();

        return response()->json([
            'data' => null,
            'message' => 'Tokens deleted'
        ]);
    }
}
