<?php

namespace App\Http\Controllers;

use App\Services\Models\GroupService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\Tags\Tag;
use Illuminate\Support\Facades\Request;

class TagController extends Controller
{
    public function __construct(
        protected GroupService $groupService,
    )
    {
        //
    }

    protected function rules(): array
    {
        return [
            'tagName' => 'required|min:2|string',
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        return Inertia::render('Tags/Index', [
            'tags' => Tag::orderBy('name')
                ->filterByCurrentUser()
                ->get()
                ->transform(fn(Tag $tag) => [
                    'id' => $tag->id,
                    'name' => $tag->name,
                ]),
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
    public function store(Request $request)
    {
        Request::validate($this->rules());

        Tag::create([
            'name' => Request::get('tagName'),
            'user_id' => Auth::id(),
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Tag $tag)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tag $tag)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Tag $tag)
    {
        Request::validate($this->rules());

        $tag->name = Request::get('tagName');

        $tag->save();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tag $tag)
    {
        $tag->delete();

        $this->groupService->removeDeletedTagFromQueryOptions($tag->id, Auth::user());
        $this->groupService->updateUserGroupsLinkCount(Auth::user());
    }

    /**
     * Returns all tags for the current user.
     */
    public static function getAllTags(): array
    {
        return Tag::orderBy('name')
            ->filterByCurrentUser()
            ->get()
            ->transform(fn(Tag $tag) => [
                'id' => $tag->id,
                'name' => $tag->name,
            ])
            ->toArray();
    }

    /**
     * Returns tags by their names.
     */
    public static function getTagsByNames(array $names): array
    {
        $locale = $locale ?? Tag::getLocale();

        return Tag::filterByCurrentUser()
            ->where(function (Builder $query) use ($names, $locale) {
                foreach ($names as $name) {
                    $query->orWhere("name->$locale", $name);
                }
            })
            ->get()
            ->transform(fn(Tag $tag) => [
                'id' => $tag->id,
                'name' => $tag->name,
            ])
            ->toArray();
    }

    /**
     * Returns all tags of the given link.
     */
    public static function getTagsOfLink($link): array
    {
        $tags = $link->tags()->filterByCurrentUser()->get();
        $linkTags = [];

        foreach ($tags as $tag) {
            $linkTags[] = (object)[
                'id' => $tag->id,
                'name' => $tag->name
            ];
        }

        return $linkTags;
    }
}
