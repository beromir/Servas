<?php

namespace App\Http\Controllers\ApiControllers;

use App\Http\Controllers\Controller;
use App\Models\Group;

class GroupController extends Controller
{
    /**
     * Returns all groups for the current user.
     */
    public function getAllGroups(): array
    {
        return Group::orderBy('title')
            ->filterByCurrentUser()
            ->get()
            ->transform(fn(Group $group) => [
                'id' => $group->id,
                'title' => $group->title,
            ])
            ->toArray();
    }
}
