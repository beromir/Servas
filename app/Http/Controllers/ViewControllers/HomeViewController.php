<?php

namespace App\Http\Controllers\ViewControllers;

use App\Http\Controllers\Controller;
use App\Http\Controllers\WidgetControllers\LastAddedWidgetController;
use App\Http\Controllers\WidgetControllers\LaterWidgetController;
use App\Http\Controllers\WidgetControllers\StatsWidgetController;
use Inertia\Inertia;
use Inertia\Response;

class HomeViewController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Home/Index', [
            'stats' => StatsWidgetController::getData(),
            'later' => LaterWidgetController::getData(),
            'lastAdded' => LastAddedWidgetController::getData(),
        ]);
    }
}
