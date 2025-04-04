<?php

namespace App\Services;

use Carbon\Carbon;

class HtmlBookmarkService
{
    public function extractLinks(string $htmlContent): array
    {
        $lines = explode("\n", $htmlContent);

        $groups = [];

        foreach ($lines as $line) {
            // Get link
            $linkMatches = [];

            preg_match('@<a(.+)>(.+)</a>@mi', $line, $linkMatches);

            if ($linkMatches) {
                $attributes = $linkMatches[1] ?? '';

                $link = [
                    'title' => $linkMatches[2] ?? '',
                    'link' => $this->extractAttributeValue('href', $attributes),
                    'createdAt' => Carbon::createFromTimestamp($this->extractAttributeValue('add_date', $attributes))->toDateTimeString(),
                    'updatedAt' => Carbon::createFromTimestamp($this->extractAttributeValue('last_modified', $attributes))->toDateTimeString(),
                    'groups' => array_slice($groups, -1)[0] ? [array_slice($groups, -1)[0]] : [],
                    'tags' => explode(',', $this->extractAttributeValue('tags', $attributes)),
                ];
            }

            // Get group
            $groupMatches = [];

            preg_match('@<h3.*>(.+)</h3>@mi', $line, $groupMatches);

            if ($groupMatches && $groupMatches[1] ?? false) {
                $group = [
                    'title' => $groupMatches[1],
                ];

                $groups[] = $group['title'];
            }

            // Group opened
            $groupOpened = str_contains(strtolower($line), '<dl>');

            // Group closed
            $groupClosed = str_contains(strtolower($line), '</dl>');

            if ($groupClosed) {
                array_pop($groups);
            }
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
