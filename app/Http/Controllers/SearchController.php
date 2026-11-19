<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\Link;
use App\Models\Tag;
use Illuminate\Http\Request;
use Spatie\Searchable\ModelSearchAspect;
use Spatie\Searchable\Search;
use Spatie\Searchable\SearchResult;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $searchString = $request->input('searchString') ?? '';

        return (new Search())
            ->registerModel(Link::class, fn (ModelSearchAspect $aspect) => $aspect
                ->addSearchableAttribute('title')
                ->addSearchableAttribute('link')
                ->filterByCurrentUser())
            ->registerModel(Group::class, fn (ModelSearchAspect $aspect) => $aspect
                ->addSearchableAttribute('title')
                ->filterByCurrentUser())
            ->registerModel(Tag::class, fn (ModelSearchAspect $aspect) => $aspect
                ->addSearchableAttribute('name')
                ->filterByCurrentUser())
            ->search($searchString)
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
