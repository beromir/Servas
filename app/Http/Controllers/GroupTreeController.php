<?php

namespace App\Http\Controllers;

use App\Models;
use Illuminate\Http\Request;
use Inertia\Inertia;

class GroupTreeController extends Controller
{
    public function index()
    {
        return Inertia::render('GroupsTree/Index', [
            'groups' => Models\Group::filterByCurrentUser()
                ->orderBy('title')
                ->where('parent_group_id', null)
                ->withCount(['links', 'groups'])
                ->get()
                ->transform(fn (Models\Group $group) => [
                    'id' => $group->id,
                    'title' => $group->title,
                    'childGroupsCount' => $group->groups_count,
                    'linksCount' => $group->links_count,
                ]),
        ]);
    }

    public function list(Request $request, Models\Group $group)
    {
        $data = [];

        $data['groups'] = Models\Group::filterByCurrentUser()
            ->orderBy('title')
            ->where('parent_group_id', $group->id)
            ->withCount(['links', 'groups'])
            ->get()
            ->transform(fn (Models\Group $group) => [
                'id' => $group->id,
                'title' => $group->title,
                'childGroupsCount' => $group->groups_count,
                'linksCount' => $group->links_count,
            ]);


        if (!$request->input('groupOnly')) {
            $data['links'] = $group->links
                ->sortByDesc('created_at')
                ->values()
                ->transform(fn (Models\Link $link) => [
                    'title' => $link->title,
                    'link' => $link->link,
                    'id' => $link->id,
                    'createdAt' => $link->getCreatedAtForHumansAttribute(),
                ])
                ->all();
        }


        return response()->json([
            'data' => $data
        ]);
    }
}
