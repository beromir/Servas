<?php

namespace App\Models;

use App\Enums\MediaType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Spatie\Tags\HasTags;

class Link extends Model
{
    use HasFactory,
        HasTags;

    protected $fillable = [
        'title',
        'link',
        'media_type'
    ];

    protected $casts = [
        'media_type' => MediaType::class,
    ];

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
}
