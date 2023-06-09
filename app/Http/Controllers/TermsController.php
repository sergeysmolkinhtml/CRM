<?php

namespace App\Http\Controllers;

use App\Http\Requests\TermsAcceptRequest;

class TermsController extends Controller
{
    public function index()
    {
        return view('terms');
    }

    public function store(TermsAcceptRequest $request): \Illuminate\Http\RedirectResponse
    {
        auth()->user()->update([
            'terms_accepted' => true
        ]);

        return redirect()->route('admin.home');
    }
}
