<?php

namespace App\Services\Models;

use App\Models\Group;
use App\Models\User;

class GroupService
{
    public function updateUserGroupsLinkCount(User $user)
    {
        $groups = Group::where('user_id', $user->id)->get();

        foreach ($groups as $group) {
            $group->updateLinksCount();

            $group->save();
        }
    }
}
