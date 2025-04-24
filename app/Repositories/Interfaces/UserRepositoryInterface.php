<?php

namespace App\Repositories\Interfaces;
use App\Models\User;

interface UserRepositoryInterface {
    public function all(): iterable;

    public function findById( int $id ): ?User;

    public function findByEmail( string $email ): ?User;

    public function create( array $data ): ?User;

    public function update( int $id, array $data ): bool;

    public function delete( int $id ): bool;
}