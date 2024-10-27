<?php

namespace App\Services;

use App\Helpers\PermissionHelper;
use App\Models\Group;
use App\Services\Models\GroupService;
use App\Services\Models\LinkService;
use Illuminate\Support\Facades\Auth;
use Spatie\Tags\Tag;

class BulkEditingService
{
    /**
     * Edit the links with the passed action.
     */
    public function handleLinkEditingAction(string $action, array $links, array $groups, array $tags): void
    {
        if (empty($links)) {
            return;
        }

        $linkService = app(LinkService::class);

        $groups = PermissionHelper::filterArrayOfModalIds($groups, Group::class);
        $tags = PermissionHelper::filterArrayOfModalIds($tags, Tag::class);

        foreach ($links as $link) {
            switch ($action) {
                case 'delete':
                    $linkService->delete($link);
                    break;
                case 'attachGroups':
                    $linkService->attachOrDetachGroups($link, $groups, true);
                    break;
                case 'detachGroups':
                    $linkService->attachOrDetachGroups($link, $groups, false);
                    break;
                case 'attachTags':
                    $linkService->attachOrDetachTags($link, $tags, true);
                    break;
                case 'detachTags':
                    $linkService->attachOrDetachTags($link, $tags, false);
                    break;
                default:
                    return;
            }
        }

        $groupService = app(GroupService::class);

        $groupService->updateUserGroupsLinkCount(Auth::user());
    }
}
