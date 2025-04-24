<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller {
    public function __construct( protected UserService $userService ) {
    }

    public function register( Request $request ) {
        $data = $request->validate( [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ] );

        $user = $this->userService->register( $data );
        $token = $user->createToken( 'auth_token' )->plainTextToken;

        return response()->json( [
            'user' => $user,
            'token' => $token,
        ] );
    }

    public function login( Request $request ) {
        $data = $request->validate( [
            'email' => 'required|string|email',
            'password' => 'required|string|min:6',
        ] );

        $user = $this->userService->authenticate( $data );

        if ( !$user ) {
            throw ValidationException::withMessages( [
                'email' => [ 'The provided credentials are incorrect.' ],
            ] );
        }

        $token = $user->createToken( 'auth_token' )->plainTextToken;

        return response()->json( [
            'user' => $user,
            'token' => $token,
        ] );
    }

    public function logout( Request $request ) {
        $request->user()->tokens()->delete();

        return response()->json( [
            'message' => 'Logged out',
        ] );
    }
}
