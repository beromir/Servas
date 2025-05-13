<?php

namespace App\Services;

use Illuminate\Support\Carbon;

class HtmlBookmarkExportService
{
    public function createHtmlExport(array $links): string
    {
        $currentDate = time();

        $html = $this->getBookmarkFileHeader($currentDate);

        foreach ($links as $link) {
            $html .= $this->createBookmarkItem($link);
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

    private function createBookmarkItem(array $link): string
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

        return <<<HTML
        <DT><A HREF="$url" ADD_DATE="$createdAt" LAST_MODIFIED="$updatedAt"$tagsAttribute>$title</A>
HTML
            . "\n";
    }
}
