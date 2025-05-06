<?php

namespace App\Http\Controllers;

use App\Http\Requests\ExportRequest;
use App\Services\ExportService;
use App\Services\HtmlBookmarkExportService;
use Illuminate\Support\Facades\Auth;

class ExportController extends Controller
{
    public function export(ExportRequest $request, HtmlBookmarkExportService $htmlBookmarkExportService, ExportService $exportService)
    {
        $validated = $request->validated();

        $exportFormat = $validated['exportFormat'];
        $exportOptions = $validated['exportOptions'];

        $export = $exportService->exportUserData($exportOptions, Auth::user());

        if ($exportFormat === 'json') {
            header('Content-Type: application/json');
            header('Cache-Control: no-store, no-cache');
            header('Content-Disposition: attachment; filename="export.json"');

            echo json_encode($export);
        } elseif ($exportFormat === 'html' && array_key_exists('links', $export)) {
            $html = $htmlBookmarkExportService->createHtmlExport($export['links']);

            header('Content-Type: text/html');
            header('Cache-Control: no-store, no-cache');
            header('Content-Disposition: attachment; filename="export.html"');

            echo $html;
        }

        return back();
    }
}
