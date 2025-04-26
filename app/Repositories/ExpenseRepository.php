<?php
namespace App\Repositories;

use App\Models\Expense;
use App\Repositories\Interfaces\ExpenseRepositoryInterface;

class ExpenseRepository implements ExpenseRepositoryInterface {
    public function create( array $data ): ?Expense {
        return Expense::create( $data );
    }

    public function findExpensesByUser( int $userId ): array {
        return Expense::where( 'user_id', $userId )->get()->toArray();
    }
}