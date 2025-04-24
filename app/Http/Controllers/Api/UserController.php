<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller {
    public function __construct( protected UserService $userService ) {

    }

    public function index(): JsonResponse {
        return response()->json( $this->userService->getAllUsers() );
    }

    public function show( $id ): JsonResponse {
        return response()->json( $this->userService->getUserById( $id ) );
    }

    public function update( Request $request, $id ): JsonResponse {
        $validatedData = $request->validate( [
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|string|email|max:255|unique:users,email,' . $id,
            'password' => 'sometimes|required|string|min:6',
        ] );

        return response()->json( $this->userService->updateUser( $id, $validatedData ) );
    }

    public function destroy( $id ): JsonResponse {
        return response()->json( $this->userService->deleteUser( $id ) );
    }
}
