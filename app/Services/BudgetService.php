<?php

use App\Models\Budget;
use App\Repositories\BudgetRepository;

class BudgetService {
    public function __construct( protected BudgetRepository $budgetRepository ) {

    }

    public function createForUser( int $userId, array $data ): ?Budget {
        $data[ 'user_id' ] = $userId;
        return $this->budgetRepository->create( $data );
    }

}