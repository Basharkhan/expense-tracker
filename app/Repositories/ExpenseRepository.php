<?php
namespace App\Repositories;

use App\Models\Expense;
use App\Repositories\Interfaces\ExpenseRepositoryInterface;

class ExpenseRepository implements ExpenseRepositoryInterface {
    public function create( array $data ): ?Expense {
        return Expense::create( $data );
    }

    public function update( int $id, array $data ): ?Expense {
        $expense = Expense::find( $id );
        if ( $expense ) {
            $expense->update( $data );
            return $expense;
        }
        return null;
    }

    public function findExpensesByUser( int $userId ): array {
        return Expense::where( 'user_id', $userId )->get()->toArray();
    }

    public function delete( int $id ): bool {
        return Expense::destroy( $id ) > 0;
    }

    public function findExpenseById( int $id ): ?Expense {
        return Expense::find( $id );
    }
}