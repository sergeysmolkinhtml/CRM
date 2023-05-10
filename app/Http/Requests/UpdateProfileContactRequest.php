<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateProfileContactRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'address' => ['required', 'max:255'],
            'phone_number' => ['required', 'max:255'],
            'email' => 'required|email|max:255|unique:users,email,' . Auth::id(),
        ];

    }
    public function first_name(): string
    {
        return (string) $this->get('first_name', '');
    }

    public function last_name(): string
    {
        return (string) $this->get('last_name');
    }

    public function email(): string
    {
        return (string) $this->get('email');
    }

    public function phone_number(): string
    {
        return (string) $this->get('email');
    }



}
