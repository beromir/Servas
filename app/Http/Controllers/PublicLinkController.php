<?php

namespace App\Http\Controllers;

use App\Helpers\PermissionHelper;
use App\Http\Requests\StorePublicLinkRequest;
use App\Http\Requests\UpdatePublicLinkRequest;
use App\Models\Group;
use App\Models\PublicLink;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PublicLinkController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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

        $publicLink->share_id = Str::take(sha1(Str::uuid()), 12);
        $publicLink->user_id = Auth::id();
        $publicLink->publicLinkable()->associate($group);

        $publicLink->save();
    }

    /**
     * Display the specified resource.
     */
    public function show(PublicLink $publicLink)
    {
        //
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
