<?php

namespace App\Tools;

use Carbon\Carbon;
use Carbon\CarbonPeriod;
use DateTime;
use Illuminate\Support\Str;
use stdClass;

class Tools
{
    public static array $searchArray = [' ', '-', '(', ')', '+'];
    public static array $arrayMonth = [1 => 'Январь', 2 => 'Февраль', 3 => 'Март', 4 => 'Апрель', 5 => 'Май', 6 => 'Июнь', 7 => 'Июль', 8 => 'Август', 9 => 'Сентябрь', 10 => 'Октябрь', 11 => 'Ноябрь', 12 => 'Декабрь'];
    protected static array $roleHrManager = [];
    protected static array $roleLgManager = [];
    protected static array $roleTeamLead = []; // Array phone rules
    protected static array $roleSalesManager = [];

    public static function getRoleRecruiterManager(): array
    {
        $str = "Recruiter Team Lead";
        return [
            "Recruiter Manager",
            $str,
            "Lead and Recruiter Manager",
        ];
    }

    public static function getRoleLgManager(): array
    {
        return [
            "Team Lead Leads",
            "Lead generator",
            "Lead and Hr Manager",
            "Lead Generation Manager All Lead",
            "Sales Manager",
            "Account Manager",
            "Account Manager + Time Tracking",
            "LG Part",
            "Hr Manager",
        ];
    }

    public static function getRoleTeamLead(): array
    {
        return [
            __('app.roleEmployee.leadGeneratorTeamLeads'),
            "HR Manager Team Lead",
            __('app.roleEmployee.teamLead'),
            __('app.roleEmployee.salesManagerTeamLead'),
            __('Admin'),
        ];
    }

    public static function getRoleSalesManager(): array
    {
        return [
            __('app.roleEmployee.salesManager'),
            __('app.roleEmployee.salesManagerTeamLead'),
        ];
    }


    public static function getRoleAccountManager()
    {
        return [
            __('app.roleEmployee.accountManager'),
            "Account Manager + Time Tracking",
        ];
    }

    public static function getRoleVideoEditor() : array
    {
        return [
            __('app.roleEmployee.videoEditor')
        ];
    }

    public static function getRoleSmmManager()
    {
        return [
            __('app.roleEmployee.smmManager')
        ];
    }

    public static function getRoleArtist()
    {
        return [
            __('app.roleEmployee.artist')
        ];
    }

    public static function getRoleContentManager()
    {
        return [
            __('app.roleEmployee.contentManager')
        ];
    }

    public static function getRoleCopywriter()
    {
        return [
            __('app.roleEmployee.copywriter')
        ];
    }

    public static function checkDateForDataBase($date)
    {
        if (is_string($date)) {

            return Carbon::hasFormat($date, 'Y-m-d') ? $date : Carbon::createFromFormat(company_setting()->date_format, $date)->format('Y-m-d');
        } else
            return $date;
    }

    public static function formatDate($date)
    {
        return Carbon::createFromFormat(company_setting()->date_format, $date);
    }

    public static function checkTimeForDataBase($time)
    {
        return Carbon::hasFormat($time, 'H:i:s') ? $time : Carbon::createFromFormat(company_setting()->time_format, $time)->format('H:i:s');
    }

    public static function checkDateTimeForDataBase($datetime)
    {
        return Carbon::hasFormat($datetime, 'Y-m-d H:i:s') ? $datetime : Carbon::createFromFormat(company_setting()->date_format . ' ' . company_setting()->time_format, $datetime)->format('Y-m-d H:i:s');
    }

    public static function checkEmptuValueInKey($data, $exception = []) : bool
    {
        $result = true;
        foreach ($data as $k => $v) {
            if (in_array($k, $exception)) continue;
            if ($v !== '' && $v !== 0 && $v !== null) return false;

        }
        return $result;
    }

    public static function getPeriodByDate($startDate, $endDate)
    {

        $start = (new DateTime($startDate))->modify('first day of this month');
        $end = (new DateTime($endDate))->modify('first day of this month');

        $period = CarbonPeriod::create($start, $end)->settings([
            'monthOverflow' => false,
        ])->month();

        return collect($period)->map(function (Carbon $date) {
            return [
                'days' => $date->daysInMonth,
                'name' => $date->monthName,
                'month' => $date->month,
                'year' => $date->year,
                'date' => $date->format("Y-m-d"),
                'lastDate' => $date->lastOfMonth(),
            ];
        });
    }

    public static function arrayCheck($arrayData)
    {
        return ($arrayData && is_array($arrayData) && count($arrayData) > 0);
    }

    public static function stringCheck($arrayData = '')
    {
        return ($arrayData && $arrayData !== 'all' && $arrayData != '' && $arrayData != null);
    }

    public static function dateCheck($arrayData = '')
    {
        return ($arrayData != null && $arrayData != '' && $arrayData !== 'null');
    }

    public static function prepareArrayForRawCondition(array $array)
    {
        $result = [];

        if (array_keys($array) !== range(0, count($array) - 1)) {
            foreach ($array as $key => $item) {
                $prepare = implode("\", \"", $item);
                $result[$key] = "(\"$prepare\")";
            }
        }

        return $result;
    }

    public static function getItemsCalculating(string $itemKey, stdClass $dbResult, array $except = []): array
    {
        $itemKey = Str::studly($itemKey);

        $availableKeys = ['sum', 'min', 'max', 'avg'];

        $neededKeys = array_diff($availableKeys, $except);

        $output = [];

        foreach ($neededKeys as $key) {
            $output[$key] = $dbResult->{$key . $itemKey} ?? 0;
        }

        return $output;
    }

    public static function getOffsetTimezone()
    {
        return Carbon::now()->timezone(company_setting()->timezone)->getOffsetString();
    }
}
