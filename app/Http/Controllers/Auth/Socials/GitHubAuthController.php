<?php

namespace App\Http\Controllers\Auth\Socials;

use App\Http\Controllers\Controller;
use App\Jobs\UpdateProfile;
use App\Models\User;
use App\Socials\GithubUser;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\InvalidStateException;
use Laravel\Socialite\Two\User as SocialiteUser;

/**
 *
 */
class GitHubAuthController extends Controller
{
    /**
     * Redirect the user to the GitHub authentication page.
     */
    public function redirectToProvider(): \Symfony\Component\HttpFoundation\RedirectResponse|RedirectResponse
    {
        return Socialite::driver('github')->redirect();
    }

    /**
     * Obtain the user information from GitHub.
     */
    public function handleProviderCallback(): RedirectResponse
    {
        try {
            $socialiteUser = $this->getSocialiteUser();

        } catch (InvalidStateException $exception) {
            $this->error('errors.github_invalid_state');

            return redirect()->route('login');
        }

        try {
            $user = User::findByGithubId($socialiteUser->getId());
        } catch (ModelNotFoundException $exception) {
            return $this->userNotFound(new GithubUser($socialiteUser->getRaw()));
        }

        return $this->userFound($user, $socialiteUser);
    }

    private function getSocialiteUser(): SocialiteUser
    {
        return Socialite::driver('github')->user();
    }

    /**
     * @param User $user
     * @param SocialiteUser $socialiteUser
     * @return RedirectResponse
     */
    private function userFound(User $user, SocialiteUser $socialiteUser): RedirectResponse
    {
        dispatch_sync(new UpdateProfile($user, ['github_username' => $socialiteUser->getNickname()]));

        Auth::login($user, true);

        return redirect()->route('admin.profile.index');
    }

    private function userNotFound(GithubUser $user): RedirectResponse
    {
        if ($user->isTooYoung()) {
            $this->error('errors.github_account_too_young');

            return redirect()->home();
        }

        return $this->redirectUserToRegistrationPage($user);
    }

    private function redirectUserToRegistrationPage(GithubUser $user): RedirectResponse
    {
        session(['githubData' => $user->toArray()]);

        return redirect()->route('register');
    }
}
