<?php

namespace App\Console\Commands;

use App\Services\HtmlBookmarkService;
use Illuminate\Console\Command;

class DebugCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:debug';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle(HtmlBookmarkService $htmlBookmarkService)
    {
        $htmlBookmarkService->test();
    }
}