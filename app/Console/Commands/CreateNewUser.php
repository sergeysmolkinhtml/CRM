<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CreateNewUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'taseo:create-new-user {--name=?} {--email=} {--password=} {--count=?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates new user';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $count = $this->option('count');
        $bar = $this->output->createProgressBar($count);
        $bar->start();

        for ($i= 1; $i <= $count; $i++ ) {
            $first_name = $this->option('name') ?? fake()->name();
            $password = $this->option('password');
            $email = $this->option('email');

            User::create([
                'first_name' => $first_name,
                'email' => Str::plural($email) . $email[$i] ,
                'password' => $password
            ]);
            $bar->advance();
        }

        $bar->finish();
        $this->info('Successfully created a user! '. $count . 'Email: ' . $email .' Password: ' . $password);
    }
}
