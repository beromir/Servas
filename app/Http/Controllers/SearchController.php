<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\Link;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Searchable\Search;
use Spatie\Searchable\SearchResult;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $searchString = $request->input('searchString') ?? '';

        return (new Search())
            ->registerModel(Link::class, 'title', 'link')
            ->registerModel(Group::class, 'title')
            ->registerModel(Tag::class, 'name')
            ->search($searchString, Auth::user())
            ->transform(fn(SearchResult $searchResult) => (object)[
                'title' => $searchResult->title,
                'url' => $searchResult->url,
                'link' => $searchResult->searchable->link,
                'type' => $searchResult->type,
                'hash' => md5($searchResult->url),
            ])
            ->toArray();
    }
}
