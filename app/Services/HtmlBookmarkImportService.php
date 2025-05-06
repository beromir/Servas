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

        $groups = [];

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
                    'groups' => array_slice($groups, -1)[0] ? [array_slice($groups, -1)[0]] : [],
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

                $groups[] = $group['title'];

                $data['groups'][] = $group;
            }

            // Group opened
            $groupOpened = str_contains(strtolower($line), '<dl>');

            // Group closed
            $groupClosed = str_contains(strtolower($line), '</dl>');

            if ($groupClosed) {
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
