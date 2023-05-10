<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Config\Repository as Config;
use Illuminate\Validation\Factory as ValidatorFactory;


class CreateSuperAdmin extends Command
{
    protected $signature = 'taseo:superadmin {email?} {password?}';

    protected $description = "Create the superadmin account";

    protected ValidatorFactory $validatorFactory;

    protected Config $config;

    public function __construct(ValidatorFactory $validatorFactory, Config $config)
    {
        parent::__construct();

        $this->validatorFactory = $validatorFactory;
        $this->config = $config;
    }

    public function handle()
    {
        $this->info("Let's create a superadmin account!");
        $email = $this->setEmail();
        $password = $this->setPassword();

        $user = User::create([
            'name' => "Admin",
            'email' => $email,
            'role' => 'super_admin',
            'published' => true,
        ]);

        $user->password = Hash::make($password);
        $user->save();

        $this->info("Your account has been created");
    }

    private function setEmail()
    {
        if (filled($email = $this->argument('email'))) {
            return $email;
        }
        $email = $this->ask('Enter an email');
        if ($this->validateEmail($email)) {
            return $email;
        } else {
            $this->error("Your email is not valid");
            return $this->setEmail();
        }
    }

    private function setPassword()
    {
        if (filled($email = $this->argument('password'))) {
            return $email;
        }
        $password = $this->secret('Enter a password');
        if ($this->validatePassword($password)) {
            $confirmPassword = $this->secret('Confirm the password');
            if ($password === $confirmPassword) {
                return $password;
            } else {
                $this->error('Password does not match the confirm password');
                return $this->setPassword();
            }
        } else {
            $this->error("Your password is not valid, at least 6 characters");
            return $this->setPassword();
        }
    }

    private function validateEmail($email): bool
    {
        return $this->validatorFactory->make(['email' => $email], [
            'email' => 'required|email|max:255|unique:' . $this->config->get('crm.users_table'),
        ])->passes();
    }

    private function validatePassword($password): bool
    {
        return $this->validatorFactory->make(['password' => $password], [
            'password' => 'required|min:6',
        ])->passes();
    }
}
