<?php

namespace App\Datatables;

use App\Models\EmployeeStatus;
use App\Position;
use App\Models\Role;
use App\Team;
use App\Tools\Tools;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTableAbstract;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use function foo\func;

class EmployeesDatatable extends BaseDatatable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return DataTableAbstract
     */
    public function dataTable($query): DataTableAbstract
    {
        $roles = Role::where('name', '<>', 'client')->orderBy("name")->get();
        return datatables()
            ->eloquent($query)
            ->addColumn('role', function ($row) use ($roles) {
                $is_admin = $row->hasRole('admin');
                if ($row->id != user()->id) {
                    $status = '<div class="btn-group dropdown" onClick="getPos(event)">';
                    $status .= '<button aria-expanded="true" data-toggle="dropdown" class="btn ' . (($is_admin) ? 'btn-danger' : 'btn-info')
                        . ' dropdown-toggle waves-effect waves-light btn-xs" type="button">'
                        . ((!$is_admin) ? $row->current_role . $row->current_role_name : __('app.' .'admin'))
                        . ' <span class="caret"></span></button>';
                    $status .= '<ul role="menu" class="dropdown-menu pull-right">';
                    foreach ($roles as $role) {
                        if ($role->id <= 3) {
                            $status .= '<li><a href="javascript:;" data-user-id="' . $row->id . '" class="assign_role" data-role-id="' . $role->id . '">' . __('app.' . $role->name) . '</a></li>';
                        } else {
                            $status .= '<li><a href="javascript:;" data-user-id="' . $row->id . '" class="assign_role" data-role-id="' . $role->id . '">' . ucwords($role->name) . '</a></li>';
                        }
                    }
                    $status .= '</ul>';
                    $status .= '</div>';
                    return $status;
                } else {
                    return __('messages.roleCannotChange');
                }

//                dd($row->roleName == "admin");

                $status = '';
//                if ($is_admin){
//                    $status = '<option>Admin</option>';
//                } else {
//                    $status = '<option  >' . $row->current_role_name  . ' </option>';
//                }

//                $status = '<option value="' . $row->roleId . '">' .  $is_admin ?  'Admin' : $row->current_role_name . '</option>';
//                $status = '<option>--</option>';

//                if ($row->id != user()->id) {
//                    foreach ($roles as $role) {
////                       dd($row->roleName);
//
//                        if (($row->roleId == $role->id)) {
//                            $selected = 'selected';
//                        } else {
//                            $selected = '';
//                        }
////                        dd($row->toArray(), $role->toArray() , $status);
//
////                        if ($is_admin){
////                            $status .= '<option value="' . $role->id . '">' . $role->name . '</option>';
////                        }
//
//                        $status .= '<option ' . $selected . ' value="' . $role->id . '">' . $role->name . ''. $row->current_role_name. '</option>';
//                    }
//                    return '<div style="min-width: 150px">
//                              <select class="select2 form-control statusChange">
//                                  ' . $status . '
//                              </select>
//                            </div>';
//                } else {
//                    return __('messages.roleCannotChange');
//                }

            })
            ->addColumn('employeeRole', function ($row) use ($roles) {
                return (($row->roleId != 1) ? $row->current_role_name : __('app.' . $row->roleName));

            })
            ->addColumn('action', function ($row) {

                $action = '<div class="btn-group dropdown m-r-10" onClick="getPos(event)">
                <button aria-expanded="false" data-toggle="dropdown" class="btn dropdown-toggle waves-effect waves-light" type="button"><i class="ti-more"></i></button>
                    <ul role="menu" class="dropdown-menu pull-right">
                    <li><a href="' . route('admin.employees.edit', [$row->id]) . '"><i class="fa fa-pencil" aria-hidden="true"></i> ' . trans('app.edit') . '</a></li>
                  <li><a href="' . route('admin.employees.show', [$row->id]) . '"><i class="fa fa-search" aria-hidden="true"></i> ' . __('app.view') . '</a></li>';
                if ($this->user->id !== $row->id) {
                    $action .= '<li><a href="javascript:;"  data-user-id="' . $row->id . '"  class="sa-params"><i class="fa fa-times" aria-hidden="true"></i> ' . trans('app.delete') . '</a></li>';
                }
                $action .= '</ul> </div>';

                return $action;
            })
            ->editColumn(
                'created_at',
                function ($row) {
                    return Carbon::parse($row->created_at)->format($this->global->date_format);
                }
            )
            ->addColumn('statusEmployeeName', function ($row) {
                $status = EmployeeStatus::orderBy('name')->get();

                $disabled = '';
                if (!empty($row->statusEmployeeName) && isset($row->statusEmployeeName)) {
                    $disabled = 'disabled';
                }

                $statusLi = '<option ' . $disabled . ' >--</option>';
                foreach ($status as $st) {
                    if ($row->statusEmployee == $st->id) {
                        $selected = 'selected';
                    } else {
                        $selected = '';
                    }
                    $statusLi .= '<option ' . $selected . ' value="' . $st->id . '">' . $st->name . '</option>';
                }

                return '<div style="min-width: 150px">
                          <select class="select2 form-control statusChange" name="statusChange" onchange="changeStatus( ' .   $row->id . ', this.value,  this.options[this.selectedIndex].text)">
                              ' . $statusLi . '
                          </select>
                        </div>';
            })
            ->addColumn('department_and_positions', function ($row) {
                $primary_department = Team::where('team_name', $row->department)->first()->id ?? 0;
                $departments = Team::getAllShort()->get();
                $positions = Position::getAllShort()->get();
                $employee_department_id = $row->id;
                $primary_position = Position::where('name', $row->employees_positions)->first()->id ?? 0;

                return "<div class='react-department-and-positions' style='min-width: 300px' data-selected-department='$primary_department'
                            data-selected-positions='$primary_position' data-ids='department_id-$employee_department_id, position-$employee_department_id' data-names='department_id, position' data-cols='col-sm-12,col-sm-12'
                            data-departments='$departments' data-positions='$positions' data-id='$employee_department_id'
                            data-bind='1'>
                        </div>
                        ";
            })

            ->editColumn('name', function ($row) use ($roles) {

                $image = '<img src="' . $row->image_url . '"alt="user" class="img-circle" width="100" height="30"> ';

                $designation = ($row->designation_name) ? ucwords($row->designation_name) : ' ';

                return '<div class="row truncate"><div class="col-sm-3 col-xs-4">' . $image . '</div>
                    <div class="col-sm-9 col-xs-8 empl-name-box"><a class="empl-name" target="_blank" href="' .
                    route('admin.employees.show', $row->id) . '">' . ucwords($row->name) .
                    '<br>(' . ucwords($row->nameEng) . ')' . '</a><br><span class="text-muted font-12">' .
                    $designation . '</span></div></div>';
            })
            ->addIndexColumn()
            ->rawColumns(['name', 'action', 'employee_viber', 'role', 'department_and_positions','statusEmployeeName'])
            ->removeColumn('roleId')
            ->removeColumn('roleName')
            ->removeColumn('current_role');
    }

    /**
     * Get query source of dataTable.
     *
     * @param User $model
     * @return Builder
     */
    public function query(User $model)
    {
        $request = $this->request();
        if ($request->role != 'all' && $request->role != '') {
            $userRoles = Role::findOrFail($request->role);
        }

        $users = User::with('role')
            ->withoutGlobalScope('active')
            ->join('role_user', 'role_user.user_id', '=', 'users.id')
            ->leftJoin('employee_details', 'employee_details.user_id', '=', 'users.id')
            ->leftJoin('designations', 'employee_details.designation_id', '=', 'designations.id')
            ->leftJoin('employees_statuses', 'employee_details.status_id', '=', 'employees_statuses.id')
            ->join('roles', 'roles.id', '=', 'role_user.role_id')
            ->leftJoin('teams', 'teams.id', '=','employee_details.department_id')
            ->leftJoin('positions', 'employee_details.position_id', '=', 'positions.id')
            ->leftJoin('employee_work_experiences', 'employee_work_experiences.user_id', '=', 'users.id')
            ->leftJoin('work_experience_tags', 'work_experience_tags.work_experience_id',  '=', 'employee_work_experiences.id')
            ->leftJoin('education_experience_tags', 'work_experience_tags.tag_id',  '=', 'education_experience_tags.id')
            ->leftJoin('employee_education', 'employee_education.user_id',  '=', 'users.id')
            ->leftJoin('education_tags', 'education_tags.education_id',  '=', 'employee_education.id')
            ->select(
                'users.id',
                'users.name',
                'users.mobile',
                'users.email',
                'users.created_at',
                'roles.name as roleName',
                'roles.id as roleId',
                'users.image',
                'users.status',

                \DB::raw("(select roles.name from roles where roles.id =
            (select user_roles.role_id from role_user as user_roles
                    where user_roles.user_id = users.id ORDER BY user_roles.role_id DESC limit 1)
            limit 1) as `current_role_name`"),

                'positions.name AS employees_positions',

                'designations.name as designation_name',

                'employee_details.name_eng as  nameEng',
                'employee_details.status_id as  statusEmployee',
                'employee_details.id as  employeeId',
                'employee_details.employee_id as  employee_id',
                'employee_details.joining_date as start_date',
                'employee_details.viber as employee_viber',

                'employees_statuses.name as statusEmployeeName',

                'teams.team_name as department'
            )
            ->where('roles.name', '<>', 'client');


        if ($request->startDate !== null && $request->startDate != 'null' && $request->startDate != '') {
            $startDate = Carbon::createFromFormat($this->global->date_format, $request->startDate)->toDateString();
            $users = $users->where(DB::raw('DATE(employee_details.joining_date)'), '>=', $startDate);
        }

        if ($request->endDate !== null && $request->endDate != 'null' && $request->endDate != '') {
            $endDate = Carbon::createFromFormat($this->global->date_format, $request->endDate)->toDateString();
            $users = $users->where(DB::raw('DATE(employee_details.joining_date)'), '<=', $endDate);
        }

        if ($request->status != 'all' && $request->status != '') {
            $users = $users->where('users.status', $request->status);
        }

        if ($request->employee != 'all' && $request->employee != '') {
            $users = $users->where('users.id', $request->employee);
        }

        if ($request->designation_id != 'all' && $request->designation_id != '') {
            $users = $users->whereIn('employee_details.designation_id', $request->designation_id);
        }

        if ($request->statusEmp != 'all' && $request->statusEmp != '' && count($request->statusEmp) > 0) {
            $users = $users->whereIn('employee_details.status_id', $request->statusEmp);
        }

        if ($request->exptypes && Tools::arrayCheck($request->tags))
        {
            if ($request->exptypes == 'education')
                $users = $users->whereIn('education_tags.tag_id', $request->tags);
            elseif ($request->exptypes == 'experience')
                $users = $users->whereIn('work_experience_tags.tag_id', $request->tags);
            elseif ($request->exptypes == 'all')
                $users = $users->where(function ($q) use ($request) {
                    $q->whereIn('education_tags.tag_id', $request->tags);
                    $q->orWhereIn('work_experience_tags.tag_id', $request->tags);
                });
        }

        if ($request->role != 'all' && $request->role != ''  && count($request->role) > 0) {
            $users = $users->whereIn('roles.id', $request->role);
            if (in_array('employee', $userRoles->pluck("name")->toArray())) {


                $idRoleEmployee = $userRoles->where("name", "employee")->first()->id;
                $request->role = array_flip($request->role); //Меняем местами ключи и значения
                unset ($request->role[$idRoleEmployee]) ; //Удаляем элемент массива
                $request->role = array_flip($request->role);

                $users = $users->where(function ($q) use ($request) {
                    $q->whereIn('roles.id', $request->role)
                        ->orWhere(\DB::raw("(select roles.name from roles where roles.id =
            (select user_roles.role_id from role_user as user_roles
                    where user_roles.user_id = users.id ORDER BY user_roles.role_id DESC limit 1)
            limit 1)"), "employee")
                        ->having('roleName', '<>', 'admin');
                });
            }

        }

        if ((is_array($request->skill) && $request->skill[0] != 'all') && $request->skill != '' && $request->skill != null && $request->skill != 'null') {
            $users = $users->join('employee_skills', 'employee_skills.user_id', '=', 'users.id')
                ->whereIn('employee_skills.skill_id', $request->skill);
        }

        if($request->type && (Tools::arrayCheck($request->positions) || Tools::arrayCheck($request->department))) {
            $users = $users->leftJoin('employee_teams', 'employee_teams.user_id', '=', 'users.id');
        }

        if($request->type && Tools::arrayCheck($request->department)) {
            if ($request->type == "primary")
                $users = $users->whereIn('employee_details.department_id', $request->department);
            else if ($request->type == "secondary")
                $users = $users->whereIn('employee_teams.team_id', $request->department);
            else if ($request->type == "all")
                $users = $users->where(function ($q) use ($request) {
                    $q->whereIn('employee_details.department_id', $request->department);
                    $q->orWhereIn('employee_teams.team_id', $request->department);
                });
        }

        if($request->type && Tools::arrayCheck($request->positions)) {
            if($request->type == "secondary" || $request->type == "all"){
                $users = $users->leftJoin("employee_team_positions", "employee_teams.id", "employee_team_positions.employee_team_id");
            }
            if ($request->type == "primary")
                $users = $users->whereIn('employee_details.position_id', $request->positions);
            else if ($request->type == "secondary")
                $users = $users
                    ->whereIn('employee_team_positions.position_id', $request->positions);
            else if ($request->type == "all")
                $users = $users->where(
                    function ($q) use ($request) {
                        $q->whereIn('employee_details.position_id', $request->positions);
                        $q->orWhereIn('employee_team_positions.position_id', $request->positions);
                    }
                );
        }

        return $users->groupBy('users.id');
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->setTableId('employees-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->scrollX(true)
            ->dom("<'row'<'col-md-6'l><'col-md-6'Bf>><'row'<'col-sm-12'tr>><'row'<'col-sm-5'i><'col-sm-7'p>>")
            ->destroy(true)
            ->orderBy(0)
            // ->responsive(true)
            ->serverSide(true)
            ->stateSave(true)
            ->processing(true)
            ->language(__("app.datatable"))
            ->parameters([
                'initComplete' => 'function () {
                   window.LaravelDataTables["employees-table"].buttons().container()
                    .appendTo( ".bg-title .text-right")
                }',
                'fnDrawCallback' => 'function( oSettings ) {
                    $("body").tooltip({
                        selector: \'[data-toggle="tooltip"]\'
                    })
                }',
                "aoColumnDefs" => [
                    ["sClass" => "hidden-column", "aTargets" => [3]],
                ],
            ])
            ->buttons(
//                Button::make(['extend' => 'export', 'buttons' => ['excel', 'csv'], 'text' => '<i class="fa fa-download"></i> ' . trans('app.exportExcel') . '&nbsp;<span class="caret"></span>'])
                Button::make()
            );
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return [
            __('app.id') => ['data' => 'id', 'name' => 'id', 'visible' => false, 'exportable' => false],
            '#' => ['data' => 'DT_RowIndex', 'orderable' => false, 'searchable' => false],
            __('app.name') => ['data' => 'name', 'name' => 'name'],
            'nameEng' => ['data' => 'nameEng', 'name' => 'employee_details.name_eng', 'width' => '20%', 'orderable' => false, 'exportable' => false, 'visible' => false],
            'emplID' => ['data' => 'employee_id', 'name' => 'employee_details.employee_id', 'width' => '10%'],
            __('app.email') => ['data' => 'email', 'name' => 'email', 'width' => '10%'],
            __('viber') => ['data' => 'employee_viber', 'name' => 'employee_details.viber', 'width' => '10%'],
            __('app.role') => ['data' => 'role', 'name' => 'role', 'width' => '10%', 'exportable' => false],
            __('app.roleAssigned') => ['data' => 'employeeRole', 'name' => 'employeeRole', 'width' => '20%', 'visible' => false],
            __('app.statusEmployees') => ['data' => 'statusEmployeeName', 'name' => 'employees_statuses.name', 'width' => '10%'],
            __('app.departmentAndPosition') => ['data' => 'department_and_positions', 'name' => 'teams.team_name', 'width' => '20%'],
            Column::computed('action', __('app.action'))
                ->exportable(false)
                ->printable(false)
                ->orderable(false)
                ->searchable(false)
                ->width(150)
                ->addClass('text-center')
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename() : string
    {
        return 'employees_' . date('YmdHis');
    }

    public function pdf()
    {
        set_time_limit(0);
        if ('snappy' == config('datatables-buttons.pdf_generator', 'snappy')) {
            return $this->snappyPdf();
        }

        $pdf = app('dompdf.wrapper');
        $pdf->loadView('datatables::print', ['data' => $this->getDataForPrint()]);

        return $pdf->download($this->getFilename() . '.pdf');
    }
}

