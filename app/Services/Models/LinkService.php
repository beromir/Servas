<?php

namespace App\Services\Models;

use App\Helpers\PermissionHelper;
use App\Models\Group;
use App\Models\Link;

class LinkService
{
    /**
     * Delete the link if the current user is the owner.
     */
    public function delete(int $linkId): void
    {
        $link = Link::find($linkId);

        if (PermissionHelper::userIsOwnerOfModal($link)) {
            $link->delete();
        }
    }

    /**
     * Attach/Detach the groups to/from the link if the current user is the owner.
     */
    public function attachOrDetachGroups(int $linkId, array $groups, bool $attach): void
    {
        if (empty($groups)) {
            return;
        }

        $link = Link::find($linkId);

        if (!PermissionHelper::userIsOwnerOfModal($link)) {
            return;
        }

        $linkGroups = $link->groupIds();

        foreach ($groups as $groupId) {
            if ($attach) {
                // Only attach the group if not already attached
                if (!in_array($groupId, $linkGroups)) {
                    $link->groups()->attach($groupId);
                }
            } else {
                // Only detach the group if attached
                if (in_array($groupId, $linkGroups)) {
                    $link->groups()->detach($groupId);
                }
            }
        }
    }

    /**
     * Attach/Detach the tags to/from the link if the current user is the owner.
     */
    public function attachOrDetachTags(int $linkId, array $tags, bool $attach): void
    {
        if (empty($tags)) {
            return;
        }

        $link = Link::find($linkId);

        if (!PermissionHelper::userIsOwnerOfModal($link)) {
            return;
        }

        $linkTags = $link->tagIds();

        foreach ($tags as $tagId) {
            if ($attach) {
                // Only attach the tag if not already attached
                if (!in_array($tagId, $linkTags)) {
                    $link->tags()->attach($tagId);
                }
            } else {
                // Only detach the tag if attached
                if (in_array($tagId, $linkTags)) {
                    $link->tags()->detach($tagId);
                }
            }
        }
    }
}
