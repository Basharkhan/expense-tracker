<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;

class UserRepository implements  UserRepositoryInterface {
    public function all(): iterable {
        return User::all();
    }

    public function findById( int $id ): ?User {
        return User::find( $id );
    }

    public function findByEmail( string $email ): ?User {
        return User::where( 'email', $email )->first();
    }

    public function create( array $data ): ?User {
        return User::create( $data );
    }

    public function update( int $id, array $data ): bool {
        $user = User::find( $id );
        return $user ? $user->update( $data ): false;
    }

    public function delete( int $id ): bool {
        $user = User::find( $id );
        return $user ? $user->delete(): false;
    }
}