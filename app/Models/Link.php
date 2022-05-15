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
     * Get all of the groups for the link.
     */
    public function groups(): MorphToMany
    {
        return $this->morphToMany(Group::class, 'groupable');
    }

    /**
     * Get all of the group IDs for the link.
     */
    public function groupIds(): array
    {
        $groupIds = [];
        $groups = $this->groups;

        foreach ($groups as $group) {
            $groupIds[] = $group->id;
        }

        return $groupIds;
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
