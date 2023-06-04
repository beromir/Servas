<?php

namespace App\Http\Controllers;

use App\Http\Requests\ImportRequest;
use App\Services\ImportService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;

class ImportController extends Controller
{
    public function import(ImportRequest $request, ImportService $importService)
    {
        $validated = $request->validated();

        $importOptions = $validated['importOptions'];

        /** @var UploadedFile $importFile */
        $importFile = $validated['importFile'];

        if (!Str::isJson($importFile->getContent())) {
            Redirect::back();
        }

        $importService->importUserData(json_decode($importFile->getContent(), true), $importOptions, Auth::user());
    }
}
