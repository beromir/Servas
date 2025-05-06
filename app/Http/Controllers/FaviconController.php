<?php

namespace App\Http\Controllers;

use App\Models\Link;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class FaviconController extends Controller
{
    public function show(Link $link): \Symfony\Component\HttpFoundation\BinaryFileResponse
    {
        $host = ($info = parse_url($link->link)) ? $info['host'] : null;
        $hash_string = md5($host);

        $url = "https://t2.gstatic.com/faviconV2?client=SOCIAL&type=FAVICON&fallback_opts=TYPE,SIZE,URL&url={$link->link}&size=32";

        $cache_folder = Storage::disk('public')->path('cache/favicon');
        $dir = $cache_folder . '/' . substr($hash_string, 0, 1) . '/' . substr($hash_string, 1, 2);
        $file_path = $dir . '/' . $hash_string . '.png';

        // Check if the favicon already exists in the cache
        if (!File::exists($file_path)) {
            try {
                // Fetch the favicon from the URL
                $favicon_content = file_get_contents($url);

                // Save the favicon to the cache
                if ($favicon_content !== false) {
                    // Ensure the directory exists
                    if (!File::exists($dir)) {
                        File::makeDirectory($dir, 0755, true);
                    }

                    File::put($file_path, $favicon_content);
                }
            } catch (Exception $exception) {
                // if (str_contains($exception->getMessage(), '404 Not Found'))
                return response()->file(Storage::disk('local')->path('/vendor/empty-favicon.png'));
            }
        }

        return response()->file($file_path);
    }
}
