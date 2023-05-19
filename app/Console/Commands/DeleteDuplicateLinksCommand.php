<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Services\LinkService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class DeleteDuplicateLinksCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'link:delete-duplicates';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete the duplicate links of the user';

    /**
     * Execute the console command.
     */
    public function handle(LinkService $linkService): int
    {
        $userId = $this->ask('User ID');

        try {
            Validator::make(['id' => $userId], [
                'id' => ['required', 'numeric'],
            ])->validate();
        } catch (ValidationException $exception) {
            $this->error($exception->getMessage());
            return Command::FAILURE;
        }

        $user = User::find($userId);

        if (empty($user)) {
            $this->error('The user does not exist.');

            return Command::FAILURE;
        }

        $duplicates = $linkService->getDuplicates($user);

        if (count($duplicates) === 0) {
            $this->info('No duplicates found.');

            return Command::SUCCESS;
        }

        if ($this->confirm(count($duplicates) . ' links found. Do you want to delete them?')) {
            $linkService->deleteDuplicates($duplicates);

            $this->info('Duplicate links deleted.');

            return Command::SUCCESS;
        }

        $this->error('Please confirm the deletion.');

        return Command::FAILURE;
    }
}
