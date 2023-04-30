<?php

namespace App\Http\Controllers;

use App\Models\Link;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;

class LaterController extends Controller
{
    /**
     * Get all links from the later list for the current user.
     */
    static public function getLinksFromLaterList(): Collection
    {
        return DB::table('later')
            ->join('links', 'later.link_id', '=', 'links.id')
            ->where('user_id', Auth::id())
            ->get();
    }

    /**
     * Check if the link is on the later list.
     */
    static public function isLinkOnLaterList(int $linkId): bool
    {
        return DB::table('later')
            ->where('link_id', $linkId)
            ->exists();
    }

    public function toggleLinkOnLaterList()
    {
        $linkId = Request::get('linkId');

        $link = Link::find($linkId);

        // Check if the link belongs to the current user
        if ($link?->user_id !== Auth::id()) {
            return Redirect::back();
        }

        // Get all links with the link ID from the later list
        $laterLinks = DB::table('later')
            ->where('link_id', $linkId)
            ->get();

        if ($laterLinks->isEmpty()) {
            $this->addLinkToLaterList($linkId);
        } else {
            $this->removeLinkFromLaterList($linkId);
        }
    }

    protected function addLinkToLaterList(int $linkId): void
    {
        DB::table('later')->insert([
            'link_id' => $linkId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    protected function removeLinkFromLaterList(int $linkId): void
    {
        DB::table('later')->where('link_id', $linkId)->delete();
    }
}
