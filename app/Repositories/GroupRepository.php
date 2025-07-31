<?php

namespace App\Repositories;

use App\Models\Group;
use App\Models\Link;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Request;

class GroupRepository
{
    public static function getLinks(
        int|Group     $group,
        null|int|User $user = null,
        bool          $filterByCurrentUser = false,

    ) {
        $searchString = Request::get('search', '');

        $group = is_int($group) ? Group::find($group) : $group;

        $orTags = $group->getOrTags();
        $andTags = $group->getAndTags();
        $notTags = $group->getNotTags();

        $query = $group->links()
            ->where(function (Builder $query) use ($group, $orTags, $andTags, $notTags) {
                $query
                    ->when($orTags, function (Builder $query, array $orTags) {
                        $query
                            ->orWhere(function (Builder $query) use ($orTags) {
                                $query
                                    ->withAnyTags($orTags);
                            });
                    })
                    ->when($andTags, function (Builder $query, array $andTags) {
                        $query
                            ->orWhere(function (Builder $query) use ($andTags) {
                                $query
                                    ->withAllTags($andTags);
                            });
                    })
                    ->when($notTags, function (Builder $query, array $notTags) {
                        $query
                            ->where(function (Builder $query) use ($notTags) {
                                $query
                                    ->withoutTags($notTags);
                            });
                    })
                ;
            });

        if ($user || $filterByCurrentUser) {
            $filteredTags = empty($queryTags = Request::get('tags', ''))
                ? []
                : explode(',', $queryTags);
            $showUntaggedOnly = Request::get('untaggedOnly', false);

            $query = $query
                ->filterByCurrentUser()
                ->filterLinks($searchString, $filteredTags, $showUntaggedOnly);
        } else {
            $query = $query
                ->filterLinks($searchString);
        }

        return $query
            ->through(fn(Link $link) => [
                'title' => $link->title,
                'link' => $link->link,
                'id' => $link->id,
            ]);
    }
}
