<?php

namespace App\Console\Commands;

use App\Models\Group;
use Illuminate\Console\Command;

class UpdateGroupsLinkCount extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'group:update-link-count';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update the link count of all groups';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        foreach (Group::all() as $group) {
            $group->updateLinksCount();

            $group->save();
        }
    }
}
