<?php

namespace App\Services;

use App\Models\Group;
use App\Models\Link;
use Spatie\Tags\Tag;
use App\Models\User;

class ExportService
{
    public function exportUserData(User $user): array
    {
        $export = [];

        $export['links'] = Link::where('user_id', $user->id)
            ->get()
            ->transform(fn(Link $link) => [
                'title' => trim($link->title),
                'link' => $link->link,
                'createdAt' => $link->created_at->jsonSerialize(),
                'updatedAt' => $link->updated_at->jsonSerialize(),
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

        $export['tags'] = Tag::where('user_id', $user->id)
            ->get()
            ->transform(fn(Tag $tag) => $tag->name)
            ->toArray();

        return $export;
    }
}
