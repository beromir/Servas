<?php

namespace App\Http\Controllers\WidgetControllers;

use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\Link;
use Spatie\Tags\Tag;

class StatsWidgetController extends Controller
{
    /**
     * Get the widget data.
     */
    public static function getData(): object
    {
        return (object)[
            'links' => Link::filterByCurrentUser()->get()->count(),
            'groups' => Group::filterByCurrentUser()->get()->count(),
            'tags' => Tag::filterByCurrentUser()->get()->count(),
        ];
    }
}
