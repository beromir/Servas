<?php

namespace App\Services;

use App\Models\Group;
use App\Models\Link;
use Spatie\Tags\Tag;
use App\Models\User;

class ExportService
{
    public function exportUserData(array $exportOptions, User $user): array
    {
        $export = [];

        if (in_array('links', $exportOptions)) {
            $export['links'] = Link::where('user_id', $user->id)
                ->get()
                ->transform(fn(Link $link) => [
                    'title' => $link->title,
                    'link' => $link->link,
                    'createdAt' => $link->created_at->jsonSerialize(),
                    'groups' => $link
                        ->groups()
                        ->get(['title'])
                        ->transform(fn(Group $group) => $group->title)
                        ->toArray(),
                    'tags' => $link
                        ->tags()
                        ->get()
                        ->transform(fn(Tag $tag) => $tag->name)
                        ->toArray()
                ])
                ->toArray();
        }

        if (in_array('groups', $exportOptions)) {
            $export['groups'] = Group::where('user_id', $user->id)
                ->get()
                ->transform(fn(Group $group) => [
                    'title' => $group->title,
                    'childGroups' => $group
                        ->groups()
                        ->get()
                        ->transform(fn(Group $group) => $group->title)
                        ->toArray(),
                ])
                ->toArray();
        }

        if (in_array('tags', $exportOptions)) {
            $export['tags'] = Tag::where('user_id', $user->id)
                ->get()
                ->transform(fn(Tag $tag) => $tag->name)
                ->toArray();
        }

        return $export;
    }
}
