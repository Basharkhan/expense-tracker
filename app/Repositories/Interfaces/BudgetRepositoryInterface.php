<?php
namespace App\Repositories\Interfaces;

use App\Models\Budget;

interface BudgetRepositoryInterface {
    public function create( array $data ): ?Budget;

    public function findById( int $id ): ?Budget;

    public function existsBudgetForMonth( int $userId, int $month, int $year ): bool;

    public function findBudgetsByUser( int $userId ): array;

    public function decreaseAmount( int $budgetId, float $amount ): void;
}