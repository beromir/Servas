<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class PublicLink extends Model
{
    /**
     * Get the parent public linkable model.
     */
    public function publicLinkable(): MorphTo
    {
        return $this->morphTo();
    }

    public function getLink(): string
    {
        return url("share/$this->share_id");
    }
}
