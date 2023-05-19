<?php

namespace App\Console\Commands;

use App\Actions\Fortify\CreateNewUser;
use Illuminate\Console\Command;
use Illuminate\Validation\ValidationException;

class CreateUserCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new user';

    /**
     * Execute the console command.
     *
     * @param CreateNewUser $createNewUser
     * @return int
     */
    public function handle(CreateNewUser $createNewUser): int
    {
        $name = $this->ask('What is your name?');
        $email = $this->ask('What is your email?');
        $password = $this->secret('What is the password?');
        $confirmPassword = $this->secret('Confirm the password?');

        try {
            $createNewUser->create([
                'name' => $name,
                'email' => $email,
                'password' => $password,
                'password_confirmation' => $confirmPassword
            ]);
        } catch (ValidationException $exception) {
            $this->error($exception->getMessage());
            return Command::FAILURE;
        }

        $this->info('The user was created!');

        return Command::SUCCESS;
    }
}
