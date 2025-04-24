<?php
namespace App\Repositories\Interfaces;

use App\Models\Budget;

interface BudgetRepositoryInterface {
    public function findById( int $id ): ?Budget;

    public function create( array $data ): ?Budget;

    public function update( int $id, array $data ): bool;

    public function delete( int $id ): bool;

    public function findByUserIdAndMonth( int $userId, int $month ): ?Budget;

    public function findByUserId( int $userId ): ?Budget;
}