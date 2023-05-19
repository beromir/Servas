<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class ListUsersCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:list';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'List all users';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(User $user)
    {
        $userInfo = [];
        $users = $user::all(['id', 'name', 'email', 'created_at']);

        foreach ($users as $user) {
            $userInfo[] = [
                $user->id,
                $user->name,
                $user->email,
                $user->created_at,
            ];
        }

        $this->table(['ID', 'Name', 'Email', 'Created at'], $userInfo);

        return Command::SUCCESS;
    }
}
