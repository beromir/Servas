<?php

namespace App\Http\Controllers;

use App\Http\Requests\ImportRequest;
use App\Services\HtmlBookmarkImportService;
use App\Services\ImportService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ImportController extends Controller
{
    public function import(ImportRequest $request, HtmlBookmarkImportService $htmlBookmarkImportService, ImportService $importService)
    {
        $validated = $request->validated();

        $importSource = $validated['importSource'];
        $importOptions = $validated['importOptions'];

        /** @var UploadedFile $importFile */
        $importFile = $validated['importFile'];

        if ($importSource === 'json' && Str::isJson($importFile->getContent())) {
            $data = json_decode($importFile->getContent(), true);
        } elseif ($importSource === 'html') {
            $data = $htmlBookmarkImportService->extractData($importFile->getContent());
        } else {

            return back();
        }

        $importService->importUserData($data, $importOptions, Auth::user());

        return back();
    }
}
