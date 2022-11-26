<?php

namespace App\Http\Controllers;

use App\Helpers\WebpageData;
use App\Models\Group;
use App\Models\Link;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rules\Enum;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\Tags\Tag;

class LinkController extends Controller
{
    protected function rules(): array
    {
        return [
            'title' => 'string|min:2|nullable',
            'link' => 'url|required',
            'groups' => 'array',
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index(array $filteredTags = []): Response
    {
        $searchString = Request::get('search') ?? '';
        $filteredTags = Request::get('tags') ?? [];

        return Inertia::render('Links/Index', [
            'links' => Link::orderBy('created_at', 'desc')
                ->filterByCurrentUser()
                ->search('title', $searchString)
                ->additionalSearch('link', $searchString)
                ->filterByTags($filteredTags)
                ->paginate(20)
                ->withQueryString()
                ->through(fn(Link $link) => [
                    'title' => $link->title,
                    'link' => $link->link,
                    'id' => $link->id,
                    'tags' => TagController::getTagsOfLink($link),
                    'groups' => $link->groups
                        ->sortBy('title')
                        ->values()
                        ->transform(fn(Group $group) => [
                            'title' => $group->title,
                        ]),
                    'created_at' => $link->getCreatedAtForHumansAttribute(),
                    'updated_at' => $link->getUpdatedAtForHumansAttribute(),
                    'created_at_with_time' => $link->getCreatedAtForHumansAttribute(true),
                    'updated_at_with_time' => $link->getUpdatedAtForHumansAttribute(true),
                ]),
            'tags' => TagController::getAllTags(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        Request::validate($this->rules());

        $link = Link::make();

        $link->link = Request::get('link');
        $link->title = Request::get('title');
        $link->user_id = Auth::id();

        if (empty($link->title)) {
            $link->title = WebpageData::getWebPageTitle($link->link);
        }

        $link->save();

        $groupIds = Request::get('groups');

        $link->groups()->sync($groupIds);

        $tags = [];

        foreach (Request::get('tags') as $tag) {
            $tags[] = Tag::filterByCurrentUser()->find($tag);
        }

        $link->syncTags($tags);

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
                'isInLaterList' => LaterController::isLinkOnLaterList($link->id),
            ],
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Link $link
     * @return \Illuminate\Http\Response
     */
    public function edit(Link $link)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Link $link)
    {
        Request::validate($this->rules());

        $link->link = Request::get('link');
        $link->title = Request::get('title');

        if (empty($link->title)) {
            $link->title = WebpageData::getWebPageTitle($link->link);
        }

        $link->save();

        $groupIds = Request::get('groups');

        $link->groups()->sync($groupIds);

        $tags = [];

        foreach (Request::get('tags') as $tag) {
            $tags[] = Tag::filterByCurrentUser()->find($tag);
        }

        $link->syncTags($tags);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Link $link)
    {
        $link->delete();
    }

    /**
     * Return all links for the current user.
     */
    public function getAllLinks()
    {
        return Link::orderBy('created_at', 'desc')
            ->filterByCurrentUser()
            ->get()
            ->transform(fn(Link $link) => [
                'title' => $link->title,
                'link' => $link->link,
                'id' => $link->id,
            ]);
    }
}
