<?php

namespace App\Models;

use Spatie\Searchable\Searchable;
use Spatie\Searchable\SearchResult;

class Tag extends \Spatie\Tags\Tag implements Searchable
{
    public string $searchableType = 'Tags';

    public function getSearchResult(): SearchResult
    {
        $url = route('links.index', ['tags' => $this->name]);

        return new SearchResult(
            $this,
            $this->name,
            $url
        );
    }
}
