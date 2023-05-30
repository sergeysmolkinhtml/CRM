<?php


namespace App\Http\Requests\Admin\Employee;

use Illuminate\Foundation\Http\FormRequest;

class LanguageRequest extends FormRequest
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
        $rules = [];

        if (isset($this->empl_languages) && count(json_decode($this->empl_languages))) {
//            $emplLanguages = json_decode($this->empl_languages);

            $rules = ['emplLanguagesError.*.lang_id' => 'required|distinct',
                'emplLanguagesError.*.level_id' => 'required'
            ];


//            foreach ($emplLanguages as $key => $val) {
//                $rules['lang_id.' . $key] = 'required' . $val->lang_id;
//                $rules['level_id.' . $key] = 'required' . $val->level_id;
//            }
        }


        switch ($this->getMethod()) {
            case 'PUT':
            case 'POST':
                return
                    $rules;

            case 'DELETE':
                return [];
        }
        return $rules;
    }


    protected function getValidatorInstance()
    {
        $languages = $this->request->get('empl_languages');
        $this->merge(['emplLanguagesError' => json_decode($languages, true)]);

        $validator = parent::getValidatorInstance();

        return $validator;
    }

    public function messages()
    {
        return [
            'emplLanguagesError.*.lang_id.distinct' => 'The lang id field has a duplicate value.',
            'emplLanguagesError.*.level_id.required' => 'The level id field is required.',
            'emplLanguagesError.*.lang_id.required' => 'The lang id field is required.'
        ];
    }

}
