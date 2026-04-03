<?php

namespace App\Policies;

use App\Enums\Role;
use App\Models\Order;
use App\Models\User;

class OrderPolicy
{
    /**
     * Create a new policy instance.
     */
    public function viewAny(User $user): bool
    {
        return $this->canSee($user);
    }

    public function view(User $user, Order $order): bool
    {
        return $this->canSee($user);
    }

    public function create(User $user): bool
    {
        return $this->canManage($user);
    }

    public function update(User $user, Order $order): bool
    {
        return $this->canManage($user);
    }

    public function delete(User $user, Order $order): bool
    {
        return in_array($user->role, [Role::ADMIN], true);
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
