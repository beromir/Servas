<?php

namespace App\Http\Controllers;

use App\Services\BulkEditingService;
use Illuminate\Support\Facades\Request;

class BulkEditingController extends Controller
{
    public function editLinks(Request $request, BulkEditingService $bulkEditingService): void
    {
        $action = $request::get('action');
        $links = $request::get('links');
        $groups = $request::get('groups');

        $bulkEditingService->handleLinkEditingAction($action, $links, $groups);
    }
}
