<?php

namespace App\Http\Controllers\ApiControllers;

use App\Http\Controllers\Controller;
use Spatie\Tags\Tag;

class TagController extends Controller
{
    /**
     * Returns all tags for the current user.
     */
    public function getAllTags(): array
    {
        return Tag::orderBy('name')
            ->filterByCurrentUser()
            ->get()
            ->transform(fn(Tag $tag) => [
                'id' => $tag->id,
                'name' => $tag->name,
            ])
            ->toArray();
    }
}
