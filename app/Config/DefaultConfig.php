<?php

namespace App\Config;

class DefaultConfig
{
    public static string $nameProjectCompanyForEmployees = "Taseo";

    public static string $configStatusNameHrForChangeEmployees = 'Hired';
    public static string $configStatusNameClientCandidateHired = 'Hired';
    public static string $configStatusNameClientCandidateCandidate = 'Candidate';
    public static string $configStatusNameClientCandidateDemand = 'Demand';

    //Employees
    public static string $configStatusNameEmployeesFired = 'Fired';
    public static string $configStatusNameEmployeesHired = 'Hired';
    public static string $configStatusNameEmployeesLeft = 'Left';
    public static string $configStatusNameEmployeesDidntStart = "Didn't start";

    public static string $configStatusNameEmployeesProject = 'Project';
    public static string $configStatusNameEmployeesPartProjectPartTime = 'Part Project + Part Time';
    public static string $configStatusNameEmployeesPartProjectPartProject = 'Part Project + Part Project';
    public static string $configStatusNameEmployeesAvailable = 'Available';
    public static string $configStatusNameEmployeesProjectLG = 'Project+LG';
    public static string $configStatusNameEmployeesFullProjectPartTime = 'Full Project + Part Time';
    public static string $configStatusNameEmployeesPartProjectFullTime = 'Part Project + Full Time';
    public static string $configStatusNameEmployeesWork = 'Work';
    public static string $configStatusNameEmployeesPending = 'Pending';
    public static string $configStatusNameEmployeesCandidate = 'Candidate';

    //TASK

    //hide resume, youtube
    public static string $configStatusNameEmployeesFiredCreatedTaskHide = 'Fired';
    public static string $configStatusNameEmployeesLeftCreatedTaskHide = 'Left';
    public static string $configStatusNameEmployeesProjectCreatedTaskHide = 'Project';

    //created new employees
    public static string $configStatusNameEmployeesAvailableCreatedTaskNewEmployees = 'Available';
    public static string $configStatusNameEmployeesHiredCreatedTaskNewEmployees = 'Hired';

    //open resume
    public static string $configStatusNameEmployeesAvailableCreatedTaskOpenEmployees = 'Available';
    public static string $configStatusNameEmployeesProjectCreatedTasOpenEmployees = 'Project';
    public static string $configStatusNameEmployeesWorkCreatedTasOpenEmployees = 'Work';

    //attendance
    public static string $configCompanyNameDefaultInAttendance = 'TaSeo';
    public static string $configNameDirector = 'Niko Kar';

    //название статусов канидатов у клиента для отобажения в селекте для отмечания посещаемости часов
    public static string $configStatusNameForClientSelectedHired = 'Hired';
    public static string $configStatusNameForClientSelectedFired = 'Fired';
    public static string $configStatusNameForClientSelectedChanged = 'Changed';

    public static function getDefaultImg(): string
    {
        return asset('img/default-profile-3.png');
    }

    public static function getDefaultFolderImg(): \Illuminate\Foundation\Application|string|\Illuminate\Contracts\Routing\UrlGenerator|\Illuminate\Contracts\Foundation\Application
    {
        return asset_url('avatar/');
    }

    public static function getDefaultFolderUserDoc(): \Illuminate\Foundation\Application|string|\Illuminate\Contracts\Routing\UrlGenerator|\Illuminate\Contracts\Foundation\Application
    {
        return asset_url('employee-docs/');
    }
}
