<?php
namespace App\Repositories;

use App\Models\Budget;
use App\Repositories\Interfaces\BudgetRepositoryInterface;

class BudgetRepository implements BudgetRepositoryInterface {
    public function all(): iterable {
        return Budget::all();
    }

    public function findById( int $id ): ?Budget {
        return Budget::find( $id );
    }

    public function create( array $data ): ?Budget {
        return Budget::create( $data );
    }

    public function update( int $id, array $data ): bool {
        $budget = Budget::find( $id );
        return $budget ? $budget->update( $data ): false;
    }

    public function delete( int $id ): bool {
        $budget = Budget::find( $id );
        return $budget ? $budget->delete(): false;
    }

    public function findByUserIdAndMonth( int $userId, int $month ): ?Budget {
        return Budget::where( 'user_id', $userId )
        ->where( 'month', $month )
        ->first();
    }

    public function findByUserId( int $userId ): ?Budget {
        return Budget::where( 'user_id', $userId )->first();
    }
}