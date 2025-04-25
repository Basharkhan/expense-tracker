<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\BudgetService;
use Exception;
use Illuminate\Http\Request;

class BudgetController extends Controller {
    public function __construct( protected BudgetService $budgetService ) {
    }

    public function createBudget( Request $request ) {
        $validatedData = $request->validate( [
            'amount' => 'required|numeric|min:0|decimal:2|max:99999999.99',
            'month' => 'required|integer|between:1,12',
            'year' => 'required|integer|digits:4|min:1900|max:' . ( date( 'Y' ) + 5 )
        ] );

        try {
            $budget = $this->budgetService->createBudget( auth()->id(), $validatedData );

            return response()->json( [
                'message' => 'Budget created successfully',
                'budget'  => $budget,
            ] );

        } catch( Exception $e ) {
            return response()->json( [
                'message' => $e->getMessage(),
            ], 400 );
        }

    }

    public function getBudgetsByUserId() {
        $budgets = $this->budgetService->getBudgetsByUserId( auth()->id() );

        return $budgets ? response()->json( [
            'message' => 'Budgets retrieved successfully',
            'budgets' => $budgets,
        ] ) : response()->json( [
            'message' => 'No budgets found',
        ], 404 );
    }
}
