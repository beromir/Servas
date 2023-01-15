<?php

namespace App\Services\Models;

use App\Models\Dashboard;
use Illuminate\Support\Facades\Request;

class DashboardService
{
    protected array $rules = [
        'title' => 'string|required',
        'description' => 'string|nullable'
    ];

    protected function store(Request $request)
    {
        Request::validate($this->rules);

        $dashboard = Dashboard::make();

    }
}
