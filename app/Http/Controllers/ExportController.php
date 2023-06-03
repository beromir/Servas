<?php

namespace App\Http\Controllers;

use App\Http\Requests\ExportRequest;
use App\Services\ExportService;
use Illuminate\Support\Facades\Auth;

class ExportController extends Controller
{
    public function export(ExportRequest $request)
    {
        $data = $request->validated('exportOptions');

        $export = ExportService::exportUserData(Auth::user());

        $export = json_encode($export);

        header('Content-Type: application/json');
        header('Cache-Control: no-store, no-cache');
        header('Content-Disposition: attachment; filename="export.json"');

        echo $export;
    }
}
