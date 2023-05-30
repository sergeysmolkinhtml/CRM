<?php


namespace App\Http\Requests\Admin\Employee;

use Illuminate\Foundation\Http\FormRequest;

class SkillRequest extends FormRequest
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

        if (isset($this->skills) && count(json_decode($this->skills))) {
            $emplSkills = json_decode($this->skills);

            $rules = ['emplSkillsError.*.skill_id' => 'distinct|required'];


//            foreach ($emplSkills as $key => $val) {
//                $rules['skill_id.' . $key] = 'required' . $val->skill_id;
//
//            }
        }

        return match ($this->getMethod()) {
            'PUT', 'POST' => $rules,
            'DELETE' => [],
            default => $rules,
        };
    }

    protected function getValidatorInstance()
    {
        $skills = $this->request->get('skills');
        $this->merge(['emplSkillsError' => json_decode($skills, true)]);

        $validator = parent::getValidatorInstance();

        return $validator;
    }


    public function messages()
    {
        return [
            'emplSkillsError.*.skill_id.required' => 'The skill field is required.',
        ];
    }


}
