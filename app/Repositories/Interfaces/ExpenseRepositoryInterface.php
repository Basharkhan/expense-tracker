<?php
namespace App\Repositories\Interfaces;

use App\Models\Expense;

interface ExpenseRepositoryInterface {
    public function create( array $data ): ?Expense;

    public function update( int $id, array $data ): ?Expense;

    public function findExpensesByUser( int $userId ): array;

    public function delete( int $id ): bool;

    public function findExpenseById( int $id ): ?Expense;
}