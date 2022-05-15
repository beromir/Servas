<?php

namespace App\Http\Controllers\WidgetControllers;

use App\Helpers\DateHelper;
use App\Http\Controllers\Controller;
use App\Http\Controllers\LaterController;

class LaterWidgetController extends Controller
{
    /**
     * Get the widget data.
     */
    public static function getData(): object
    {
        return (object)[
            'links' => LaterController::getLinksFromLaterList()
                ->sortByDesc('created_at')
                ->values()
                ->transform(fn($link) => [
                    'title' => $link->title,
                    'link' => $link->link,
                    'id' => $link->id,
                    'mediaType' => $link->media_type,
                    'createdAt' => DateHelper::convertToDateString($link->created_at),
                ]),
        ];
    }
}
