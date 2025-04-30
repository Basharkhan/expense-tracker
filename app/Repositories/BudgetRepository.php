<?php
namespace App\Repositories;

use App\Models\Budget;
use App\Repositories\Interfaces\BudgetRepositoryInterface;

class BudgetRepository implements BudgetRepositoryInterface {
    public function create( array $data ): ?Budget {
        return Budget::create( $data );
    }

    public function update( int $id, array $data ): ?Budget {
        $budget = Budget::find( $id );
        if ( $budget ) {
            $budget->update( $data );
            return $budget;
        }
        return null;
    }

    public function updateAmount( int $id, float $amount ): ?Budget {
        $budget = Budget::find( $id );
        if ( $budget ) {
            $budget->amount = $amount;
            $budget->save();
            return $budget;
        }
        return null;
    }

    public function findById( int $id ): ?Budget {
        return Budget::find( $id );
    }

    public function existsBudgetForMonth( int $userId, int $month, int $year ): bool {
        return Budget::where( 'user_id', $userId )
        ->where( 'month', $month )
        ->where( 'year', $year )
        ->exists();
    }

    public function findBudgetForMonth( int $userId, int $month, int $year ): ?Budget {
        return Budget::where( 'user_id', $userId )
        ->where( 'month', $month )
        ->where( 'year', $year )
        ->first();
    }

    public function findBudgetsByUser( int $userId ): array {
        return Budget::where( 'user_id', $userId )
        ->orderBy( 'year', 'desc' )
        ->orderBy( 'month', 'desc' )
        ->get()
        ->toArray();
    }

    public function decreaseAmount( int $budgetId, float $amount ): void {
        $updatedBudget = Budget::where( 'id', $budgetId )->where( 'amount', '>=', $amount )
        ->decrement( 'amount', $amount );

        if ( $updatedBudget === 0 ) {
            throw new \RuntimeException(
                'Budget update failed: Insufficient balance or invalid budget'
            );
        }
    }

}