<?php

namespace App\Jobs;

use App\Events\EmailAddressWasChanged;
use App\Http\Requests\UpdateProfileContactRequest;
use App\Models\User;
use Illuminate\Support\Arr;

final class UpdateProfile
{
    private array $attributes;

    public function __construct(
        private readonly User $user,
        array                 $attributes = []
    ) {
        $this->attributes = Arr::only($attributes, [
            'name', 'email', 'username', 'github_username', 'bio', 'twitter', 'website',
        ]);
    }

    public static function fromRequest(User $user, UpdateProfileContactRequest $request): self
    {
        return new self($user, [
            'first_name' => $request->first_name(),
            'last_name' => $request->last_name(),
            'email' => $request->email(),
            'phone_number' => $request->phone_number(),
        ]);
    }

    public function handle(): void
    {
        $emailAddress = $this->user->emailAddress();

        $this->user->update($this->attributes);

        if ($emailAddress !== $this->user->emailAddress()) {
            $this->user->email_verified_at = null;
            $this->user->save();

            event(new EmailAddressWasChanged($this->user));
        }
    }
}
