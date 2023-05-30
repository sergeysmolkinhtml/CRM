<?php

namespace App\Http\Controllers\Employees\Admin;

use App\DataTables\EmployeesDatatable;
use App\Helpers\Reply;
use App\Http\Controllers\Admin\AdminBaseController;
use App\Http\Requests\Admin\Employee\EmployeeRequest;
use App\Http\Requests\Admin\Employee\LanguageRequest;
use App\Http\Requests\Admin\Employee\SkillRequest;
use App\Http\Requests\Admin\Employee\StatusUpdateRequest;
use App\Http\Requests\EmployeeTaskBoardRequest;
use App\Models\ProjectMember;
use App\Models\Role;
use App\Services\Employees\EmployeesServices;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class ManageEmployeesController extends AdminBaseController
{
    private array $searchArray = [' ', '-', '(', ')', '+'];
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
    public function testIndex()
    {
        $result = $this->employeesServices->getIndex($this->data);
        $this->data += $result;

        return view('admin.employees.test-index', $this->data);
    }

    public function getData(Request $request) : JsonResponse
    {
        $result = $this->employeesServices->getData($request);

        return Reply::dataOnly($result);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View
     */
    public function create($candidateID = null)
    {
        $result = $this->employeesServices->getCreate($candidateID, $this->data);
        $this->data += $result;

        return view('admin.employees.create', $this->data);
    }

    /**
     * @param EmployeeRequest $request
     * @return array|string[]
     * @throws \Exception
     */
    public function store(EmployeeRequest $request) : array
    {

        DB::beginTransaction();
        try {
            $result = $this->employeesServices->setCreate($request, $this->data);
            $this->data += $result;

            $this->logSearchEntry($this->user->id, $this->user->name, 'admin.employees.show', 'employee');

            DB::commit();
            return Reply::redirect(route('admin.employees.show', [$this->employee->user_id]), __('messages.employeeAdded'));
        } catch (\Swift_TransportException $e) {
            DB::rollback();
            return Reply::error('Please configure SMTP details to add employee. Visit Settings -> Email setting to set SMTP', 'smtp_error');
        } catch (Exception $e) {
            DB::rollback();
            return Reply::error("File:  " . $e->getFile() . " Line: " . $e->getLine() . " Message " . __($e->getMessage()));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @param Request $request
     * @return Application|Factory|View
     */
    public function show(Request $request, $id)
    {
        $result = $this->employeesServices->getShow($id, $this->data, $request);
        $this->data += $result;

        return view('admin.employees.show', $this->data);
    }

    /**
     * @param $id
     * @param Request $request
     * @return Application|Factory|View
     */
    public function employees_test_show(Request $request, $id)
    {
        $result = $this->employeesServices->getShow($id, $this->data, $request);
        $this->data += $result;

        return view('admin.employees.show-test', $this->data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return Application|Factory|View
     */
    public function edit($id)
    {
        $result = $this->employeesServices->getUpdate($id, $this->data);
        $this->data += $result;

        return view('admin.employees.edit', $this->data);
    }

    /**
     * @param EmployeeRequest $request
     * @param $id
     * @return array
     */
    public function update(EmployeeRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            $result = $this->employeesServices->setUpdate($id, $request, $this->data);
            // $this->data += $result;
            DB::commit();
            return Reply::redirect(route('admin.employees.show', [$id]), __('messages.employeeUpdated'));
        } catch (Exception $exception) {
            DB::rollBack();
            return Reply::error("File:  " . $exception->getFile() . " Line: " . $exception->getLine() . " Message " . __($exception->getMessage()));
        }


    }

    public function detachLanguages(Request $request)
    {
        $this->employeesServices->detachLanguages($request);
        return Reply::dataOnly(['status' => 'success']);
    }

    public function detachEmployeeDepartment(Request $request)
    {
        $this->employeesServices->detachEmployeeDepartment($request);
        return Reply::dataOnly(['status' => 'success']);
    }

    // Надо проверить возможно устарел и надо удалить
    public function detachPosition(Request $request)
    {
        $this->employeesServices->detachPosition($request);
        return Reply::dataOnly(['status' => 'success']);
    }

    /**
     * @param Request $request
     * @return array
     */
    public function changeStatusEmployees(Request $request)
    {

        $request->validate([
            'statusEmpl' => 'required|numeric',
        ], [
            'statusEmpl.*' => "The status empl can't be empty."
        ]);

        $this->employeesServices->changeStatusEmployees($request, $this->data);
        return Reply::success(__('messages.leadStatusChangeSuccess'));
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function changeDepartmentEmployees(Request $request)
    {
        $this->employeesServices->changeDepartmentByEmployee($request);

        return Reply::dataOnly(['status' => 'success', 'message' => 'Department changed successfully']);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function changePositionEmployees(Request $request)
    {
        $this->employeesServices->changePrimaryPositionByEmployee($request);
        return Reply::dataOnly(['status' => 'success', 'message' => 'Positions changed successfully']);
    }

    /**
     * @param $employeeID
     * @param $statusID
     * @return Application|Factory|View
     */
    public function changeStatusModal($employeeID, $statusID)
    {
        $result = $this->employeesServices->changeStatusModal($employeeID, $statusID, $this->data);
        $this->data += $result;
        return view('admin.employees.change-status', $this->data);
    }

    /**
     * @param StatusUpdateRequest $request
     * @return array
     */
    public function changeStatusEmployeesUpdate(StatusUpdateRequest $request)
    {
        $result = $this->employeesServices->changeStatusEmployeesUpdate($request, $this->data);
        $this->data += $result;
        return Reply::success(__('messages.leadStatusChangeSuccess'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return array
     */
    public function destroy($id)
    {
        $result = $this->employeesServices->setDestroy($id);
        return ($result) ?
            Reply::success(__('messages.employeeDeleted')) : Reply::error(__('messages.adminCannotDelete'));
    }

    /**
     * @param $userId
     * @param $hideCompleted
     * @return mixed
     * @throws Exception
     */
    public function tasks($userId, $hideCompleted)
    {
        $tasks = $this->employeesServices->getTasks($userId, $hideCompleted);

        return DataTables::of($tasks)
            ->editColumn('due_date', function ($row) {
                return ($row->due_date->isPast()) ?
                    '<span class="text-danger">' . $row->due_date->format($this->global->date_format) . '</span>' :
                    '<span class="text-success">' . $row->due_date->format($this->global->date_format) . '</span>';
            })
            ->editColumn('heading', function ($row) {
                $name = '<a href="javascript:;" data-task-id="' . $row->id . '" class="show-task-detail">' . ucfirst($row->heading) . '</a>';
                if ($row->is_private)
                    $name .= ' <i data-toggle="tooltip" data-original-title="' . __('app.private') . '" class="fa fa-lock" style="color: #ea4c89"></i>';
                return $name;
            })
            ->editColumn('column_name', function ($row) {
                return '<label class="label" style="background-color: ' . $row->label_color . '">' . $row->column_name . '</label>';
            })
            ->editColumn('project_name', function ($row) {
                return (!is_null($row->project_id)) ?
                    '<a href="' . route('member.projects.show', $row->project_id) . '">' . ucfirst($row->project_name) . '</a>' :
                    '-';
            })
            ->rawColumns(['column_name', 'project_name', 'due_date', 'heading'])
            ->removeColumn('project_id')
            ->make(true);
    }

    /**
     * @param $userId
     * @return mixed
     * @throws Exception
     */
    public function timeLogs($userId)
    {
        $timeLogs = $this->employeesServices->getTimeLogs($userId);

        return DataTables::of($timeLogs)
            ->editColumn('start_time', function ($row) {
                return $row->start_time->timezone($this->global->timezone)->format($this->global->date_format . ' ' . $this->global->time_format);
            })
            ->editColumn('end_time', function ($row) {
                return (!is_null($row->end_time)) ?
                    $row->end_time->timezone($this->global->timezone)->format($this->global->date_format . ' ' . $this->global->time_format) :
                    "<label class='label label-success'>Active</label>";
            })
            ->editColumn('project_name', function ($row) {
                return '<a href="' . route('admin.projects.show', $row->project_id) . '">' . ucfirst($row->project_name) . '</a>';
            })
            ->editColumn('total_hours', function ($row) {
                $timeLog = intdiv($row->total_minutes, 60) . ' hrs ';
                if (($row->total_minutes % 60) > 0)
                    $timeLog .= ($row->total_minutes % 60) . ' mins';
                return $timeLog;
            })
            ->rawColumns(['end_time', 'project_name'])
            ->removeColumn('project_id')
            ->make(true);
    }

    /**
     * @param $status
     * @param $employee
     * @param $role
     * @return void
     */
    public function export($status, $employee, $role)
    {
        if ($role != 'all' && $role != '') {
            $userRoles = Role::findOrFail($role);
        }
        $rows = User::join('role_user', 'role_user.user_id', '=', 'users.id')
            ->withoutGlobalScope('active')
            ->join('roles', 'roles.id', '=', 'role_user.role_id')
            ->where('roles.name', '<>', 'client')
            ->leftJoin('employee_details', 'users.id', '=', 'employee_details.user_id')
            ->leftJoin('designations', 'designations.id', '=', 'employee_details.designation_id')
            ->select(
                'users.id',
                'users.name',
                'users.email',
                'users.mobile',
                'designations.name as designation_name',
                'employee_details.address',
                'employee_details.hourly_rate',
                'users.created_at',
                'roles.name as roleName'
            );
        if ($status != 'all' && $status != '') {
            $rows = $rows->where('users.status', $status);
        }

        if ($employee != 'all' && $employee != '') {
            $rows = $rows->where('users.id', $employee);
        }

        if ($role != 'all' && $role != '' && $userRoles) {
            if ($userRoles->name == 'admin') {
                $rows = $rows->where('roles.id', $role);
            } elseif ($userRoles->name == 'employee') {
                $rows = $rows->where(\DB::raw("(select user_roles.role_id from role_user as user_roles where user_roles.user_id = users.id ORDER BY user_roles.role_id DESC limit 1)"), $role)
                    ->having('roleName', '<>', 'admin');
            } else {
                $rows = $rows->where(\DB::raw("(select user_roles.role_id from role_user as user_roles where user_roles.user_id = users.id ORDER BY user_roles.role_id DESC limit 1)"), $role);
            }
        }
        $attributes = ['roleName'];
        $rows = $rows->groupBy('users.id')->get()->makeHidden($attributes);

        // Initialize the array which will be passed into the Excel
        // generator.
        $exportArray = [];

        // Define the Excel spreadsheet headers
        $exportArray[] = ['ID', 'Name', 'Email', 'Mobile', 'Designation', 'Address', 'Hourly Rate', 'Created at', 'Role'];

        // Convert each member of the returned collection into an array,
        // and append it to the payments array.
        foreach ($rows as $row) {
            $exportArray[] = [
                "id" => $row->id,
                "name" => $row->name,
                "email" => $row->email,
                "mobile" => $row->mobile,
                "Designation" => $row->designation_name,
                "address" => $row->address,
                "hourly_rate" => $row->hourly_rate,
                "created_at" => $row->created_at->format('Y-m-d h:i:s a'),
                "roleName" => $row->roleName
            ];
        }

        // Generate and return the spreadsheet
        Excel::create('Employees', function ($excel) use ($exportArray) {

            // Set the spreadsheet title, creator, and description
            $excel->setTitle('Employees');
            $excel->setCreator('Worksuite')->setCompany($this->companyName);
            $excel->setDescription('Employees file');

            // Build the spreadsheet, passing in the payments array
            $excel->sheet('sheet1', function ($sheet) use ($exportArray) {
                $sheet->fromArray($exportArray, null, 'A1', false, false);

                $sheet->row(1, function ($row) {

                    // call row manipulation methods
                    $row->setFont(array(
                        'bold' => true
                    ));
                });
            });
        })->download('xlsx');
    }

    /**
     * @param Request $request
     * @return array
     */
    public function assignRole(Request $request)
    {
        $this->employeesServices->assignRole($request);
        return Reply::success(__('messages.roleAssigned'));
    }

    /**
     * @param Request $request
     * @return array
     */
    public function assignProjectAdmin(Request $request)
    {
        $this->employeesServices->assignProjectAdmin($request);
        return Reply::success(__('messages.roleAssigned'));
    }

    /**
     * @param Request $request
     * @param $id
     * @return Application|Factory|View
     */
    public function docsCreate(Request $request, $id)
    {
        $this->employeeID = $id;
        $this->upload = can_upload();
        return view('admin.employees.docs-create', $this->data);
    }

    /**
     * @return Application|Factory|View
     * @throws Exception
     */
    public function freeEmployees()
    {
        if (\request()->ajax()) {

            $whoseProjectCompleted = ProjectMember::join('projects', 'projects.id', '=', 'project_members.project_id')
                ->join('users', 'users.id', '=', 'project_members.user_id')
                ->select('users.*')
                ->groupBy('project_members.user_id')
                ->havingRaw("min(projects.completion_percent) = 100 and max(projects.completion_percent) = 100")
                ->orderBy('users.id')
                ->get();

            $notAssignedProject = User::join('role_user', 'role_user.user_id', '=', 'users.id')
                ->join('roles', 'roles.id', '=', 'role_user.role_id')
                ->select('users.*')
                ->whereNotIn('users.id', function ($query) {

                    $query->select('user_id as id')->from('project_members');
                })
                ->where('roles.name', '<>', 'client')
                ->get();

            $freeEmployees = $whoseProjectCompleted->merge($notAssignedProject);

            return DataTables::of($freeEmployees)
                ->addColumn('action', function ($row) {
                    return '<a href="' . route('admin.employees.edit', [$row->id]) . '" class="btn btn-info btn-circle"
                      data-toggle="tooltip" data-original-title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i></a>

                      <a href="' . route('admin.employees.show', [$row->id]) . '" class="btn btn-success btn-circle"
                      data-toggle="tooltip" data-original-title="View Employee Details"><i class="fa fa-search" aria-hidden="true"></i></a>

                      <a href="javascript:;" class="btn btn-danger btn-circle sa-params"
                      data-toggle="tooltip" data-user-id="' . $row->id . '" data-original-title="Delete"><i class="fa fa-times" aria-hidden="true"></i></a>';
                })
                ->editColumn(
                    'created_at',
                    function ($row) {
                        return Carbon::parse($row->created_at)->format($this->global->date_format);
                    }
                )
                ->editColumn(
                    'status',
                    function ($row) {
                        if ($row->status == 'active') {
                            return '<label class="label label-success">' . __('app.active') . '</label>';
                        } else {
                            return '<label class="label label-danger">' . __('app.inactive') . '</label>';
                        }
                    }
                )
                ->editColumn('name', function ($row) {
                    $image = '<img src="' . $row->image_url . '" alt="user" class="img-circle" width="30" height="30"> ';
                    return '<a href="' . route('admin.employees.show', $row->id) . '">' . $image . ' ' . ucwords($row->name) . '</a>';
                })
                ->rawColumns(['name', 'action', 'role', 'status'])
                ->removeColumn('roleId')
                ->removeColumn('roleName')
                ->removeColumn('current_role')
                ->make(true);
        }

        return view('admin.employees.free_employees', $this->data);
    }

    public function setUpdateEmployeesByRequest(EmployeeTaskBoardRequest $request)
    {
        $this->employeesServices->setUpdateEmployeesByRequest($request);
        return Reply::success(__('messages.employeeUpdated'));
    }

    public function editLanguagesByUserModal($id)
    {
        $this->employeesServices->editLanguagesByUserModal($this, $id);
        return view('admin.employees.modal.edit-languages', $this->data);
    }

    public function setEditLanguagesByUserModal(LanguageRequest $request)
    {
        DB::beginTransaction();
        try {
            $this->employeesServices->setEditLanguagesByUserModal($this, $request);
            DB::commit();
            return Reply::success(__('messages.employeeLanguages.updateSuccess'));
        } catch (Exception $e) {
            DB::rollback();
            return Reply::error("File:  " . $e->getFile() . " Line: " . $e->getLine() . " Message " . __($e->getMessage()));
        }
    }

    public function editSkillsByUserModal($id)
    {
        $this->employeesServices->editSkillsByUserModal($this, $id);
        return view('admin.employees.modal.edit-skills', $this->data);
    }

    public function setEditSkillsByUserModal(SkillRequest $request)
    {
        DB::beginTransaction();
        try {
            $this->employeesServices->setEditSkillsByUserModal($this, $request);
            DB::commit();
            return Reply::success(__('messages.employeeSkills.updateSuccess'));
        } catch (Exception $e) {
            DB::rollback();
            return Reply::error("File:  " . $e->getFile() . " Line: " . $e->getLine() . " Message " . __($e->getMessage()));
        }
    }

    public function destroySkills(Request $request)
    {
        DB::beginTransaction();
        try {
            $this->employeesServices->destroySkills($this, $request);
            DB::commit();
            return Reply::success(__('messages.employeeSkills.deleteSuccess'));
        } catch (Exception $e) {
            DB::rollback();
            return Reply::error("File:  " . $e->getFile() . " Line: " . $e->getLine() . " Message " . __($e->getMessage()));
        }
    }

    public function checkShortName(Request $request)
    {
        $message = $this->employeesServices->checkShortName($this, $request);

        return Reply::dataOnly(['status' => 'success', 'message' => $message]);
    }

    public function checkSlug(Request $request)
    {
        $message = $this->employeesServices->checkSlug($this, $request);

        return Reply::dataOnly(['status' => 'success', 'message' => $message]);
    }

    public function getInterviewDataByEmployee($id)
    {
        $result = $this->employeesServices->getInterviewDataByEmployee($id);
        return Reply::dataOnly($result);
    }

    public function getUserReports(Request $request, $id)
    {
        $result = $this->employeesServices->getUserReportsByDataRange($id, $request);

        return Reply::dataOnly([
            'status' => 'success',
            'data' => $result,
        ]);
    }

}
