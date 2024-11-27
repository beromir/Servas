<?php

namespace App\Http\Controllers\ApiControllers;

use App\Helpers\WebpageData;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreLinkApiRequest;
use App\Models\Link;
use App\Services\Models\GroupService;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Spatie\Tags\Tag;

class LinkController extends Controller
{
    public function __construct(
        protected GroupService $groupService,
    )
    {
        //
    }

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

        if (key_exists('groups', $validated)) {
            $groupIds = $validated['groups'];

            $link->groups()->sync($groupIds);

            $this->groupService->updateUserGroupsLinkCount(Auth::user());
        }

        if (key_exists('tags', $validated)) {
            $tags = [];

            foreach ($validated['tags'] as $tag) {
                $tags[] = Tag::filterByCurrentUser()->find($tag);
            }

            $link->syncTags($tags);
        }

        return response('The link was added.', headers: ['Content-Type' => 'text/plain']);
    }
}
