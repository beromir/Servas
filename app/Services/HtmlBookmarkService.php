<?php

namespace App\Services;

use Carbon\Carbon;
use DOMDocument;
use DOMNode;

class HtmlBookmarkService
{
    public function extractLinks(string $htmlContent): array
    {
        // Initialize empty array to store links and titles
        $links = [];

        // Create a new DOMDocument instance
        $dom = new DOMDocument();

        // Suppress warnings that might be generated from malformed HTML
        libxml_use_internal_errors(true);

        // Load the HTML content
        $dom->loadHTML($htmlContent);

        // Clear any errors that might have been generated
        libxml_clear_errors();

        // Get all anchor elements directly using getElementsByTagName
        $anchorElements = $dom->getElementsByTagName('a');

        // Iterate through each anchor element
        /** @var DOMNode $anchor */
        foreach ($anchorElements as $anchor) {
            // Check if the anchor has an href attribute
            if (empty($href = $anchor->attributes->getNamedItem('href')->textContent)) {
                continue;
            }

            // Get the text content of the anchor (the title)
            $title = trim($anchor->textContent);

            // Only allow links that start with http:// or https://
            if (!str_starts_with($href, 'http://') && !str_starts_with($href, 'https://')) {
                continue;
            }

            // Get ADD_DATE and LAST_MODIFIED attributes if they exist
            $addDate = null;
            $lastModified = null;

            // Convert Unix timestamp to datetime string using Carbon
            $timestamp = $anchor->attributes->getNamedItem('add_date')?->textContent;
            if ($timestamp) {
                $addDate = Carbon::createFromTimestamp(intval($timestamp))->toDateTimeString();
            }

            $timestamp = $anchor->attributes->getNamedItem('last_modified')?->textContent;
            if ($timestamp) {
                $lastModified = Carbon::createFromTimestamp(intval($timestamp))->toDateTimeString();
            }

            // Add the link, title, and dates to the array
            $links['links'][] = [
                'title' => $title,
                'link' => $href,
                'createdAt' => $addDate,
                'updatedAt' => $lastModified
            ];
        }

        return $links;
    }
}
