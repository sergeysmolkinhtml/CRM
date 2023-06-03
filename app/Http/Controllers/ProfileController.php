<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\UpdateProfileContactRequest;
use App\Models\User;
use App\Virtual\Models\Project;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;

class ProfileController extends Controller
{
    public function index(): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('profile.index');
    }

    public function update(UpdateProfileContactRequest $request): RedirectResponse
    {
        auth()->user()->update($request->validated());

        return redirect()->route('admin.profile.index')->with('status', 'Contacts updated.');
    }

    public function changePassword(ChangePasswordRequest $request): RedirectResponse
    {
        auth()->user()->update([
            'password' => bcrypt($request->new_password),
        ]);

        return redirect()->route('admin.profile.index')->with('status', 'Password changed.');
    }
}
