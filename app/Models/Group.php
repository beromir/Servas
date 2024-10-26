<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Spatie\Searchable\Searchable;
use Spatie\Searchable\SearchResult;

class Group extends Model implements Searchable
{
    use HasFactory;

    protected $fillable = [
        'title',
        'user_id'
    ];

    protected $casts = [
        'query_options' => 'array',
    ];

    public string $searchableType = 'Groups';

    public static function rules(): array
    {
        return [
            'title' => 'string|min:3',
            'parentGroupId' => 'exists:App\Models\Group,id|numeric|nullable',
            'orTags' => 'array',
            'orTags.*' => 'integer',
            'andTags' => 'array',
            'andTags.*' => 'integer',
            'notTags' => 'array',
            'notTags.*' => 'integer',
        ];
    }

    /**
     * Get all of the links that are assigned this group.
     */
    public function directLinks(): MorphToMany
    {
        return $this->morphedByMany(Link::class, 'groupable');
    }

    /**
     * Get all group links.
     */
    public function links()
    {
        $orTags = $this->getOrTags();
        $andTags = $this->getAndTags();
        $notTags = $this->getNotTags();

        return Link::leftJoin('groupables', 'links.id', '=', 'groupables.groupable_id')
            ->where(function (Builder $query) use ($orTags, $andTags, $notTags) {
                $query->where(function (Builder $query) {
                    $query->where('groupables.group_id', $this->id)
                        ->where('groupables.groupable_type', Link::class);
                })
                    ->when($orTags, function (Builder $query, array $orTags) {
                        $query->orWhere(function (Builder $query) use ($orTags) {
                            $query->withAnyTags($orTags);
                        });
                    })
                    ->when($andTags, function (Builder $query, array $andTags) {
                        $query->orWhere(function (Builder $query) use ($andTags) {
                            $query->withAllTags($andTags);
                        });
                    })
                    ->when($notTags, function (Builder $query, array $notTags) {
                        $query->where(function (Builder $query) use ($notTags) {
                            $query->withoutTags($notTags);
                        });
                    });
            });
    }

    /**
     * Get all child groups.
     */
    public function groups(): HasMany
    {
        return $this->hasMany(Group::class, 'parent_group_id');
    }

    /**
     * Get the group's public link.
     */
    public function publicLink(): MorphOne
    {
        return $this->morphOne(PublicLink::class, 'public_linkable');
    }

    public function getSearchResult(): SearchResult
    {
        $url = route('groups.show', $this->id);

        return new SearchResult(
            $this,
            $this->title,
            $url
        );
    }

    public function updateLinksCount(): int
    {
        return $this->links_count = $this->links()->count();
    }

    public function getOrTags(): array
    {
        return $this->getQueryOption('containsTagsOr');
    }

    public function getAndTags(): array
    {
        return $this->getQueryOption('containsTagsAnd');
    }

    public function getNotTags(): array
    {
        return $this->getQueryOption('containsTagsNot');
    }

    protected function getQueryOption(string $queryOption): array
    {
        $tags = [];

        if ($this->query_options[$queryOption] ?? false) {
            foreach ($this->query_options[$queryOption] as $tag) {
                $tags[] = Tag::find($tag);
            }
        }

        return $tags;
    }
}
