<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreGroupRequest;
use App\Http\Requests\UpdateGroupRequest;
use App\Models\Group;
use App\Models\Link;
use App\Models\PublicLink;
use App\Models\Tag;
use App\Services\Models\GroupService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

class GroupController extends Controller
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
    public function store(StoreGroupRequest $request)
    {
        $validated = $request->validated();

        $group = Group::make();

        $group->title = $validated['title'];
        $group->parent_group_id = $validated['parentGroupId'];
        $group->user_id = Auth::id();

        $group->query_options = $this->groupService->cleanupQueryOptions([
            'containsTagsOr' => $validated['orTags'],
            'containsTagsAnd' => $validated['andTags'],
            'containsTagsNot' => $validated['notTags'],
        ]);

        $group->updateLinksCount();

        $group->save();
    }

    /**
     * Display the specified resource.
     */
    public function show(int $groupId): Response|RedirectResponse
    {
        $group = Group::filterByCurrentUser()->find($groupId);
        $searchString = Request::get('search') ?? '';
        $filteredTags = Request::get('tags') ?? '';
        $showUntaggedOnly = Request::get('untaggedOnly') ?? false;

        $filteredTags = empty($filteredTags) ? [] : explode(',', $filteredTags);

        if ($group === null) {
            return Redirect::route('home');
        }

        return Inertia::render('SingleGroup/Index', [
            'group' => (object)[
                'title' => $group->title,
                'id' => $group->id,
                'parentGroupId' => $group->parent_group_id,
                'orTags' => collect($group->getOrTags())->transform(fn(Tag $tag) => [
                    'id' => $tag->id,
                    'name' => $tag->name,
                ]),
                'andTags' => collect($group->getAndTags())->transform(fn(Tag $tag) => [
                    'id' => $tag->id,
                    'name' => $tag->name,
                ]),
                'notTags' => collect($group->getNotTags())->transform(fn(Tag $tag) => [
                    'id' => $tag->id,
                    'name' => $tag->name,
                ]),
            ],
            'links' => $group->links()
                ->filterByCurrentUser()
                ->filterLinks($searchString, $filteredTags, $showUntaggedOnly)
                ->through(fn(Link $link) => [
                    'title' => $link->title,
                    'link' => $link->link,
                    'id' => $link->id,
                ]),
            'publicLink' => (object)[
                'id' => $group->publicLink?->id,
                'link' => $group->publicLink?->getLink(),
            ],
            'searchString' => $searchString,
            'filteredTags' => $filteredTags ? TagController::getTagsByNames($filteredTags) : [],
            'showUntaggedOnly' => $showUntaggedOnly,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateGroupRequest $request, Group $group)
    {
        $validated = $request->validated();

        $group->title = $validated['title'];

        // The parent group cannot be itself.
        if ($group->id !== $validated['parentGroupId']) {
            $group->parent_group_id = $validated['parentGroupId'];
        }

        $group->query_options = $this->groupService->cleanupQueryOptions([
            'containsTagsOr' => $validated['orTags'],
            'containsTagsAnd' => $validated['andTags'],
            'containsTagsNot' => $validated['notTags'],
        ]);

        $group->updateLinksCount();

        $group->save();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Group $group)
    {
        /** @var PublicLink $publicLink */
        if ($publicLink = $group->publicLink) {
            $publicLink->delete();
        }

        $group->delete();
    }

    protected function getAllParentGroups(Group $group): array|null
    {
        $parentGroups = [];
        $currentGroupInLoop = $group;

        while (($currentGroupInLoop = $this->getParentGroup($currentGroupInLoop)) !== null) {
            $parentGroups[] = (object)[
                'title' => $currentGroupInLoop->title,
                'link' => route('groups.show', $currentGroupInLoop->id, absolute: false),
            ];
        }

        return array_reverse($parentGroups);
    }

    protected function getParentGroup(Group $group): Group|null
    {
        if ($group->parent_group_id === null) {
            return null;
        }

        return Group::filterByCurrentUser()->find($group->parent_group_id);
    }

    /**
     * Returns all groups for the current user.
     */
    public function getAllGroups()
    {
        return Group::orderBy('title')
            ->filterByCurrentUser()
            ->withCount(['groups'])
            ->get()
            ->transform(fn(Group $group) => [
                'id' => $group->id,
                'title' => $group->title,
                'parentGroupId' => $group->parent_group_id,
                'childGroupsCount' => $group->groups_count,
                'linksCount' => $group->links_count,
            ]);
    }
}
