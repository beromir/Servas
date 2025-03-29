<?php

namespace App\Http\Controllers;

use App\Helpers\WebpageData;
use App\Http\Requests\StoreLinkRequest;
use App\Http\Requests\UpdateLinkRequest;
use App\Models\Group;
use App\Models\Link;
use App\Services\Models\GroupService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;
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
     * Display a listing of the resource.
     */
    public function index(array $filteredTags = []): Response
    {
        $searchString = Request::get('search') ?? '';
        $filteredTags = Request::get('tags') ?? '';
        $showUntaggedOnly = Request::get('untaggedOnly') ?? false;

        $filteredTags = empty($filteredTags) ? [] : explode(',', $filteredTags);

        return Inertia::render('Links/Index', [
            'links' => Link::orderBy('created_at', 'desc')
                ->filterByCurrentUser()
                ->filterLinks($searchString, $filteredTags, $showUntaggedOnly)
                ->through(fn(Link $link) => [
                    'title' => $link->title,
                    'link' => $link->link,
                    'id' => $link->id,
                ]),
            'searchString' => $searchString,
            'filteredTags' => $filteredTags ? TagController::getTagsByNames($filteredTags) : [],
            'showUntaggedOnly' => $showUntaggedOnly,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreLinkRequest $request): RedirectResponse
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

        $groupIds = $validated['groups'];

        $link->groups()->sync($groupIds);

        $tags = [];

        foreach ($validated['tags'] as $tag) {
            $tags[] = Tag::filterByCurrentUser()->find($tag);
        }

        $link->syncTags($tags);

        $this->groupService->updateUserGroupsLinkCount(Auth::user());

        return Redirect::route('links.show', $link->id);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $link): Response|RedirectResponse
    {
        $link = Link::filterByCurrentUser()->find($link);

        if ($link === null) {
            return Redirect::route('links.index');
        }

        return Inertia::render('SingleLink/Index', [
            'link' => (object)[
                'title' => $link->title,
                'link' => $link->link,
                'id' => $link->id,
                'tags' => TagController::getTagsOfLink($link),
                'linkGroups' => $link->groups
                    ->sortBy('title')
                    ->values()
                    ->transform(fn(Group $group) => [
                        'id' => $group->id,
                        'title' => $group->title,
                    ]),
                'groups' => $link->groupIds(),
                'created_at' => $link->getCreatedAtForHumansAttribute(),
                'updated_at' => $link->getUpdatedAtForHumansAttribute(),
                'created_at_with_time' => $link->getCreatedAtForHumansAttribute(true),
                'updated_at_with_time' => $link->getUpdatedAtForHumansAttribute(true),
            ],
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateLinkRequest $request, Link $link)
    {
        $validated = $request->validated();

        $link->link = $validated['link'];
        $link->title = $validated['title'];

        if (empty($link->title)) {
            $link->title = WebpageData::getWebPageTitle($link->link);
        }

        $link->save();

        $groupIds = $validated['groups'];

        $link->groups()->sync($groupIds);

        $tags = [];

        foreach ($validated['tags'] as $tag) {
            $tags[] = Tag::filterByCurrentUser()->find($tag);
        }

        $link->syncTags($tags);

        $this->groupService->updateUserGroupsLinkCount(Auth::user());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Link $link)
    {
        $link->groups()->detach();

        $link->delete();

        $this->groupService->updateUserGroupsLinkCount(Auth::user());
    }
}
