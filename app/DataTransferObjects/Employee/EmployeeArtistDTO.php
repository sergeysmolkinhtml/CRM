<?php

namespace App\DataTransferObjects\Employee;

use App\Tools\Tools;

/**
 *
 */
class EmployeeArtistDTO
{
    /**
     * @param $data
     * @param $id
     * @return array
     */
    public static function getArray($data, $id = null): array
    {
        $self = [];

        $self['employee_details_id'] = $id ?? (array_key_exists("employee_details_id", $data) ? $data['employee_details_id'] : null) ;

        if(array_key_exists("url_portrait", $data))
            $self['url_portrait'] = $data['url_portrait'];

        if(array_key_exists("promo_pic", $data))
            $self['promo_pic'] = $data['promo_pic'];

        if(array_key_exists("artist_id", $data))
            $self['artist_id'] = $data['artist_id'] ? $data['artist_id'] : null;

        if(array_key_exists("artist_created_by", $data))
            $self['artist_created_by'] = $data['artist_created_by'];

        if(array_key_exists("artist_start_date", $data))
            $self['artist_start_date'] = Tools::checkDateForDataBase($data['artist_start_date']);

        if(array_key_exists("artist_updated_date", $data))
            $self['artist_updated_date'] = Tools::checkDateForDataBase($data['artist_updated_date']);

        if (array_key_exists("project_company_id", $data))
            $self['project_company_id'] = $data['project_company_id'];
        return $self;
    }

}
