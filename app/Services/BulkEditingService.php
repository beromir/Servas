<?php

namespace App\Services;

use App\Services\Models\LinkService;

class BulkEditingService
{
    /**
     * Edit the links with the passed action.
     */
    public function handleLinkEditingAction(string $action, array $links, array $groups): void
    {
        if (empty($links)) {
            return;
        }

        $linkService = app(LinkService::class);

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
                default:
                    return;
            }
        }
    }
}
