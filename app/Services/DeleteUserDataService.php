<?php

namespace App\Services;

use App\Models\Group;
use App\Models\Link;
use App\Models\User;
use Spatie\Tags\Tag;

class DeleteUserDataService
{
    public function deleteUserData(array $deleteOptions, User $user): void
    {
        if (in_array('links', $deleteOptions)) {
            $this->deleteLinks($user);
        }

        if (in_array('groups', $deleteOptions)) {
            $this->deleteGroups($user);
        }

        if (in_array('tags', $deleteOptions)) {
            $this->deleteTags($user);
        }
    }

    public function deleteLinks(User $user)
    {
        Link::where('user_id', $user->id)->delete();
    }

    public function deleteGroups(User $user)
    {
        Group::where('user_id', $user->id)->delete();
    }

    public function deleteTags(User $user)
    {
        Tag::where('user_id', $user->id)->delete();
    }
}
