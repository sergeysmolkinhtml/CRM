<?php

namespace App\Http\Controllers\Employees\Admin;

use App\DataTables\EmployeesDatatable;
use App\Http\Controllers\Controller;

class ManageEmployeesController extends Controller
{
    private $searchArray = [' ', '-', '(', ')', '+'];
    private EmployeesServices $employeesServices;

    /**
     * UserBaseController constructor.
     */
    public function __construct(
        EmployeesServices $employeesServices
    )
    {
        parent::__construct();
        $this->pageTitle = 'app.menu.employees';
        $this->pageIcon = 'icon-user';
        $this->middleware(function ($request, $next) {
            if (!in_array('employees', $this->user->modules))
                abort(403);

            return $next($request);
        });

        $this->employeesServices = $employeesServices;
    }

    public function index(EmployeesDatatable $dataTable)
    {
        $result = $this->employeesServices->getIndex($this->data);
        $this->data += $result;

        // return view('admin.employees.index', $this->data);
        return $dataTable->render('admin.employees.index', $this->data);
    }
}
