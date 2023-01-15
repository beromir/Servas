<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Dashboard extends Model
{
    protected $fillable = [
        'title',
        'description',
        'settings',
    ];

    /**
     * Get all dashboard columns for to the dashboard.
     */
    public function dashboardColumns(): HasMany
    {
        return $this->hasMany(DashboardColumn::class);
    }
}
