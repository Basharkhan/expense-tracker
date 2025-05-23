<?php
namespace App\Services;

use App\Models\Expense;
use App\Repositories\BudgetRepository;
use App\Repositories\ExpenseRepository;
use Illuminate\Support\Facades\DB;

class ExpenseService {
    public function __construct( protected ExpenseRepository $expenseRepository,
    protected BudgetRepository $budgetRepository ) {
    }

    public function createExpense( int $userId, array $data ): ?Expense {
        return DB::transaction( function () use ( $userId, $data ) {
        $month = date( 'm', strtotime( $data[ 'date' ] ) );
        $year = date( 'Y', strtotime( $data[ 'date' ] ) );
        $budget = $this->budgetRepository->findBudgetForMonth( $userId, $month, $year );

        if ( !$budget ) {
            throw new \Exception( 'No budget found for the specified month and year.' );
        }

        if ( $data[ 'amount' ] > $budget->amount ) {
            throw new \Exception( 'Expense exceeds the budget amount.' );
        }

        $data[ 'user_id' ] = $userId;
        $data[ 'budget_id' ] = $budget->id;
        $expense = $this->expenseRepository->create( $data );
        $this->budgetRepository->decreaseAmount( $budget->id, $data[ 'amount' ] );
        return $expense;
        } );        
    }

    public function updateExpense( int $id, array $data ): ?Expense {
        return DB::transaction( function () use ( $id, $data ) {
        $expense = $this->expenseRepository->findExpenseById( $id );
        if ( !$expense ) {
            throw new \Exception( 'Expense not found.' );
        }

        $month = date( 'm', strtotime( $data[ 'date' ] ) );
        $year = date( 'Y', strtotime( $data[ 'date' ] ) );
        $budget = $this->budgetRepository->findBudgetForMonth( $expense->user_id, $month, $year );

        if ( !$budget ) {
            throw new \Exception( 'No budget found for the specified month and year.' );
        }

        $newBudget = $budget->amount + $expense->amount - $data[ 'amount' ];
        if ( $newBudget < 0 ) {
            throw new \Exception( 'Expense exceeds the budget amount.' );   
        }
        
        $this->budgetRepository->updateAmount( $budget->id, $newBudget );
        $expense = $this->expenseRepository->update( $id, $data );
        return $expense;
        } );        
    }

    public function getExpensesByUser( int $userId ): array {
        return $this->expenseRepository->findExpensesByUser( $userId );
    }    

    public function deleteExpense( int $id ): bool {
        return $this->expenseRepository->delete( $id );
    }

    public function getExpenseById( int $id ): ?Expense {
        return $this->expenseRepository->findExpenseById( $id );
    }
}