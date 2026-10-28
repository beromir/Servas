<?php

namespace App\Services;

use Carbon\Carbon;

class HtmlBookmarkImportService
{
    public function extractData(string $htmlContent): array
    {
        $data = [
            'links' => [],
            'groups' => [],
            'tags' => [],
        ];

        $lines = explode("\n", $htmlContent);

        // Stack of the groups the current line is nested in (null for lists that do not belong to a group)
        $groups = [];

        // Group whose list has not been opened yet
        $pendingGroup = null;

        foreach ($lines as $line) {
            // Get link
            $linkMatches = [];

            preg_match('@<a(.+)>(.+)</a>@mi', $line, $linkMatches);

            if ($linkMatches) {
                $attributes = $linkMatches[1] ?? '';

                $tags = $this->extractAttributeValue('tags', $attributes);
                $tags = $tags ? explode(',', $this->extractAttributeValue('tags', $attributes)) : [];

                $link = [
                    'title' => $linkMatches[2] ?? '',
                    'link' => $this->extractAttributeValue('href', $attributes),
                    'createdAt' => Carbon::createFromTimestamp($this->extractAttributeValue('add_date', $attributes))->toDateTimeString(),
                    'updatedAt' => Carbon::createFromTimestamp($this->extractAttributeValue('last_modified', $attributes))->toDateTimeString(),
                    'groups' => end($groups) ? [end($groups)] : [],
                    'tags' => $tags,
                ];

                $data['links'][] = $link;
                $data['tags'] = array_unique(array_merge($data['tags'], $tags));
            }

            // Get group
            $groupMatches = [];

            preg_match('@<h3.*>(.+)</h3>@mi', $line, $groupMatches);

            if ($groupMatches && $groupMatches[1] ?? false) {
                $group = [
                    'title' => $groupMatches[1],
                    'childGroups' => [],
                ];

                $pendingGroup = $group['title'];

                $data['groups'][] = $group;
            }

            // Group opened
            if (preg_match('@<dl[\s>]@i', $line)) {
                $groups[] = $pendingGroup;
                $pendingGroup = null;
            }

            // Group closed
            if (str_contains(strtolower($line), '</dl>')) {
                array_pop($groups);
            }
        }

        return $data;
    }

    protected function extractAttributeValue(string $attributeName, string $subject)
    {
        $matches = [];

        preg_match("@$attributeName=\"(.+)\"(?:\s|$)@miU", $subject, $matches);

        return $matches[1] ?? '';
    }
}
