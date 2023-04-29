<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\Link;
use App\Models\Tag;
use Illuminate\Http\Request;
use Spatie\Searchable\Search;
use Spatie\Searchable\SearchResult;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $searchString = $request->input('searchString');

        return (new Search())
            ->registerModel(Link::class, 'title', 'link')
            ->registerModel(Group::class, 'title')
            ->registerModel(Tag::class, 'name')
            ->search($searchString, \Auth::user())
            ->groupByType()
            ->transform(fn($group, $groupTitle) => (object)[
                'title' => $groupTitle,
                'items' => $group->map(fn(SearchResult $searchResult) => (object)[
                    'title' => $searchResult->title,
                    'url' => $searchResult->url,
                    'hash' => md5($searchResult->url),
                    'link' => $searchResult->searchable->link,
                ])
            ])
            // Reset the array keys for use in JavaScript
            ->values()
            ->toArray();
    }
}
