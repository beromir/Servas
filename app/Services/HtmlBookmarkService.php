<?php

namespace App\Services;

use Carbon\Carbon;

class HtmlBookmarkService
{
    public function extractLinks(string $htmlContent): array
    {
        $lines = explode("\n", $htmlContent);

        foreach ($lines as $line) {
            // Get link
            $linkMatches = [];

            preg_match('@<a(.+)>(.+)</a>@mi', $line, $linkMatches);

            if ($linkMatches) {
                $attributes = $linkMatches[1] ?? '';

                $link = [
                    'title' => $linkMatches[2] ?? '',
                    'href' => $this->extractAttributeValue('href', $attributes),
                    'tags' => explode(',', $this->extractAttributeValue('tags', $attributes)),
                    'addDate' => Carbon::createFromTimestamp($this->extractAttributeValue('add_date', $attributes))->toDateTimeString(),
                    'lastModified' => Carbon::createFromTimestamp($this->extractAttributeValue('last_modified', $attributes))->toDateTimeString(),
                ];
            }

            // Get group
            $groupMatches = [];

            preg_match('@<h3.*>(.+)</h3>@mi', $line, $groupMatches);

            if ($groupMatches) {
                $group = [
                    'title' => $groupMatches[1] ?? '',
                ];
            }

            // Group opened
            $groupOpened = str_contains(strtolower($line), '<dl>');

            // Group closed
            $groupClosed = str_contains(strtolower($line), '</dl>');
        }

        die();

        return $matches;
    }

    protected function extractAttributeValue(string $attributeName, string $subject)
    {
        $matches = [];

        preg_match("@$attributeName=\"(.+)\"(?:\s|$)@miU", $subject, $matches);

        return $matches[1] ?? '';
    }
}
