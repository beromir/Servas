<?php

namespace App\Http\Controllers\WidgetControllers;

use App\Helpers\DateHelper;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LastAddedWidgetController extends Controller
{
    /**
     * Get the widget data.
     */
    public static function getData(): object
    {
        return (object)[
            'links' => DB::table('links')
                ->where('user_id', Auth::id())
                ->orderByDesc('created_at')
                ->limit(15)
                ->get()
                ->values()
                ->transform(fn($link) => [
                    'title' => $link->title,
                    'link' => $link->link,
                    'id' => $link->id,
                    'createdAt' => DateHelper::convertToDateString($link->created_at),
                ]),
        ];
    }
}
