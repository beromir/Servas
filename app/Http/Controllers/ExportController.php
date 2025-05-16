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

        $export = $exportService->exportUserData(Auth::user());

        if ($exportFormat === 'json') {

            return response()->streamDownload(function () use ($export) {
                echo json_encode($export, JSON_PRETTY_PRINT);
            }, 'export.json', [
                'Content-Type' => 'application/json',
            ]);
        } elseif ($exportFormat === 'html' && array_key_exists('links', $export)) {

            return response()->streamDownload(function () use ($htmlBookmarkExportService, $export) {
                echo $htmlBookmarkExportService->createHtmlExport($export);
            }, 'export.html', [
                'Content-Type' => 'text/html',
            ]);
        }

        return back();
    }
}
