<?php

namespace App\Services\Models;

use App\Models\Group;
use App\Models\Link;
use App\Models\Tag;
use Illuminate\Database\Eloquent\Builder;

class GroupService
{
    public function getGroupLinksQuery(Group $group): Builder
    {
        $tags = $this->getOrTags($group);

        return Link::leftJoin('groupables', 'links.id', '=', 'groupables.groupable_id')
            ->where(function (Builder $query) use ($group, $tags) {
                $query->where(function (Builder $query) use ($group) {
                    $query->where('groupables.group_id', $group->id)
                        ->where('groupables.groupable_type', Link::class);
                })
                    ->when($tags, function (Builder $query, array $tags) {
                        $query->orWhere(function (Builder $query) use ($tags) {
                            $query->withAnyTags($tags);
                        });
                    });
            });
    }

    protected function getOrTags(Group $group): array
    {
        $tags = [];

        if ($group->query_options['containsTagsOr'] ?? false) {
            foreach ($group->query_options['containsTagsOr'] as $tag) {
                $tags[] = Tag::find($tag);
            }
        }

        return $tags;
    }
}
