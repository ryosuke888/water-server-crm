<?php

namespace App\Policies;

use App\Enums\Role;
use App\Models\OrderHistory;
use App\Models\User;

class OrderHistoryPolicy
{
    /**
     * Create a new policy instance.
     */
    public function viewAny(User $user): bool
    {
        return $this->canSee($user);
    }

    public function view(User $user, OrderHistory $orderHistory): bool
    {
        return $this->canSee($user);
    }

    private function canSee(User $user): bool
    {
        return in_array($user->role, [Role::ADMIN, Role::OPERATOR, Role::SALES], true);
    }
}
