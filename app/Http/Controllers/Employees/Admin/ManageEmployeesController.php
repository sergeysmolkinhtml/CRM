<?php

namespace App\Http\Controllers\Employees\Admin;

use App\DataTables\EmployeesDatatable;
use App\Http\Controllers\Controller;

class ManageEmployeesController extends Controller
{
    public function index(EmployeesDatatable $dataTable)
    {
        $result = $this->employeesServices->getIndex($this->data);
        $this->data += $result;

        // return view('admin.employees.index', $this->data);
        return $dataTable->render('admin.employees.index', $this->data);
    }
}
