<?php

namespace App\Helpers;

use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Http;

class WebpageData
{
    public static function getWebPageTitle(string $url): string
    {
        $filteredUrl = filter_var($url, FILTER_VALIDATE_URL);

        if ($filteredUrl === false) return '';

        try {
            $html = Http::timeout(30)->get($filteredUrl)->body();

            preg_match_all('@<head.*>.*<title.*>(.*)</title>.*</head>@sU', $html, $matches);

            if (empty($matches[1])) return '';

            $title = trim(html_entity_decode($matches[1][0]));
            $title = strip_tags($title);

            return mb_substr($title, 0, 255);
        } catch (RequestException) {

            return '';
        }
    }
}
