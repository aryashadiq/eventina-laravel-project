<?php

namespace App\Auth;

use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Contracts\Auth\Authenticatable as UserContract;

class AdminUserProvider extends EloquentUserProvider
{
    /**
     * Retrieve a user by their unique identifier.
     * Only returns users with admin role.
     *
     * @param  mixed  $id
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function retrieveById($id)
    {
        $user = parent::retrieveById($id);
        
        if ($user && $user->hasRole('admin')) {
            return $user;
        }
        
        return null;
    }

    /**
     * Retrieve a user by their unique identifier and "remember me" token.
     * Only returns users with admin role.
     *
     * @param  mixed  $id
     * @param  string  $token
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function retrieveByToken($id, $token)
    {
        $user = parent::retrieveByToken($id, $token);
        
        if ($user && $user->hasRole('admin')) {
            return $user;
        }
        
        return null;
    }

    /**
     * Retrieve a user by the given credentials.
     * Only returns users with admin role.
     *
     * @param  array  $credentials
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function retrieveByCredentials(array $credentials)
    {
        if (empty($credentials) ||
           (count($credentials) === 1 &&
            str_contains($this->firstCredentialKey($credentials), 'password'))) {
            return;
        }

        // First, retrieve the user by the normal method
        $user = parent::retrieveByCredentials($credentials);
        
        // Check if user exists and has admin role
        if ($user && $user->hasRole('admin')) {
            return $user;
        }
        
        return null;
    }

    /**
     * Get the first key from the credentials array.
     *
     * @param  array  $credentials
     * @return string
     */
    protected function firstCredentialKey($credentials)
    {
        foreach ($credentials as $key => $value) {
            return $key;
        }
    }
}
