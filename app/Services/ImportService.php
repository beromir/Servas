<?php

namespace App\Services;

use App\Models\Group;
use App\Models\Link;
use Illuminate\Support\Facades\Validator;
use Spatie\Tags\Tag;
use App\Models\User;

class ImportService
{
    public function importUserData(array $data, array $importOptions, User $user): void
    {
        if (empty($data)) {
            return;
        }

        if (in_array('tags', $importOptions) && array_key_exists('tags', $data) && is_array($data['tags']) && !empty($data['tags'])) {
            foreach ($data['tags'] as $tag) {
                if (is_string($tag)) {
                    Tag::create([
                        'name' => $tag,
                        'user_id' => $user->id,
                    ]);
                }
            }
        }

        if (in_array('groups', $importOptions) && array_key_exists('groups', $data) && is_array($data['groups']) && !empty($data['groups'])) {
            foreach ($data['groups'] as $group) {
                if (array_key_exists('title', $group) && is_string($group['title'])) {
                    Group::create([
                        'title' => $group['title'],
                        'user_id' => $user->id,
                    ]);
                }
            }

            $this->createNestedGroups($data['groups'], $user);
        }

        if (in_array('links', $importOptions) && array_key_exists('links', $data) && is_array($data['links']) && !empty($data['links'])) {
            foreach ($data['links'] as $link) {
                $linkData = [];

                if (array_key_exists('title', $link) && is_string($link['title'])) {
                    $linkData['title'] = $link['title'];
                } else {
                    continue;
                }

                if (array_key_exists('link', $link) && is_string($link['link'])) {
                    $linkData['link'] = $link['link'];
                } else {
                    continue;
                }

                $validator = Validator::make($linkData, Link::rules($linkData['link']));

                if ($validator->fails()) {
                    continue;
                }

                $newLink = Link::make();

                $newLink->title = $linkData['title'];
                $newLink->link = $linkData['link'];

                if (array_key_exists('createdAt', $link) && is_string($link['createdAt'])) {
                    $newLink->created_at = $link['createdAt'];
                }

                if (array_key_exists('updatedAt', $link) && is_string($link['updatedAt'])) {
                    $newLink->updated_at = $link['updatedAt'];
                } elseif (array_key_exists('createdAt', $link) && is_string($link['createdAt'])) {
                    $newLink->updated_at = $link['createdAt'];
                }

                $newLink->user_id = $user->id;

                $newLink->save();

                if (array_key_exists('groups', $link) && is_array($link['groups']) && !empty($link['groups'])) {
                    $groupIds = $this->getGroupIds($link['groups'], $user);

                    $newLink->groups()->sync($groupIds);
                }

                if (array_key_exists('tags', $link) && is_array($link['tags']) && !empty($link['tags'])) {
                    $tags = $this->getTags($link['tags'], $user);

                    $newLink->syncTags($tags);
                }
            }
        }
    }

    protected function getGroupIds(array $groups, User $user): array
    {
        $groupIds = [];

        foreach ($groups as $group) {
            if (is_string($group)) {
                $foundGroup = Group::where(['title' => $group, 'user_id' => $user->id])->first();

                if (is_null($foundGroup)) {
                    continue;
                }

                $groupIds[] = $foundGroup->id;
            }
        }

        return $groupIds;
    }

    protected function getTags(array $tags, User $user): array
    {
        $tagModels = [];

        $locale = Tag::getLocale();

        foreach ($tags as $tag) {
            if (is_string($tag)) {
                $foundTag = Tag::where(["name->{$locale}" => $tag, 'user_id' => $user->id])->first();

                if (is_null($foundTag)) {
                    continue;
                }

                $tagModels[] = $foundTag;
            }
        }

        return $tagModels;
    }

    protected function createNestedGroups(array $groups, User $user)
    {
        foreach ($groups as $group) {
            if (array_key_exists('childGroups', $group) && is_array($group['childGroups']) && !empty($group['childGroups'])) {
                if (!is_string($group['title'])) {
                    continue;
                }

                $parentGroup = Group::where(['title' => $group['title'], 'user_id' => $user->id])->first();

                if (is_null($parentGroup)) {
                    continue;
                }

                foreach ($group['childGroups'] as $childGroupTitle) {
                    if (!is_string($childGroupTitle)) {
                        continue;
                    }

                    $childGroup = Group::where(['title' => $childGroupTitle, 'user_id' => $user->id])->first();

                    if (is_null($childGroup)) {
                        continue;
                    }

                    $childGroup->parent_group_id = $parentGroup->id;

                    $childGroup->save();
                }
            }
        }
    }
}
