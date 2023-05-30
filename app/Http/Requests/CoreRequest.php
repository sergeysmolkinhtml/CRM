<?php


namespace App\Http\Requests;

use App\Helpers\Reply;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class CoreRequest extends FormRequest
{

    protected function formatErrors(Validator $validator) : array
    {
        return Reply::formErrors($validator);
    }

}
