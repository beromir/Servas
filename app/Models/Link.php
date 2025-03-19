<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Query\Builder;
use Illuminate\Validation\Rule;
use Spatie\Searchable\Searchable;
use Spatie\Searchable\SearchResult;
use Spatie\Tags\HasTags;

class Link extends Model implements Searchable
{
    use HasFactory,
        HasTags;

    protected $fillable = [
        'title',
        'link',
    ];

    public string $searchableType = 'Links';

    public static function rules($linkFromRequest, $link = ''): array
    {
        return [
            'title' => 'string|min:2|nullable',
            'link' => [
                'url',
                'required',
                $link !== $linkFromRequest ? Rule::unique(Link::class, 'link')->where(fn(Builder $query) => $query->where('user_id', \Auth::id())) : '',
            ],
        ];
    }

    /**
     * Get all groups for the link.
     */
    public function groups(): MorphToMany
    {
        return $this->morphToMany(Group::class, 'groupable');
    }

    /**
     * Get all group IDs for the link.
     */
    public function groupIds(): array
    {
        return $this->groups()->pluck('id')->toArray();
    }

    /**
     * Get all tag IDs for the link.
     */
    public function tagIds(): array
    {
        return $this->tags()->pluck('id')->toArray();
    }

    public function getCreatedAtForHumansAttribute(bool $withTime = false): string
    {
        if ($withTime) return $this->created_at->format('d.m.Y H:i:s');

        return $this->created_at->format('d.m.Y');
    }

    public function getUpdatedAtForHumansAttribute(bool $withTime = false): string
    {
        if ($withTime) return $this->updated_at->format('d.m.Y H:i:s');

        return $this->updated_at->format('d.m.Y');
    }

    public function getSearchResult(): SearchResult
    {
        $url = route('links.show', $this->id);

        return new SearchResult(
            $this,
            $this->title,
            $url
        );
    }
}
