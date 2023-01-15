<?php

namespace App\Services\Models;

use App\Http\Requests\StoreDashboardRequest;
use App\Models\Dashboard;
use Illuminate\Support\Facades\Auth;

class DashboardService
{
    public function store(string $title, string|null $description): void
    {
        $dashboard = Dashboard::create([
            'title' => $title,
            'description' => $description,
            'user_id' => Auth::id(),
        ]);
    }
}
