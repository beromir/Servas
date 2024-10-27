<?php

namespace App\Http\Controllers;

use App\Helpers\PermissionHelper;
use App\Http\Requests\StorePublicLinkRequest;
use App\Http\Requests\UpdatePublicLinkRequest;
use App\Models\Group;
use App\Models\Link;
use App\Models\PublicLink;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Inertia\Inertia;
use Inertia\Response;
use Sqids\Sqids;

class PublicLinkController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        return Inertia::render('PublicLink/Index', [
            'publicLinks' => PublicLink::filterByCurrentUser()
                ->get()
                ->transform(fn(PublicLink $publicLink) => [
                    'id' => $publicLink->id,
                    'shareId' => $publicLink->share_id,
                    'link' => $publicLink->getLink(),
                    'group' => (object)[
                        'title' => $publicLink->publicLinkable->title,
                        'id' => $publicLink->publicLinkable->id,
                    ]
                ])
                ->sortBy(function (array $publicLink) {
                    return $publicLink['group']->title;
                })
                ->values()
                ->all(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePublicLinkRequest $request)
    {
        $groupId = $request->validated('groupId');

        $group = Group::find($groupId);

        $publicLink = PublicLink::make();

        $publicLink->user_id = Auth::id();
        $publicLink->share_id = time();
        $publicLink->publicLinkable()->associate($group);

        $publicLink->save();

        $sqids = new Sqids(minLength: 10);

        $publicLink->share_id = $sqids->encode([$publicLink->id]);

        $publicLink->save();
    }

    /**
     * Display the specified resource.
     */
    public function show(string $shareId, Sqids $sqids): Response
    {
        $publicLinkId = $sqids->decode($shareId)[0];

        $publicLink = PublicLink::find($publicLinkId);

        if (is_null($publicLink)) {
            abort(404);
        }

        $group = $publicLink->publicLinkable;

        if (is_null($group)) {
            abort(404);
        }

        $searchString = Request::get('search') ?? '';

        return Inertia::render('PublicLink/Show', [
            'title' => $group->title,
            'links' => $group->links()
                ->filterLinks($searchString)
                ->through(fn(Link $link) => [
                    'title' => $link->title,
                    'link' => $link->link,
                    'id' => $link->id,
                ]),
            'shareId' => $shareId,
            'searchString' => $searchString,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PublicLink $publicLink)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePublicLinkRequest $request, PublicLink $publicLink)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PublicLink $publicLink)
    {
        if (PermissionHelper::userIsOwnerOfModal($publicLink)) {
            $publicLink->delete();
        }
    }
}
