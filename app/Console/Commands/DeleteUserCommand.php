<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class DeleteUserCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:delete';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete a user';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
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

        if ($this->confirm("Are you sure you want to delete the user with the email: $user->email?")) {
            $user->delete();

            $this->info('The user was deleted!');

            return Command::SUCCESS;
        }

        $this->error('Please confirm the deletion.');

        return Command::FAILURE;
    }
}
