<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\ExpenseService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Exception;
use Illuminate\Http\JsonResponse;

class ExpenseController extends Controller {
    public function __construct( protected ExpenseService $expenseService ) {
    }

    /**
    * Create a new expense.
    */

    public function store( Request $request ): JsonResponse {

        try {
            $validatedData = $request->validate( [
                'amount' => 'required|numeric|min:0|decimal:2|max:99999999.99',
                'category' => 'required|string|max:255',
                'date' => 'required|date_format:Y-m-d',
                'description' => 'nullable|string|max:255',
            ] );

            $userId = auth()->id();
            $expense = $this->expenseService->createExpense( $userId, $validatedData );

            return response()->json( [
                'success' => true,
                'data' => $expense,
                'message' => 'Expense created successfully',
            ], Response::HTTP_CREATED );
        } catch ( ValidationException $e ) {
            return response()->json( [
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY );
        } catch ( Exception $e ) {
            return response()->json( [
                'success' => false,
                'message' => 'Failed to create expense',
                'error' => config( 'app.debug' ) ? $e->getMessage() : null
            ], Response::HTTP_INTERNAL_SERVER_ERROR );
        }
    }

    /*
    * Get all expenses for the authenticated user.
    */

    public function index(): JsonResponse {
        try {
            $userId = auth()->id();
            $expenses = $this->expenseService->getExpensesByUser( $userId );

            return response()->json( [
                'success' => true,
                'data' => $expenses,
                'message' => empty( $expense ) ? 'Expenses fetched successfully' : 'No expenses found',
            ], Response::HTTP_OK );
        } catch ( Exception $e ) {
            return response()->json( [
                'success' => false,
                'message' => 'Failed to retrieve expenses',
                'error' => config( 'app.debug' ) ? $e->getMessage() : null
            ], Response::HTTP_INTERNAL_SERVER_ERROR );
        }
    }

    public function delete( int $id ): JsonResponse {
        try {
            $expense = $this->expenseService->getExpenseById( $id );

            if ( empty( $expense ) ) {
                return response()->json( [
                    'success' => false,
                    'message' => 'No expenses found',
                ], Response::HTTP_NOT_FOUND );
            }

            if ( auth()->id() !== $expense->user_id ) {
                return response()->json( [
                    'success' => false,
                    'message' => 'You are not authorized to delete this expense',
                ], Response::HTTP_FORBIDDEN );
            }

            $result = $this->expenseService->deleteExpense( $id );

            if ( !$result ) {
                return response()->json( [
                    'success' => false,
                    'message' => 'Expense not found or already deleted'
                ], Response::HTTP_NOT_FOUND );
            }

            return response()->json( [
                'success' => true,
                'message' => 'Expense deleted successfully'
            ], Response::HTTP_OK );
        } catch ( Exception $e ) {
            return response()->json( [
                'success' => false,
                'message' => 'Failed to delete expense',
                'error' => config( 'app.debug' ) ? $e->getMessage() : null
            ], Response::HTTP_INTERNAL_SERVER_ERROR );
        }
    }
}
