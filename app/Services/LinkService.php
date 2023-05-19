<?php

namespace App\Services;

use App\Models\Link;
use App\Models\User;

class LinkService
{
    /**
     * Get duplicate links of the user.
     */
    public function getDuplicates(User $user): array
    {
        $groupedLinks = Link::where('user_id', $user->id)->get(['id', 'link'])->groupBy('link')->toArray();;

        return array_filter($groupedLinks, fn($group) => count($group) > 1);
    }

    /**
     * Remove duplicate links.
     */
    public function deleteDuplicates(array $links): void
    {
        // TODO: Find a cleaner solution.
        foreach ($links as $duplicates) {

            if (!is_array($duplicates)) {
                continue;
            }

            array_pop($duplicates);

            if (empty($duplicates)) {
                continue;
            }

            foreach ($duplicates as $duplicateLink) {
                $link = Link::find($duplicateLink['id']);

                $this->deleteLink($link);
            }
        }
    }

    /**
     * Delete the link if not null.
     */
    public function deleteLink(?Link $link): void
    {
        if (is_null($link)) {
            return;
        }

        $link->delete();
    }
}
