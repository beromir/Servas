<?php

namespace App\Console\Commands;

use App\Models\Group;
use App\Models\Link;
use Illuminate\Console\Command;
use Spatie\Tags\Tag;

class ShowStatsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'servas:stats';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Show Servas stats';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(Link $link, Group $group, Tag $tag)
    {
        $this->table(['Model', 'Count'], [
            ['Links', Link::all()->count()],
            ['Groups', Group::all()->count()],
            ['Tags', Tag::all()->count()],
        ]);

        return Command::SUCCESS;
    }
}
