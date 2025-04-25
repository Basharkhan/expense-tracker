<?php
namespace App\Repositories\Interfaces;

use App\Models\Budget;

interface BudgetRepositoryInterface {
    public function createBudget( array $data ): ?Budget;

    public function existsBudgetForMonth( int $userId, int $month, int $year ): bool;

    public function getBudgetsByUserId( int $userId ): array;
}