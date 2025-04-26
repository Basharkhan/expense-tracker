<?php
namespace App\Repositories\Interfaces;

use App\Models\Expense;

interface ExpenseRepositoryInterface {
    public function create( array $data ): ?Expense;

    public function findExpensesByUser( int $userId ): array;

}