<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\BudgetService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

class BudgetController extends Controller {
    public function __construct( protected BudgetService $budgetService ) {
    }

    /**
    * Create a new budget.
    */

    public function store( Request $request ): JsonResponse {
        try {
            $validatedData = $request->validate( [
                'amount' => 'required|numeric|min:0|decimal:2|max:99999999.99',
                'month' => 'required|integer|between:1,12',
                'year' => 'required|integer|digits:4|min:1900|max:' . ( date( 'Y' ) + 5 )
            ] );

            $userId = auth()->id();
            $budget = $this->budgetService->createBudget( $userId, $validatedData );

            return response()->json( [
                'success' => true,
                'data' => $budget,
                'message' => 'Budget created successfully',
            ], Response::HTTP_CREATED );
        } catch( ValidationException $e ) {
            return response()->json( [
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY );
        } catch( Exception $e ) {
            return response()->json( [
                'success' => false,
                'message' => 'Failed to create budget',
                'error' => config( 'app.debug' ) ? $e->getMessage() : null
            ], Response::HTTP_INTERNAL_SERVER_ERROR );
        }

    }

    /**
    * Get budgets by user ID.
    */

    public function getBudgetsByUserId(): JsonResponse {
        try {
            $userId = auth()->id();
            $budgets = $this->budgetService->getBudgetsByUserId( $userId );

            return response()->json( [
                'success' => true,
                'data' => $budgets,
                'message' => empty( $budgets ) ? 'No budgets found' : 'Budgets fetched successfully',
            ], Response::HTTP_OK );
        } catch( Exception $e ) {
            return response()->json( [
                'success' => false,
                'message' => 'Failed to retrieve budgets',
                'error' => config( 'app.debug' ) ? $e->getMessage() : null
            ], Response::HTTP_INTERNAL_SERVER_ERROR );
        }

    }
}
