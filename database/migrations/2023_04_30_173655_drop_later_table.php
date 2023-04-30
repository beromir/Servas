<?php

use App\Models\Group;
use App\Models\Link;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $laterLinks = DB::table('later')->get();

        // Move all links from the later list to a new group
        foreach ($laterLinks as $laterLink) {
            $link = Link::find($laterLink->link_id);

            if (!($group = Group::where('title', 'Later list from migration')->where('user_id', $link->user_id)->first())) {
                $group = Group::make();

                $group->title = 'Later list from migration';
                $group->user_id = $link->user_id;

                $group->save();
            }

            if (!$group->links()->find($link->id)) {
                $group->links()->attach($link->id);
            }
        }

        Schema::table('later', function (Blueprint $table) {
            $table->dropIfExists();
        });
    }
};
