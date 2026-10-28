<?php

use App\Services\HtmlBookmarkImportService;
use Tests\TestCase;

uses(TestCase::class);

test('extracts links with their groups from a browser bookmark export', function () {
    $html = <<<'HTML'
<!DOCTYPE NETSCAPE-Bookmark-file-1>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=UTF-8">
<TITLE>Bookmarks</TITLE>
<H1>Bookmarks</H1>
<DL><p>
    <DT><A HREF="https://root.example.com/" ADD_DATE="1700000000">Root link</A>
    <DT><H3 ADD_DATE="1700000000" LAST_MODIFIED="1700000000">Empty group</H3>
    <DL><p>
    </DL><p>
    <DT><A HREF="https://after-empty.example.com/" ADD_DATE="1700000000">After empty group</A>
    <DT><H3 ADD_DATE="1700000000" LAST_MODIFIED="1700000000">Parent</H3>
    <DL><p>
        <DT><H3 ADD_DATE="1700000000" LAST_MODIFIED="1700000000">Child</H3>
        <DL><p>
            <DT><A HREF="https://child.example.com/" ADD_DATE="1700000000">Child link</A>
        </DL><p>
        <DT><A HREF="https://parent.example.com/" ADD_DATE="1700000000" TAGS="a,b">Parent link</A>
    </DL><p>
    <DT><A HREF="https://last.example.com/" ADD_DATE="1700000000">Last link</A>
</DL><p>
HTML;

    $data = (new HtmlBookmarkImportService())->extractData($html);

    expect(array_column($data['groups'], 'title'))->toBe(['Empty group', 'Parent', 'Child'])
        ->and(array_column($data['links'], 'groups', 'title'))->toBe([
            'Root link' => [],
            'After empty group' => [],
            'Child link' => ['Child'],
            'Parent link' => ['Parent'],
            'Last link' => [],
        ])
        ->and($data['tags'])->toBe(['a', 'b']);
});
