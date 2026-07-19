<?php

namespace App\Policies;

use App\Models\FeePayment;
use App\Models\User;

class FeePaymentPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('fees.view');
    }

    public function view(User $user, FeePayment $feePayment): bool
    {
        return FeePayment::allowedForUser($user)->whereKey($feePayment->id)->exists();
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('fees.collect');
    }

    public function update(User $user, FeePayment $feePayment): bool
    {
        return $this->view($user, $feePayment) && $user->hasPermissionTo('fees.edit');
    }

    public function delete(User $user, FeePayment $feePayment): bool
    {
        return $this->view($user, $feePayment) && $user->hasPermissionTo('fees.delete');
    }
}
