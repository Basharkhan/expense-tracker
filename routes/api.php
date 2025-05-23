<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BudgetController;
use App\Http\Controllers\Api\ExpenseController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

Route::post( '/register', [ AuthController::class, 'register' ] );
Route::post( '/login',    [ AuthController::class, 'login' ] );

Route::middleware( 'auth:sanctum' )->group( function () {
    // users
    Route::post( '/logout', [ AuthController::class, 'logout' ] );
    Route::get( '/users', [ UserController::class, 'index' ] );
    Route::get( '/users/{id}', [ UserController::class, 'show' ] );
    Route::put( '/users/{id}', [ UserController::class, 'update' ] );
    Route::delete( '/users/{id}', [ UserController::class, 'destroy' ] );

    // budgets
    Route::post('/budgets', [BudgetController::class, 'store'] );    
    Route::get('/budgets', [BudgetController::class, 'getBudgets'] );

    // expenses
    Route::post('/expenses', [ExpenseController::class, 'store'] );
    Route::put('/expenses/{id}', [ExpenseController::class, 'update'] );
    Route::get('/expenses', [ExpenseController::class, 'index'] );
    Route::delete('/expenses/{id}', [ExpenseController::class, 'delete'] );
} );
