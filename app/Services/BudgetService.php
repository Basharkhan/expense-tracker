<?php
namespace App\Services;

use App\Models\Budget;
use App\Repositories\BudgetRepository;
use Exception;

class BudgetService {
    public function __construct( protected BudgetRepository $budgetRepository ) {

    }

    public function createBudget( int $userId, array $data ): ?Budget {
        if ( $this->budgetRepository->existsBudgetForMonth( $userId, $data[ 'month' ], $data[ 'year' ] ) ) {
            throw new Exception( 'Budget already exists for this month and year.' );
        }

        $data[ 'user_id' ] = $userId;
        return $this->budgetRepository->createBudget( $data );
    }

    public function getBudgetsByUserId( int $userId ): array {
        return $this->budgetRepository->getBudgetsByUserId( $userId );
    }
}