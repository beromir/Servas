<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Auth;

class PermissionHelper
{
    /**
     * Check if the user is the owner of the modal instance.
     */
    public static function userIsOwnerOfModal(object $modal, int|null $userId = null, string $userTable = 'user_id'): bool
    {
        if ($userId === null) {
            $userId = Auth::id();
        }

        return !empty($modal) && $modal->$userTable === $userId;
    }
}
