<?php


namespace App\DataTransferObjects\HR;


use App\Config\DefaultConfig;
use App\Tools\Tools;
use Carbon\Carbon;

class HrDTO
{

    public static function getArray($data): array
    {
        $phoneArray = DefaultConfig::$phoneArray;

        $self = [];

        if (array_key_exists('created_by', $data))
            $self['created_by'] = $data['created_by'];

        if (array_key_exists('source_id', $data))
            $self['source_id'] = $data['source_id'];

        if (array_key_exists('hr_manager', $data))
            $self['hr_manager'] = $data['hr_manager'];

        if (array_key_exists('address_id', $data))
            $self['address_id'] = $data['address_id'];

        if (array_key_exists('courses', $data))
            $self['courses'] = $data['courses'];

        if (array_key_exists('video_url', $data) || array_key_exists('original_video', $data))
            $self['video_url'] = $data['video_url'] ?? $data['original_video'] ?? "";

        if (array_key_exists('age', $data))
            $self['age'] = $data['age'];

        if (array_key_exists('shift_id', $data))
            $self['shift_id'] = $data['shift_id'];

        if (array_key_exists('status_candidate_id', $data))
            $self['status_candidate_id'] = $data['status_candidate_id'];

        if (array_key_exists('expected_salary_id', $data))
            $self['expected_salary_id'] = $data['expected_salary_id'];

        if (array_key_exists('start_salary_id', $data))
            $self['start_salary_id'] = $data['start_salary_id'];

        if (array_key_exists('currency_id', $data))
            $self['currency_id'] = $data['currency_id'];

        if (array_key_exists('note', $data))
            $self['note'] = $data['note'];

        if (array_key_exists('note_admin', $data))
            $self['note_admin'] = $data['note_admin'];

        if (array_key_exists('note_team_lead', $data))
            $self['note_team_lead'] = $data['note_team_lead'];

        if (array_key_exists('status_admin_id', $data))
            $self['status_admin_id'] = $data['status_admin_id'];

        if (array_key_exists('email', $data))
            $self['email'] = $data['email'];

        if (array_key_exists('instagram', $data))
            $self['instagram'] = $data['instagram'];

        if (array_key_exists('telegram', $data))
            $self['telegram'] = $data['telegram'];

        if (array_key_exists('photo_url', $data))
            $self['photo_url'] = $data['photo_url'];

        if (array_key_exists('resume_url', $data))
            $self['resume_url'] = $data['resume_url'];

        if (array_key_exists('test_link', $data))
            $self['test_link'] = $data['test_link'];

        if (array_key_exists('portfolio_url', $data))
            $self['portfolio_url'] = $data['portfolio_url'];

        if (array_key_exists('excel_knowledge_id', $data))
            $self['excel_knowledge_id'] = $data['excel_knowledge_id'];

        if (array_key_exists('team_lead', $data))
            $self['team_lead'] = $data['team_lead'];

        if (array_key_exists('employee_details_id', $data))
            $self['employee_details_id'] = $data['employee_details_id'];

        if (array_key_exists('resume_editor', $data))
            $self['resume_editor'] = $data['resume_editor'];

        if (array_key_exists('department_id', $data))
            $self['department_id'] = $data['department_id'];

        if (array_key_exists('position_id', $data))
            $self['position_id'] = $data['position_id'];

        if (array_key_exists('empl_protege', $data))
            $self['empl_protege'] = $data['empl_protege'];

        if (array_key_exists('team_lead_id', $data))
            $self['team_lead_id'] = $data['team_lead_id'];

        if (array_key_exists('approved_status_team_lead', $data))
            $self['approved_status_team_lead'] = $data['approved_status_team_lead'];

        if (array_key_exists('start_date', $data))
            $self['start_date'] = $data['start_date'] ? Tools::checkDateForDataBase($data['start_date']) : null;

        if (array_key_exists("end_date", $data))
            $self['end_date'] = $data['end_date'] ? Tools::checkDateForDataBase($data['end_date']) : null;

        if (array_key_exists("start_time", $data))
            $self['start_time'] = $data['start_time'] ?
                Carbon::now()->format('Y-m-d') . ' ' . Tools::checkTimeForDataBase($data['start_time'])
                : null;

        if (array_key_exists("date_interview", $data))
            $self['date_interview'] = $data['date_interview'] ?
                Tools::checkDateForDataBase($data['date_interview'])
                : null;

        if (array_key_exists("time_interview", $data))
            $self['time_interview'] = $data['time_interview'] ?
                Tools::checkTimeForDataBase($data['time_interview'])
                : null;

        if (array_key_exists('viber', $data))
            $self['viber'] = $data['viber'];

        if (array_key_exists('phone', $data) || array_key_exists('mobile', $data))
            $self['phone'] = $data['phone'] ? str_replace($phoneArray, '', $data['phone'] ?? $data['mobile'] ?? null) : null;

        if (array_key_exists('name_candidate', $data) || array_key_exists('name', $data))
            $self['name_candidate'] = $data['name_candidate'] ?? $data['name'];

        if (array_key_exists('admin_name_id', $data))
            $self['admin_name_id'] = $data['admin_name_id'];

        if (array_key_exists('is_crm', $data))
            $self['is_crm'] = $data['is_crm'];

        if (array_key_exists('channel_communication', $data))
            $self['communication_id'] = $data['channel_communication'];

        if (array_key_exists('communication_id', $data))
            $self['communication_id'] = $data['communication_id'];

        if (array_key_exists('is_crm', $data))
            $self['is_crm'] = $data['is_crm'];

        if (array_key_exists('sub_titles', $data))
            $self['sub_titles'] = $data['sub_titles'];

        if (array_key_exists('presale_id', $data))
            $self['presale_id'] = $data['presale_id'];

        if (array_key_exists('gender', $data))
            $self['gender'] = $data['gender'];

        if (array_key_exists('job_site_link', $data))
            $self['job_site_link'] = $data['job_site_link'];

        if (array_key_exists('last_contact_date', $data))
            $self['last_contact_date'] = $data['last_contact_date'] ? Tools::checkDateForDataBase($data['last_contact_date']) : null;

        if (array_key_exists('follow_up_date', $data))
            $self['follow_up_date'] = $data['follow_up_date'] ? Tools::checkDateForDataBase($data['follow_up_date']) : null;


        return $self;
    }
}
