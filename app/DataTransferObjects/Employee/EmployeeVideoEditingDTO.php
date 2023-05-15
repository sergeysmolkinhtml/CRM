<?php


namespace App\DataTransferObjects\Employee;

use App\Tools\Tools;

/**
 *
 */
    class EmployeeVideoEditingDTO
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

        if (array_key_exists("start_date", $data))
            $self['start_date'] = Tools::checkDateForDataBase($data['start_date']);

        if (array_key_exists("due_date", $data))
            $self['due_date'] = Tools::checkDateForDataBase($data['due_date']);

        if (array_key_exists("video_editor_id", $data))
            $self['video_editor_id'] = $data['video_editor_id'] ? $data['video_editor_id'] : null;

//        dd($self['video_editor_id'], $data['video_editor_id']?$data['video_editor_id']:"iii");
        if (array_key_exists("approved", $data))
            $self['approved'] = $data['approved'];


        if (array_key_exists("url_video_to_drive_disk", $data))
            $self['url_video_to_drive_disk'] = $data['url_video_to_drive_disk'];

        if (array_key_exists("url_video_to_youtube", $data))
            $self['url_video_to_youtube'] = $data['url_video_to_youtube'];

        if (array_key_exists("sub_titles", $data))
            $self['sub_titles'] = $data['sub_titles'];

        if (array_key_exists("youtube_description", $data))
            $self['youtube_description'] = $data['youtube_description'];

        if (array_key_exists("youtube_hashtags", $data))
            $self['youtube_hashtags'] = $data['youtube_hashtags'];

        if (array_key_exists("project_company_id", $data))
            $self['project_company_id'] = $data['project_company_id'];

        return $self;
    }

    /**
     * @param $data
     * @return array
     */
    public static function getArrayForTask($data): array
    {
        $self = [];

        if (array_key_exists("url_video_to_drive_disk", $data))
            $self['url_video_to_drive_disk'] = $data['url_video_to_drive_disk'];

        if (array_key_exists("url_video_to_youtube", $data))
            $self['url_video_to_youtube'] = $data['url_video_to_youtube'];

        if (array_key_exists("sub_titles", $data))
            $self['sub_titles'] = $data['sub_titles'];

        return $self;
    }
}
