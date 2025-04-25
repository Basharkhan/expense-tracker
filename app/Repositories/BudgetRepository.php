<?php
namespace App\Repositories;

use App\Models\Budget;
use App\Repositories\Interfaces\BudgetRepositoryInterface;

class BudgetRepository implements BudgetRepositoryInterface {
    public function createBudget( array $data ): ?Budget {
        return Budget::create( $data );
    }

    public function existsBudgetForMonth( int $userId, int $month, int $year ): bool {
        return Budget::where( 'user_id', $userId )
        ->where( 'month', $month )
        ->where( 'year', $year )
        ->exists();
    }

    public function getBudgetsByUserId( int $userId ): array {
        return Budget::where( 'user_id', $userId )
        ->orderBy( 'year', 'desc' )
        ->orderBy( 'month', 'desc' )
        ->get()
        ->toArray();
    }
}