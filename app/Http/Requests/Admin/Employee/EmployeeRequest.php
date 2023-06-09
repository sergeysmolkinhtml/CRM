<?php


namespace App\Http\Requests\Admin\Employee;

use App\Config\DefaultConfig;
use App\Models\Country;
use App\Models\Employee\EmployeeDetail;
use App\Models\Employee\EmployeeStatus;
use App\HrSource;
use App\Http\Requests\CoreRequest;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EmployeeRequest extends CoreRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize() : bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() : array
    {

        $rules = [
            'employee_id' => 'required|unique:employee_details',
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'gender' => 'required',
            'hourly_rate' => 'nullable|numeric',
            'joining_date' => 'required',
            'last_date' => 'nullable|after_or_equal:joining_date',
            'department' => 'required|exists:teams,id',
            'designation_id' => 'required|exists:designations,id',
            'position_id' => 'required|exists:positions,id',
            'nameEn' => 'required',
            'name_ua' => 'required',
            'photo_url' => 'nullable|url',
            'birthday_date' => 'required',
            'mobile' => 'required',
            'work_email' => 'nullable|email',
            'h_email' => 'nullable|email',
            'viber' => 'required',
            'discord' => 'nullable',
            'country_id' => 'required|exists:countries,id',
            'city_id' => 'required|exists:cities,id',
            'statusEmpl' => 'required|exists:employees_statuses,id',
            'flat' => 'nullable|integer',
            'postal_code' => 'nullable|integer',
            'employeeDepartmentsError.*.department_id' => "distinct",
            'short_name' => [
                'required',
                Rule::unique('employee_details')->where(function ($query) {
                    $query->where('company_id', company()->id());;
                })],
            'slug' => [
                'required',
                Rule::unique('employee_details')->where(function ($query) {
                    $query->where('company_id', company()->id());;
                })],
            'is_student' => ['required', Rule::in([0, 1])],
            'hrLanguages.*.lang_id' => 'distinct',
//            'position_id'=>"required|exists:positions,id",
            /*'start_position' => 'required',
            'city' => 'required',
            'resume' => 'required|url',
            'street' => 'required',
            'slack_username' => 'nullable|unique:employee_details,slack_username',
            'language.*' => 'required|exists:languages,id',
            'level.*' => 'required|exists:lang_level,id',*/
        ];
//        dd("cd");

        if (isset($this->empl_languages) && count(json_decode($this->empl_languages))) {
            $emplLanguages = json_decode($this->empl_languages);

            foreach ($emplLanguages as $key => $val) {
                $rules['lang_id.' . $key] = 'required' . $val->lang_id;
                $rules['level_id.' . $key] = 'required' . $val->level_id;
            }
        }

//        }

        $employeeStatus = EmployeeStatus::where('name', [__("modules.statusEmployees.fired")])->orWhere('name', [__("modules.statusEmployees.left")])->pluck('id')->toArray();
        if (in_array($this->statusEmpl, $employeeStatus)) $rules['reason_dismissal'] = 'required';


        $ukraine_id = Country::where('name', '=', 'Ukraine')->first();

        if ($ukraine_id) {
            if ($this->country_id != $ukraine_id->id) {
                $rules['city_id'] = 'nullable|exists:cities,id';
            }
        }

        switch ($this->getMethod()) {
            case 'POST':
                $rules["hr_address_id"] = 'required|integer';
                $rules["password"] = 'required|min:6';
                return
                    $rules;

            case 'PUT':

                $detailID = EmployeeDetail::where('user_id', $this->route('employee'))->first();

                if (! $detailID) {
                    $detailID = new EmployeeDetail();
                    $detailID->user_id = $this->route('employee');
                    $detailID->save();
                }
                $rules["employee_id"] = [
                    'required',
                    Rule::unique('employee_details')->where(function ($query) use ($detailID) {
                        $query->where('company_id', company()->id);
                        $query->where('id', '<>', $detailID->id);
                    })
                ];
                $rules["email"] = 'required|unique:users,email,' . $this->route('employee');
                $rules["work_email"] = 'nullable|email|unique:employee_details,work_email,' . $detailID->id;
                $rules["h_email"] = 'nullable|email|unique:employee_details,work_hemail,' . $detailID->id;
                $rules["short_name"] = [
                    'required',
                    Rule::unique('employee_details')->where(function ($query) use ($detailID) {
                        $query->where('company_id', company()->id);
                        $query->where('id', '<>', $detailID->id);
                    })];
                $rules["slug"] = [
                    'required',
                    Rule::unique('employee_details')->where(function ($query) use ($detailID) {
                        $query->where('company_id', company()->id);
                        $query->where('id', '<>', $detailID->id);
                    })];

//                dd($rules,$detailID->toArray());
                return $rules; // и берем все остальные правила
            // case 'PATCH':
            case 'DELETE':
                return [

                ];
        }
        return $rules;
    }

    protected function getValidatorInstance()
    {

        $jsonEmployeeDepartments = $this->request->get('employee_departments');
        $this->merge(['employeeDepartmentsError' => json_decode($jsonEmployeeDepartments, true)]);

        $jsonHrLanguages = $this->request->get('hr_languages');
        $this->merge(['hrLanguages' => json_decode($jsonHrLanguages, true)]);

        $validator = parent::getValidatorInstance();

        $validator->sometimes('last_date', 'required', function ($input) {

            if ($input->statusEmpl) {
                $statusEmpl = EmployeeStatus::findOrFail($input->statusEmpl);

                if ($statusEmpl && $statusEmpl->name == 'Fired') {
                    return true;
                }
            }
            return false;
        });

        return $validator;
    }


    public function messages() : array
    {
        return [
            'lang_id.*.required' => 'The language field is required.',
            'level_id.*.required' => 'The level field is required.',
            'employeeDepartmentsError.*.department_id.distinct' => 'The Departments  field has a duplicate value.',
            'hrLanguages.*.lang_id.distinct' => 'The Language field has a duplicate value.',
        ];
    }
}
