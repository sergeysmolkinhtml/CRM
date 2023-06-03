<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Http\Requests\EditUserRequest;
use App\Http\Requests\CreateUserRequest;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Request;

class UserController extends Controller
{
    public function index()
    {
        $withDeleted = null;

        if (in_array(request('deleted'), User::FILTER) && request('deleted') === 'true') {
            $withDeleted = true;
        }


        $users = Cache::remember('users-list', 60*60*24, function () use ($withDeleted) {
           return User::with('roles')
                ->when($withDeleted, function ($query) {
                    $query->withTrashed();
                    }
                )->paginate(20);
           });

        return view('users.index', compact('users', 'withDeleted'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(CreateUserRequest $request)
    {
        User::create($request->validated());

        return redirect()->route('admin.users.index');
    }

    public function edit(User $user)
    {

        return view('users.edit', compact('user'));
    }

    public function update(EditUserRequest $request, User $user)
    {
        $user->update($request->validated());

        return redirect()->route('admin.users.index');
    }

    public function destroy(User $user): \Illuminate\Http\RedirectResponse
    {
        $user->delete();

        return redirect()->route('admin.users.index');
    }

    public function search()
    {
        $inputText = request()->input('userSearch');
        $users = User::where('first_name','LIKE', '%'. $inputText . '%')->get();

        return view('users.partials.search',compact('users'));
    }
}
