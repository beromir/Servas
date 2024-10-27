<?php

namespace App\Services\Models;

use App\Models\Group;
use App\Models\User;
use Illuminate\Support\Arr;

class GroupService
{
    public function updateUserGroupsLinkCount(User $user): void
    {
        $groups = Group::where('user_id', $user->id)->get();

        foreach ($groups as $group) {
            $group->updateLinksCount();

            $group->save();
        }
    }

    public function cleanupQueryOptions($queryOptions): ?array
    {
        $this->removeEmptyQueryOption('containsTagsOr', $queryOptions);
        $this->removeEmptyQueryOption('containsTagsAnd', $queryOptions);
        $this->removeEmptyQueryOption('containsTagsNot', $queryOptions);

        if (empty($queryOptions)) {
            return null;
        }

        return $queryOptions;
    }

    public function removeDeletedTagFromQueryOptions(int $tagId, User $user): void
    {
        $groups = Group::where('user_id', $user->id)->get();

        foreach ($groups as $group) {
            $queryOptions = $group->query_options ?? [];

            $this->removeTagId($tagId, $queryOptions, 'containsTagsOr');
            $this->removeTagId($tagId, $queryOptions, 'containsTagsAnd');
            $this->removeTagId($tagId, $queryOptions, 'containsTagsNot');

            $queryOptions = $this->cleanupQueryOptions($queryOptions);

            $group->query_options = $queryOptions;

            $group->save();
        }
    }

    protected function removeEmptyQueryOption(string $queryOption, array &$queryOptions): void
    {
        if (Arr::exists($queryOptions, $queryOption) && !Arr::get($queryOptions, $queryOption)) {
            Arr::forget($queryOptions, $queryOption);
        }
    }

    protected function removeTagId(int $tagId, array &$queryOptions, string $queryOption): void
    {
        $tags = Arr::get($queryOptions, $queryOption);

        if (empty($tags)) {
            return;
        }

        if (($key = array_search($tagId, $tags)) !== false) {
            Arr::forget($queryOptions, "$queryOption.$key");
        }
    }
}
