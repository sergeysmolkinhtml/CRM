<?php

namespace App\DataTransferObjects\Employee;

use App\Tools\Tools;

/**
 *
 */
class EmployeeContentDTO
{
    /**
     * @param $data
     * @param $id
     * @return array
     */
    public static function getArray($data, $id = null): array
    {
        $self = [];

        $self['employee_details_id'] = $id ?? (array_key_exists("employee_details_id", $data) ? $data['employee_details_id'] : null);
//
//        $self['smm_manager_id'] = $id ?? (array_key_exists("smm_manager_id", $data) ? $data['smm_manager_id'] : null) ;
//
//        $self['copywriter_manager_id'] = $id ?? (array_key_exists("copywriter_manager_id", $data) ? $data['copywriter_manager_id'] : null) ;
//
//        $self['content_manager_id'] = $id ?? (array_key_exists("content_manager_id", $data) ? $data['content_manager_id'] : null) ;

        if (array_key_exists("smm_manager_id", $data))
            $self['smm_manager_id'] = $data['smm_manager_id'] ? $data['smm_manager_id'] : null;

        if (array_key_exists("smm_manager_created_by", $data))
            $self['smm_manager_created_by'] = $data['smm_manager_created_by'];

        if (array_key_exists("smm_start_date", $data))
            $self['smm_start_date'] = Tools::checkDateForDataBase($data['smm_start_date']);

        if (array_key_exists("smm_updated_date", $data))
            $self['smm_updated_date'] = Tools::checkDateForDataBase($data['smm_updated_date']);

        if (array_key_exists("copywriter_manager_id", $data))
            $self['copywriter_manager_id'] = $data['copywriter_manager_id'] ? $data['copywriter_manager_id'] : null;

        if (array_key_exists("copywriter_manager_created_by", $data))
            $self['copywriter_manager_created_by'] = $data['copywriter_manager_created_by'];

        if (array_key_exists("seo_title", $data))
            $self['seo_title'] = $data['seo_title'];

        if (array_key_exists("meta_description", $data))
            $self['meta_description'] = $data['meta_description'];

        if (array_key_exists("copywriter_start_date", $data))
            $self['copywriter_start_date'] = Tools::checkDateForDataBase($data['copywriter_start_date']);

        if (array_key_exists("copywriter_updated_date", $data))
            $self['copywriter_updated_date'] = Tools::checkDateForDataBase($data['copywriter_updated_date']);

        if (array_key_exists("content_manager_id", $data))
            $self['content_manager_id'] = $data['content_manager_id'] ? $data['content_manager_id'] : null;

        if (array_key_exists("content_manager_created_by", $data))
            $self['content_manager_created_by'] = $data['content_manager_created_by'];

        if (array_key_exists("wp_status", $data) && ($data["wp_status"] == 'published' || $data["wp_status"] == 'notPublished'))
            $self['wp_status'] = $data['wp_status'];

        if (array_key_exists("cv_heading", $data))
            $self['cv_heading'] = $data['cv_heading'];

        if (array_key_exists("content_start_date", $data))
            $self['content_start_date'] = Tools::checkDateForDataBase($data['content_start_date']);

        if (array_key_exists("content_updated_date", $data))
            $self['content_updated_date'] = Tools::checkDateForDataBase($data['content_updated_date']);

        if (array_key_exists("project_company_id", $data))
            $self['project_company_id'] = $data['project_company_id'];

        if (array_key_exists("url_site", $data))
            $self['url_site'] = $data['url_site'];

//        dd($self, $data);

        return $self;
    }

}
