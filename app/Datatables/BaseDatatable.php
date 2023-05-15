<?php

namespace App\Datatables;


use Yajra\DataTables\Services\DataTable;

class BaseDatatable extends DataTable
{
    protected $global;

    public function __construct()
    {
        parent::__construct();
        $this->user = user();
        $this->global = $this->company = company_setting();
    }
    public function convertSnake($str)
    {
        $n = strlen($str);

        for ($i = 0; $i < $n; $i++)
        {
            // Converting space
            // to underscore
            if ($str[$i] == ' ')
                $str[$i] = '_';
            else
                // If not space, convert
                // into lower character
                $str[$i] = strtolower($str[$i]);
        }
        return $str;
    }

    public function processTitle($data)
    {
        $newArray = [];
        foreach($data as $key => $title) {
            if(is_array($title) && $key != '#' && $key != '0') {
                $newArray[$this->convertSnake($key)] = $data[$key];
            } else {
                $newArray[$key] = $data[$key];
            }
        }
        return $newArray;
    }
}
