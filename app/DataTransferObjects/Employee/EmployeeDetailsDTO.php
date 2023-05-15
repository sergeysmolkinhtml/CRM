<?php

namespace App\DataTransferObjects\Employee;

use App\Tools\Tools;

class EmployeeDetailsDTO
{
    public static function getArray($data): array
    {

        $self = [];

//        $self["status_id"] = EmployeeStatus::getStatusIdByName();

        if (array_key_exists('user_id', $data))
            $self['user_id'] = $data['user_id'];
        if (array_key_exists('employee_id', $data))
            $self['employee_id'] = $data['employee_id'];
        if (array_key_exists('nameEn', $data))
            $self['name_eng'] = $data['nameEn'];
        if (array_key_exists('h_email', $data))
            $self['work_hemail'] = $data['h_email'];
        if (array_key_exists('mobile_work_email', $data))
            $self['mobile_work_email'] = $data['mobile_work_email'];
        if (array_key_exists('work_email', $data))
            $self['work_email'] = $data['work_email'];

        if (array_key_exists('statusEmpl', $data))
            $self['status_id'] = $data['statusEmpl'];

        if (array_key_exists('last_date', $data))
            $self['last_date'] = $data['last_date'] ? Tools::checkDateForDataBase($data['last_date']) : null;;

        if (array_key_exists('is_student', $data))
            $self['is_student'] = $data['is_student'];

        if (array_key_exists('viber', $data))
            $self['viber'] = $data['viber'];
        if (array_key_exists('facebook', $data))
            $self['facebook'] = $data['facebook'];
        if (array_key_exists('linkedin', $data))
            $self['linkedin'] = $data['linkedin'];
        if (array_key_exists('telegram', $data))
            $self['telegram'] = $data['telegram'];
        if (array_key_exists('skype', $data))
            $self['skype'] = $data['skype'];
        if (array_key_exists('whatsapp', $data))
            $self['whatsapp'] = $data['whatsapp'];
        if (array_key_exists('discord', $data))
            $self['discord_user_id'] = $data['discord'];
        if (array_key_exists('resume', $data))
            $self['resume_url'] = $data['resume'];
        if (array_key_exists('photo_url', $data))
            $self['photo_url'] = $data['photo_url'];
//        if (array_key_exists('link_rhs', $data))
//            $self['url_site_rhs'] = $data['link_rhs'];
        if (array_key_exists('url_contract', $data))
            $self['url_contract'] = $data['url_contract'];
        if (array_key_exists('department', $data) || array_key_exists('department_id', $data))
            $self['department_id'] = $data['department'] ?? $data['department_id'];
        if (array_key_exists('position_id', $data))
            $self['position_id'] = $data['position_id'];
        if (array_key_exists('hourly_date', $data))
            $self['hourly_date'] = $data['hourly_date'];
        if (array_key_exists('office', $data))
            $self['office_id'] = $data['office'];
        if (array_key_exists('employee_id', $data))
            $self['slack_username'] = $data['employee_id'];
        if (array_key_exists('designation_id', $data))
            $self['designation_id'] = $data['designation_id'];
        if (array_key_exists('team_lead', $data))
            $self['team_lead_id'] = $data['team_lead'];
        if (array_key_exists('approved', $data))
            $self['approved'] = $data['approved'];
        if (array_key_exists('note_teamLead', $data))
            $self['note_team_lead'] = $data['note_teamLead'];
        if (array_key_exists('city', $data))
            $self['city_id'] = $data['city'];
        if (array_key_exists('address_id', $data))
            $self['address_id'] = $data['address_id'];
        if (array_key_exists('address', $data))
            $self['address'] = $data['address'];
        if (array_key_exists('hourly_rate', $data))
            $self['hourly_rate'] = $data['hourly_rate'];
        if (array_key_exists('is_approved_manager', $data))
            $self['is_approved_manager'] = $data['is_approved_manager'];
        if (array_key_exists('joining_date', $data))
            $self['joining_date'] = $data['joining_date'] ? Tools::checkDateForDataBase($data['joining_date']) : null;
        if (array_key_exists('birthday_date', $data))
            $self['birthday'] = $data['birthday_date'] ? Tools::checkDateForDataBase($data['birthday_date']) : null;

        if (array_key_exists('full_price', $data))
            $self['full_price'] = $data['full_price'];
        if (array_key_exists('full_currency_id', $data))
            $self['full_currency_id'] = $data['full_currency_id'];

        if (array_key_exists('part_price', $data))
            $self['part_price'] = $data['part_price'];
        if (array_key_exists('part_currency_id', $data))
            $self['part_currency_id'] = $data['part_currency_id'];

        if (array_key_exists('minimum_price', $data))
            $self['minimum_price'] = $data['minimum_price'];
        if (array_key_exists('minimum_currency_id', $data))
            $self['minimum_currency_id'] = $data['minimum_currency_id'];

        if (array_key_exists('short_name', $data))
            $self['short_name'] = $data['short_name'];

        if (array_key_exists('reason_dismissal', $data))
            $self['reason_dismissal'] = $data['reason_dismissal'];

        if (array_key_exists('original_video', $data))
            $self['original_video'] = $data['original_video'];

        if (array_key_exists('slug', $data))
            $self['slug'] = $data['slug'];

        if (array_key_exists('name_ua', $data))
            $self['name_ua'] = $data['name_ua'];

        return $self;
    }


}
