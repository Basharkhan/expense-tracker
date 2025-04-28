<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller {
    public function __construct( protected UserService $userService ) {
    }

    /**
    * Register a new user.
    */

    public function register( Request $request ) {
        try {
            $data = $request->validate( [
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:6|confirmed',
            ] );

            $user = $this->userService->register( $data );
            $token = $user->createToken( 'auth_token' )->plainTextToken;

            return response()->json( [
                'success' => true,
                'message' => 'User registered successfully',
                'user' => $user,
                'token' => $token,
            ], Response::HTTP_CREATED );
        } catch ( ValidationException $e ) {
            return response()->json( [
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY );
        } catch ( \Exception $e ) {
            return response()->json( [
                'success' => false,
                'message' => 'Failed to register user',
                'error' => config( 'app.debug' ) ? $e->getMessage() : null
            ], Response::HTTP_INTERNAL_SERVER_ERROR );
        }

    }

    /**
    * Login user
    */

    public function login( Request $request ) {
        try {
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
                'success' => true,
                'message' => 'User logged in successfully',
                'user' => $user,
                'token' => $token,
            ], Response::HTTP_OK );
        } catch ( ValidationException $e ) {
            return response()->json( [
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY );
        } catch ( \Exception $e ) {
            return response()->json( [
                'success' => false,
                'message' => 'Failed to login',
                'error' => config( 'app.debug' ) ? $e->getMessage() : null
            ], Response::HTTP_INTERNAL_SERVER_ERROR );
        }

    }

    /**
    * Logout user
    */

    public function logout( Request $request ) {
        try {
            $request->user()->currentAccessToken()->delete();
        } catch ( \Exception $e ) {
            return response()->json( [
                'success' => false,
                'message' => 'Failed to logout',
                'error' => config( 'app.debug' ) ? $e->getMessage() : null
            ], 500 );
        }
    }
}
