<?php

namespace App\Traits;

use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait BelongsToUser
{
    protected static string $userModel = User::class;
    protected static string $userColumnName = 'user_id';

    protected function initializeBelongsToUser()
    {
        $this->fillable[] = static::$userColumnName;
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(static::$userModel);
    }
}
