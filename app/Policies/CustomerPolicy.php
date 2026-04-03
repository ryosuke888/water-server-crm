<?php

namespace App\Policies;

use App\Enums\Role;
use App\Models\Customer;
use App\Models\User;

class CustomerPolicy
{
    /**
     * Create a new policy instance.
     */
    public function viewAny(User $user): bool
    {
        return $this->canSee($user);
    }

    public function create(User $user): bool
    {
        return $this->canManage($user);
    }

    public function view(User $user, Customer $customer): bool
    {
        return $this->canSee($user);
    }

    public function update(User $user, Customer $customer): bool
    {
        return $this->canManage($user);
    }


    private function canManage(User $user): bool
    {
        return in_array($user->role, [Role::ADMIN, Role::OPERATOR, Role::SALES], true);
    }

    private function canSee(User $user): bool
    {
        return in_array($user->role, [Role::ADMIN, Role::OPERATOR, Role::SALES, Role::VIEWER], true);
    }

}
