<?php


namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmployeeTaskBoardRequest extends FormRequest
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
    public function rules()
    {
        $regex = '/^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/';
        return [
            'url_video_to_drive_disk' => 'nullable|url|regex:' . $regex,
            'url_video_to_youtube' => 'nullable|url|regex:' . $regex,
            'sub_titles' => 'nullable|url|regex:' . $regex,
            'url_contract' => 'nullable|url|regex:' . $regex,
            'translate_resume' => 'nullable|url|regex:' . $regex,
            'url_portrait' => 'nullable|url|regex:' . $regex,
            'link_rhs' => 'nullable|url|regex:' . $regex,

        ];
    }
}
