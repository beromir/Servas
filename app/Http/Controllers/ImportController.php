<?php

namespace App\Http\Controllers;

use App\Http\Requests\ImportRequest;
use App\Services\HtmlBookmarkService;
use App\Services\ImportService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ImportController extends Controller
{
    public function import(ImportRequest $request, HtmlBookmarkService $htmlBookmarkService, ImportService $importService)
    {
        $validated = $request->validated();

        $importSource = $validated['importSource'];
        $importOptions = $validated['importOptions'];

        /** @var UploadedFile $importFile */
        $importFile = $validated['importFile'];

        if ($importSource === 'html') {
            $links = $htmlBookmarkService->extractLinks($importFile->getContent());
        }

//        if (!Str::isJson($importFile->getContent())) {
//
//            return back();
//        }

        $importService->importUserData($links, $importOptions, Auth::user());

        return back();
    }
}
