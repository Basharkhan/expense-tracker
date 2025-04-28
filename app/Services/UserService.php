<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Support\Facades\Hash;

class UserService {
    public function __construct( protected UserRepositoryInterface $userRepositoryInterface ) {
    }

    public function getAllUsers(): iterable {
        return $this->userRepositoryInterface->all();
    }

    public function getUserById( int $id ) {
        return $this->userRepositoryInterface->findById( $id );
    }

    public function register( array $data ): User {
        $data[ 'password' ] = bcrypt( $data[ 'password' ] );
        return $this->userRepositoryInterface->create( $data );
    }

    public function authenticate( array $data ): ?User {
        $user = $this->userRepositoryInterface->findByEmail( $data[ 'email' ] );

        if ( $user && Hash::check( $data[ 'password' ], $user->password ) ) {
            return $user;
        }
        return null;
    }

    public function updateUser( int $id, array $data ): bool {
        return $this->userRepositoryInterface->update( $id, $data );
    }

    public function deleteUser( int $id ): bool {
        return $this->userRepositoryInterface->delete( $id );
    }
}