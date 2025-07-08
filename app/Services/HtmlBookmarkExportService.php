<?php

namespace App\Services;

use Illuminate\Support\Carbon;

class HtmlBookmarkExportService
{
    public function createHtmlExport(array $data): string
    {
        $currentDate = time();

        $html = $this->getBookmarkFileHeader($currentDate);

        foreach ($data['links'] as $link) {
            if (empty($link['groups'])) {
                $html .= $this->createBookmarkItem($link);
            }
        }

        foreach ($data['groups'] as $group) {
            $html .= $this->processGroupWithLinks($group, $data['links'], $currentDate);
        }

        $html .= $this->getBookmarkFileFooter();

        return $html;
    }

    private function getBookmarkFileHeader(int $date): string
    {
        return <<<HTML
<!DOCTYPE NETSCAPE-Bookmark-file-1>
<!-- This is an automatically generated file.
     It will be read and overwritten.
     DO NOT EDIT! -->
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=UTF-8">
<meta http-equiv="Content-Security-Policy"
      content="default-src 'self'; script-src 'none'; img-src data: *; object-src 'none'"></meta>
<TITLE>Bookmarks</TITLE>
<H1>Servas export</H1>
<DL><p>
    <DT><H3 ADD_DATE="$date" LAST_MODIFIED="$date">Servas export</H3>
    <DL><p>
HTML
            . "\n";
    }

    private function getBookmarkFileFooter(): string
    {
        return <<<HTML
    </DL><p>
</DL>
HTML;
    }

    private function createBookmarkItem(array $link, int $indentLevel = 0): string
    {
        $url = htmlspecialchars($link['link']);
        $title = htmlspecialchars($link['title'] ?? $url);
        $createdAt = Carbon::parse($link['createdAt'])->unix();
        $updatedAt = Carbon::parse($link['updatedAt'])->unix();
        $tagsAttribute = '';

        if (!empty($link['tags'])) {
            $tags = implode(',', $link['tags']);

            $tagsAttribute = " TAGS=\"$tags\"";
        }

        $indent = str_repeat("\t", $indentLevel);

        return <<<HTML
        $indent<DT><A HREF="$url" ADD_DATE="$createdAt" LAST_MODIFIED="$updatedAt"$tagsAttribute>$title</A>
HTML
            . "\n";
    }

    private function openFolder(string $name, int $date): string
    {
        return <<<HTML
            <DT><H3 ADD_DATE="$date" LAST_MODIFIED="$date">$name</H3>
            <DL><p>
    HTML
            . "\n";
    }

    private function closeFolder(): string
    {
        return <<<HTML
            </DL></p>
    HTML
            . "\n";
    }

    private function getGroupLinks(string $groupTitle, array $links): array
    {
        return array_filter($links, function ($link) use ($groupTitle) {
            // If the link has no groups defined, skip it
            if (!isset($link['groups']) || !is_array($link['groups'])) {
                return false;
            }

            // Check if the groupTitle exists in the link's groups array
            return in_array($groupTitle, $link['groups']);
        });
    }

    private function processGroupWithLinks(array $group, array $links, int $currentDate): string
    {
        $content = $this->openFolder($group['title'], $currentDate);
        $groupLinks = $this->getGroupLinks($group['title'], $links);

        foreach ($groupLinks as $link) {
            $content .= $this->createBookmarkItem($link, 1);
        }

        $content .= $this->closeFolder();

        return $content;
    }
}
