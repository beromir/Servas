<?php

namespace App\Http\Controllers\ApiControllers;

use App\Helpers\WebpageData;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreLinkApiRequest;
use App\Models\Link;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class LinkController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreLinkApiRequest $request): Response
    {
        $validated = $request->validated();

        $link = Link::make();

        $link->link = $validated['link'];
        $link->title = $validated['title'];
        $link->user_id = Auth::id();

        if (empty($link->title)) {
            $link->title = WebpageData::getWebPageTitle($link->link);
        }

        $link->save();

        return response('The link was added.', headers: ['Content-Type' => 'text/plain']);
    }
}
