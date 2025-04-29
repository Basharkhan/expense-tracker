<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\UserService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

class UserController extends Controller {
    public function __construct( protected UserService $userService ) {

    }

    /**
    * Get all users.
    */

    public function index(): JsonResponse {
        try {
            $users = $this->userService->getAllUsers();

            return response()->json( [
                'success' => true,
                'data' => $users,
                'message' => 'Users fetched successfully',
            ], Response::HTTP_OK );
        } catch ( Exception $e ) {
            return response()->json( [
                'success' => false,
                'message' => 'Failed to retrieve users',
                'error' => config( 'app.debug' ) ? $e->getMessage() : null
            ], Response::HTTP_INTERNAL_SERVER_ERROR );
        }
    }

    /**
    * Get single user
    */

    public function show( $id ): JsonResponse {

        try {
            $user = $this->userService->getUserById( $id );

            if ( !$user ) {
                return response()->json( [
                    'success' => false,
                    'message' => 'User not found',
                ], Response::HTTP_NOT_FOUND );
            }

            return response()->json( [
                'success' => true,
                'data' => $user,
                'message' => 'User fetched successfully',
            ] );
        } catch( Exception $e ) {
            return response()->json( [
                'success' => false,
                'message' => 'Failed to retrieve user',
                'error' => config( 'app.debug' ) ? $e->getMessage() : null
            ], Response::HTTP_INTERNAL_SERVER_ERROR );
        }
    }

    /**
    * Update user
    */

    public function update( Request $request, int $id ): JsonResponse {
        try {

            $user = $this->userService->getUserById( $id );

            if ( !$user ) {
                return response()->json( [
                    'success' => false,
                    'message' => 'User not found',
                ], Response::HTTP_NOT_FOUND );
            }

            if ( auth()->id() !== $id ) {
                return response()->json( [
                    'success' => false,
                    'message' => 'You are not authorized to update this user',
                ], Response::HTTP_FORBIDDEN );
            }

            $validatedData = $request->validate( [
                'name' => 'sometimes|required|string|max:255',
                'email' => 'sometimes|required|string|email|max:255|unique:users,email,' . $id,
                'password' => 'sometimes|required|string|min:6',
            ] );

            $user = $this->userService->updateUser( $id, $validatedData );

            return response()->json( [
                'success' => true,
                'data' => $user,
                'message' => 'User updated successfully',
            ] );

        } catch( ValidationException $e ) {
            return response()->json( [
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY );
        } catch( Exception $e ) {
            return response()->json( [
                'success' => false,
                'message' => 'Failed to update user',
                'error' => config( 'app.debug' ) ? $e->getMessage() : null
            ], Response::HTTP_INTERNAL_SERVER_ERROR );
        }
    }

    /**
    * Delete user
    */

    public function destroy( int $id ): JsonResponse {

        try {
            $user = $this->userService->getUserById( $id );

            if ( !$user ) {
                return response()->json( [
                    'success' => false,
                    'message' => 'User not found',
                ], Response::HTTP_NOT_FOUND );
            }

            if ( auth()->id() !== $id ) {
                return response()->json( [
                    'success' => false,
                    'message' => 'You are not authorized to delete this user',
                ], Response::HTTP_FORBIDDEN );
            }

            $result = $this->userService->deleteUser( $id );

            if ( !$result ) {
                return response()->json( [
                    'success' => false,
                    'message' => 'User not found or already deleted'
                ], Response::HTTP_NOT_FOUND );
            }

            return response()->json( [
                'success' => true,
                'message' => 'User deleted successfully'
            ] );
        } catch ( Exception $e ) {
            return response()->json( [
                'success' => false,
                'message' => 'Failed to delete user',
                'error' => config( 'app.debug' ) ? $e->getMessage() : null
            ], Response::HTTP_INTERNAL_SERVER_ERROR );
        }
    }
}
