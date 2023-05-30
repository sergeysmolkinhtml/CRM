<?php


namespace App\Http\Requests\Admin\Employee;

use App\Models\Employee\EmployeeDetails;
use App\Tools\Tools;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class StatusUpdateRequest extends FormRequest
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
        return [
            "last_date" => ["required", function ($key, $value, $fail) {
                $empDetail = EmployeeDetails::select('joining_date')->where('user_id', $this->id)->first();

                if ($empDetail) {
                    if (Carbon::parse($empDetail->joining_date)->gte($value)) {
                        $fail(__('validation.after_or_equal_db', [
                            'date' => 'joining date',
                            'value' => $empDetail->joining_date->format(company_setting()->date_format)
                        ]));
                    }
                }
            }],
            "statusEmpl" => "required",
            "reason_dismissal" => "required"
        ];
    }
}
