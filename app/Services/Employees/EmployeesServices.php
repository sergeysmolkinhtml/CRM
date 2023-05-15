<?php

namespace App\Services\Employees;

use App\Config\DefaultConfig;
use App\DataTransferObjects\Employee\EmployeeArtistDTO;//
use App\DataTransferObjects\Employee\EmployeeContentDTO;//
use App\DataTransferObjects\Employee\EmployeeDetailsDTO;//
use App\DataTransferObjects\Employee\EmployeeVideoEditingDTO;//
use App\DataTransferObjects\HR\HrDTO;//
use App\Helpers\Files;
use App\Models\City;
use App\Models\Country;
use App\Models\Currency;
use App\Models\Designation;
use App\Models\Employee\EducationDegree;
use App\Models\Employee\EducationExperienceTag;
use App\Models\Employee\EmployeeArtist;
use App\Models\Employee\EmployeeContent;
use App\Models\Employee\EmployeeDetails;
use App\Models\Employee\EmployeeDocs;
use App\Models\Employee\EmployeeHrCorrespondence;
use App\Models\Employee\EmployeeLanguages;
use App\Models\Employee\EmployeeOffice;
use App\Models\Employee\EmployeeSkill;
use App\Models\Employee\EmployeeTeam;
use App\Models\Employee\EmployeeVideoEditing;
use App\Models\Employee\Responsibility;
use App\Models\Employee\EmployeeStatus;
use App\Models\HrDetails;
use App\Models\HrStatusCandidate;
use App\Models\LangLevel;
use App\Models\Languages;
use App\Models\LeadAgent;
use App\Models\Leave;
use App\Models\LeaveType;
use App\Models\Position;
use App\Models\Project;
use App\Models\ProjectMember;
use App\Models\ProjectTimeLog;
use App\Models\Region;
use App\Models\Role;
use App\Models\RoleUser;
use App\Models\Skill;
use App\Models\Task;
use App\Models\Taskboard as TaskboardColumn;
use App\Models\Team;
use App\Models\Tools\UniversalSearch;
use App\Models\User;
use App\Models\UserActivity;
use App\Repositories\Interfaces\AddressRepositoryInterface;
use App\Repositories\Interfaces\EmployeeArtistRepositoryInterface;
use App\Repositories\Interfaces\EmployeeContentRepositoryInterface;
use App\Repositories\Interfaces\EmployeesRepositoryInterface;
use App\Repositories\Interfaces\EmployeesVideoEditingRepositoryInterface;
use App\Repositories\Interfaces\HrRepositoryInterface;
use App\Services\HrServices;
use App\Services\ServiceBase;
use App\Tools\Tools;
use App\Traits\ActivityInTextTrait;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Exception;
use Illuminate\Http\Concerns\InteractsWithInput;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;


class EmployeesServices extends ServiceBase
{
    use InteractsWithInput, ActivityInTextTrait;

    private EmployeesRepositoryInterface $employeesRepository;
    private AddressRepositoryInterface $addressRepository;
    private HrRepositoryInterface $hrRepository;
    private HrServices $hrService;
    private array $searchArray;
    private EmployeesHrVideoInterviewServices $employeesHrVideoInterviewServices;
    private EmployeeArtistRepositoryInterface $employeeArtistRepository;
    private EmployeeContentRepositoryInterface $employeeContentRepository;
    private EmployeesVideoEditingRepositoryInterface $editingRepository;


    public function __construct(
        AddressRepositoryInterface               $addressRepository,
        EmployeesRepositoryInterface             $employeesRepository,
        HrRepositoryInterface                    $hrRepository,
        EmployeesHrVideoInterviewServices        $employeesHrVideoInterviewServices,
        HrServices                               $hrService,
        EmployeeArtistRepositoryInterface        $employeeArtistRepository,
        EmployeeContentRepositoryInterface       $employeeContentRepository,
        EmployeesVideoEditingRepositoryInterface $editingRepository
    )
    {
        $this->employeesRepository = $employeesRepository;
        $this->addressRepository = $addressRepository;
        $this->hrRepository = $hrRepository;
        $this->employeesHrVideoInterviewServices = $employeesHrVideoInterviewServices;
        $this->hrService = $hrService;
        $this->searchArray = DefaultConfig::$phoneArray;
        $this->employeeArtistRepository = $employeeArtistRepository;
        $this->employeeContentRepository = $employeeContentRepository;
        $this->editingRepository = $editingRepository;
    }

    /**
     * @param $data
     * @return array|mixed
     */
    public function getIndex($data)
    {
        $this->data = $data;

        $this->employees = User::allEmployees();
        $this->skills = Skill::getAllShort()->get();
        $this->departments = Team::getWithoutMemberShort()->get();
        $this->positions = Position::getAllShort()->get();
        $this->designations = Designation::getAllShort()->get();
        $this->totalEmployees = count($this->employees);
        $this->roles = Role::where('roles.name', '<>', 'client')->orderBy("name")->get();

        $this->type = DefaultConfig::getTypesForDepartments();
        $this->tags = EducationExperienceTag::getAllShort()->get();
        $this->exptypes = DefaultConfig::getTypesForEducationExperience();
        $this->responsibilities = Responsibility::getAllShort()->get();

        $whoseProjectCompleted = ProjectMember::join('projects', 'projects.id', '=', 'project_members.project_id')
            ->join('users', 'users.id', '=', 'project_members.user_id')
            ->select('users.*')
            ->groupBy('project_members.user_id')
            ->havingRaw("min(projects.completion_percent) = 100 and max(projects.completion_percent) = 100")
            ->orderBy('users.id')
            ->get();

        $notAssignedProject = User::join('role_user', 'role_user.user_id', '=', 'users.id')
            ->join('roles', 'roles.id', '=', 'role_user.role_id')
            ->select('users.id', 'users.name')->whereNotIn('users.id', function ($query) {
                $query->select('user_id as id')->from('project_members');
            })
            ->where('roles.name', '<>', 'client')
            ->get();

        $this->freeEmployees = $whoseProjectCompleted->merge($notAssignedProject)->count();

        $this->statusEmployees = EmployeeStatus::getAllShort()->get();
        $this->statusEmployeesForModal = json_encode(['Fired', 'Left', 'Didn\'t start']);

        return $this->data;
    }

    /**
     * @param $request
     * @return array
     */
    public function getData($request)
    {
        $orderColumn = $request->order[0]['column'] ?? 0;
        $this->orderColumns = $request->columns[$orderColumn]['name'] ?? 'name';
        $this->orderDir = $request->order[0]['dir'] ?? 'asc';
        $searchValue = $request->search["value"] ?? null;

        $draw = $request->draw;
        $skip = $request->start ?? 0;
        $take = $request->length ?? 10;

        if ($request->role != 'all' && $request->role != '') {
            $userRoles = Role::findOrFail($request->role);
        }

        $usersTotal = User::with('role')
            ->withoutGlobalScope('active')
            ->join('role_user', 'role_user.user_id', '=', 'users.id')
            ->leftJoin('employee_details', 'employee_details.user_id', '=', 'users.id')
            ->leftJoin('designations', 'employee_details.designation_id', '=', 'designations.id')
            ->leftJoin('employees_statuses', 'employee_details.status_id', '=', 'employees_statuses.id')
            ->join('roles', 'roles.id', '=', 'role_user.role_id')
            ->leftJoin('teams', 'teams.id', '=', 'employee_details.department_id')
            ->leftJoin('positions', 'employee_details.position_id', '=', 'positions.id')
            ->leftJoin('employee_work_experiences', 'employee_work_experiences.user_id', '=', 'users.id')
            ->leftJoin('work_experience_tags', 'work_experience_tags.work_experience_id', '=', 'employee_work_experiences.id')
            ->leftJoin('education_experience_tags', 'work_experience_tags.tag_id', '=', 'education_experience_tags.id')
            ->leftJoin('employee_education', 'employee_education.user_id', '=', 'users.id')
            ->leftJoin('education_tags', 'education_tags.education_id', '=', 'employee_education.id')
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
                'employee_details.joining_date as start_date',
                'employee_details.viber as employee_viber',
                'employees_statuses.name as statusEmployeeName',
                'teams.team_name as department'
            )
            ->where('roles.name', '<>', 'client');

        $users = $usersTotal;

        if (Tools::dateCheck($request->startDate)) {
            $startDate = Carbon::createFromFormat($this->global->date_format, $request->startDate)->toDateString();
            $users = $users->where(DB::raw('DATE(employee_details.joining_date)'), '>=', $startDate);
        }

        if (Tools::dateCheck($request->endDate)) {
            $endDate = Carbon::createFromFormat($this->global->date_format, $request->endDate)->toDateString();
            $users = $users->where(DB::raw('DATE(employee_details.joining_date)'), '<=', $endDate);
        }

        if (Tools::stringCheck($request->status)) {
            $users = $users->where('users.status', $request->status);
        }

        if (Tools::stringCheck($request->employee)) {
            $users = $users->where('users.id', $request->employee);
        }

        if (Tools::stringCheck($searchValue)) {
            $users = $users->where(function ($query) use ($searchValue) {
                $query->where('users.name', 'like', '%' . $searchValue . '%');
            });
        }

        if (Tools::arrayCheck($request->designation_id)) {
            $users = $users->whereIn('employee_details.designation_id', $request->designation_id);
        }

        if (Tools::arrayCheck($request->statusEmp)) {
            $users = $users->whereIn('employee_details.status_id', $request->statusEmp);
        }

        if (Tools::arrayCheck($request->responsibilities)) {
            $users = $users->leftJoin("employees_responsibilities", "employee_details.id", "employees_responsibilities.user_id");
            $users = $users->whereIn('employees_responsibilities.response_id', $request->responsibilities);
        }

        if ($request->exptypes && Tools::arrayCheck($request->tags)) {
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

        if (Tools::arrayCheck($request->role)) {
            $users = $users->whereIn('roles.id', $request->role);
            if (in_array('employee', $userRoles->pluck("name")->toArray())) {


                $idRoleEmployee = $userRoles->where("name", "employee")->first()->id;
                $request->role = array_flip($request->role); //Меняем местами ключи и значения
                unset ($request->role[$idRoleEmployee]); //Удаляем элемент массива
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

        if (Tools::arrayCheck($request->skill)) {
            $users = $users->join('employee_skills', 'employee_skills.user_id', '=', 'users.id')
                ->whereIn('employee_skills.skill_id', $request->skill);
        }

        if ($request->type && (Tools::arrayCheck($request->positions) || Tools::arrayCheck($request->department))) {
            $users = $users->leftJoin('employee_teams', 'employee_teams.user_id', '=', 'users.id');
        }

        if ($request->type && Tools::arrayCheck($request->department)) {
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

        if ($request->type && Tools::arrayCheck($request->positions)) {
            if ($request->type == "secondary" || $request->type == "all") {
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

        $users->groupBy('users.id');

        $final = $users
            ->skip($skip ?? 0)
            ->take($take ?? 10)
            ->get();

//        dd($final, $users->get()->count(), $usersTotal->get()->count());

        return [
            'status' => 'success',
            'draw' => $draw,
            'recordsTotal' => $usersTotal->get()->count(),
            'recordsFiltered' => $users->get()->count(),
            'data' => $final,
        ];
    }

    public function getCreate($candidateID = null, $data)
    {
        $this->data = $data;

        if ($candidateID)
            $this->hr = HrDetails::with([
                'hrLanguages',
                'address',
                'hrManager' => function ($query) {
                    $query->withoutGlobalScope('active');
                },
                "hrDepartments.positions",
                "hrDepartments.department",
                'correspondences'
            ])->findOrFail($candidateID);

        EmployeeStatus::updateOrCreate(['name' => __('modules.statusEmployees.hired')]);
        $employee = new EmployeeDetails();
        $this->fields = $employee->getCustomFieldGroupsWithFields()->fields;
        $this->teams = Team::getAllShort()->get();
        $this->designations = Designation::getAllShort()->get();
        $this->emplStatuses = EmployeeStatus::getAllShort()->get();
        $this->offices = EmployeeOffice::all();
        $this->languages = Languages::getAllShort()->get();
        $this->langLevels = LangLevel::all();
        $this->countries = Country::getAllShort()->get();
        $this->positions = Position::getAllShort()->get();
        $this->regions = Region::getAllShort()->addSelect('name_ru as name')->get();
        $this->skills = Skill::getAllShort()->get();
        $this->cities = City::getAllShort()->addSelect('name_ru as name')->get();
        $this->currencies = Currency::getAllShort()->get();
        $this->responsibilities = Responsibility::getAllShort()->get();


//        $roleTeamLead = Role::with('roleuser.user')
//            ->firstOrCreate([
//                'name' => __('app.roleEmployee.teamLead'),
//                'display_name' => __('app.roleEmployee.teamLead'),
//            ]);

        $this->genders = DefaultConfig::getGender();
        $this->usersTeamLeaders = User::getTeamLeadUser()->orderby('users.name')->get();

        return $this->data;
    }

    /**
     * @throws Exception
     */
    public function setCreate($request, $data)
    {

        $this->data = $data;

        $data = $request->all();

        $data['password'] = Hash::make($request->password);
        $data['date_format'] = $this->global->date_format;
        $data['time_format'] = $this->global->time_format;


        if ($request->hasFile('image'))
            $data['image'] = Files::upload($request->image, 'avatar', 300);

        $data['status'] = 'active';
        $data['mobile'] = str_replace($this->searchArray, '', $data['mobile'] ?? $data['phone']);
        $this->userCreate = User::create($data);

        $request['user_id'] = $this->userCreate->id;
        $data['user_id'] = $this->userCreate->id;

        $addressResultCreat = $this->addressRepository->update((int)$data['hr_address_id'], $data);
//dd((int)$data['hr_address_id'], $data,$addressResultCreat);
        if ($addressResultCreat['status'])
            $data['address_id'] = $addressResultCreat['data']['model']->id; // Add address_id for hrRepository relation
        $request['address_id'] = $addressResultCreat['data']['model']->id; // Add address_id for hrRepository relation

        $data['last_date'] = null;
        if ($request->last_date)
            $data['last_date'] = Carbon::createFromFormat($this->global->date_format, $request->last_date)->format('Y-m-d');

        $projectCompany = DefaultConfig::getProjectCompanyForEmployees();

        $request["project_company_id"] = $projectCompany->id;

        $this->employee = $this->employeesRepository->createDTO(EmployeesDetailsDTo::getArray($request->all()));

        if ($request['responsibilities']) {
            $this->employee->responsibilities()->sync($request["responsibilities"]);
        }

        $this->employeeArtistRepository->create(EmployeeArtistDTO::getArray($request->all(), $this->employee->id));
        $this->employeeContentRepository->create(EmployeeContentDTO::getArray($request->all(), $this->employee->id));
        $this->editingRepository->create(EmployeesVideoEditingDTo::getArray($request->all(), $this->employee->id));

        $this->employeesRepository->createOrUpdateEmployeesSkills($data);

        $this->employeesRepository->createEmployeesPortfolios($data);

        $this->employeesRepository->createOrUpdateEmployeesLanguages($data);

        $this->employeeDepartments($this->employee, $request);

        if (isset($request->hr_id) && $request->hr_id) {
            $statusHR = HrStatusCandidate::where('name', __('modules.hrStatuses.started'))->first();
            $data['employee_details_id'] = $this->employee->id;

            if ($statusHR)
                $data['status_candidate_id'] = HrStatusCandidate::getStatusIdByName();

            $tempInfo = $this->employeesRepository->getArrayForUpdateHrDetails($data);

            $hrOr = $this->hrRepository->find($data['hr_id']);
            $hr = $this->hrRepository->updateDTO($data['hr_id'], HrDTo::getArray($tempInfo));

            if ($hr->wasChanged())
                $this->hrActivity($hrOr->getOriginal(), $hr->getChanges());

            $skillRequest = collect(json_decode($request->skills))->pluck('skill_id')->values();

            $hr->skills()->sync($skillRequest);
            $hr->correspondences()->update(['employee_details_id' => $this->employee->id]);

            $this->hrService->updateHrDepartmentFromEmployee($hr, $request);
        }

        // To add custom fields data
        if ($request->get('custom_fields_data'))
            $this->userCreate->employeeDetail->updateCustomFieldData($request->get('custom_fields_data'));

        $role = Role::where('name', 'employee')->first();
        $this->userCreate->attachRole($role->id);


        return $this->data;
    }


    public function getShow($id, $data, $request)
    {
        $this->data = $data;
        $this->employee = User::with([
            'employeePortfolios',
            'leadAccount',
            'leadAccount.user' => function ($query) {
                $query->withoutGlobalScope('active');
            },
            'employeeSkills.skill',

            'employeeLanguages.language',
            'employeeLanguages.langLevel',
            'employeeDetail.designation',
            'employeeDetail.department',
            'employeeDetail.position',
            'employeeDetail.office',
            'employeeDetail.emplstatus',
            'employeeDetail.emplArtist',
            'employeeDetail.empaddress.country',
            'employeeDetail.empaddress.city',
            'employeeDetail.candidate.hrManager',
            'employeeDetail.teamLead',
            'employeeDetail.videoEditing',
            'employeeDetail.full_currency',
            'employeeDetail.part_currency',
            'employeeDetail.minimum_currency',
            'employeeDetail.designation',
            'employeeDetail.responsibilities',
            'educations.tags',
            'educations.degree',
            'educations' => function ($query) {
                $query->orderBy('start_date');
            },
            'workExperiences' => function ($query) {
                $query->orderBy('start_date');
            },
            'workExperiences.tags',
            'workExperiences.responsibilities_tags',
            'employeeDepartments.department',
            'employeeDepartments.positions',

        ])->withoutGlobalScope('active')->findOrFail($id);
        $this->employeeDetail = EmployeeDetails::with([
            "correspondences" => function ($query) {
                $query->orderBy('created_at', "desc");
            },
            "correspondences.createdBy"
        ])->where('user_id', '=', $this->employee->id)->first();


        if (!$this->employeeDetail)
            $this->employeeDetail = $this->employeesRepository->createDTO(EmployeesDetailsDTo::getArray(["user_id" => $this->employee->id]));


        $projectCompany = DefaultConfig::getProjectCompanyForEmployees();

        $this->videoEditing = EmployeeVideoEditing::where("employee_details_id", $this->employeeDetail->id)
            ->where("project_company_id", $projectCompany->id)->first();

        $this->employeeContent = EmployeeContent::where("employee_details_id", $this->employeeDetail->id)
            ->where("project_company_id", $projectCompany->id)->first();

        $this->employeeArtist = EmployeeArtist::where("employee_details_id", $this->employeeDetail->id)
            ->where("project_company_id", $projectCompany->id)->first();

        $this->employeeDocs = EmployeeDocs::where('user_id', '=', $this->employee->id)->get();

        if (!is_null($this->employeeDetail)) {
            $this->employeeDetail = $this->employeeDetail->withCustomFields();
            $this->fields = $this->employeeDetail->getCustomFieldGroupsWithFields()->fields;
        }

        $completedTaskColumn = TaskboardColumn::where('slug', 'completed')->first();
        $this->taskCompleted = Task::join('task_users', 'task_users.task_id', '=', 'tasks.id')
            ->where('task_users.user_id', $id)
            ->where('tasks.board_column_id', $completedTaskColumn->id)
            ->count();

        $hoursLogged = ProjectTimeLog::where('user_id', $id)->sum('total_minutes');

        $timeLog = intdiv($hoursLogged, 60) . ' hrs ';

        if (($hoursLogged % 60) > 0)
            $timeLog .= ($hoursLogged % 60) . ' mins';

        $this->hoursLogged = $timeLog;

        $this->activities = UserActivity::where('user_id', $id)->orderBy('id', 'desc')->get();
        $this->projects = Project::select('projects.id', 'projects.project_name', 'projects.deadline', 'projects.completion_percent')
            ->join('project_members', 'project_members.project_id', '=', 'projects.id')
            ->where('project_members.user_id', '=', $id)
            ->get();
        $this->leaves = Leave::byUser($id);
        $this->leavesCount = Leave::byUserCount($id);

        $this->leaveTypes = LeaveType::byUser($id);
        $this->allowedLeaves = LeaveType::sum('no_of_leaves');

        $this->tags = EducationExperienceTag::getAllShort()->get();
        $this->responsibilities = Responsibility::getAllShort()->get();
        $this->degrees = EducationDegree::getAllShort()->get();

        $this->startReportsDate = $request->startReportsDate ?: Carbon::now()->startOfMonth()->format('Y-m-d');
        $this->endReportsDate = $request->endReportsDate ?: Carbon::now()->endOfMonth()->format('Y-m-d');

        return $this->data;
    }

    public function getUpdate($id, $data)
    {
        $this->data = $data;

        $this->userDetail = User::with([
            'employeeDetail',
            'employeePortfolios',
            'employeeLanguages',
            'employeeDetail.designation',
            'employeeDetail.department',
            'employeeDetail.office',
            'employeeDetail.emplArtist',
            'employeeDetail.emplstatus',
            'employeeDetail.empaddress',
            'employeeDetail.emplcity',
            'employeeDetail.candidate',
            'employeeDetail.videoEditing',
            'employeeDetail.full_currency',
            'employeeDetail.part_currency',
            'employeeDetail.minimum_currency',
            'employeeDetail.responsibilities',
            'employeeSkills',
            'employeeDepartments',
            'employeeDepartments.positions',
        ])->withoutGlobalScope('active')->findOrFail($id);
        $this->offices = EmployeeOffice::all();
        $this->languages = Languages::getAllShort()->get();
        $this->langLevels = LangLevel::all();
        $this->countries = Country::getAllShort()->get();
        $this->cities = City::getAllShort()->addSelect('name_ru as name')->where('id', '=', $this->userDetail->employeeDetail->empaddress->city_id ?? null)->get();
        $this->regions = Region::getAllShort()->addSelect('name_ru as name')->get();
        $this->emplStatuses = EmployeeStatus::getAllShort()->get();
        $this->positions = Position::getAllShort()->get();
        $this->portfolios = $this->userDetail->employeePortfolios->pluck("url")->toArray();
        $this->responsibilities = Responsibility::getAllShort()->get();

        $this->employeeDetail = EmployeeDetails::where('user_id', '=', $this->userDetail->id)->first();

        $projectCompany = DefaultConfig::getProjectCompanyForEmployees();

        if (!$this->employeeDetail)
            $this->employeeDetail = $this->employeesRepository->createDTO(EmployeesDetailsDTo::getArray(["user_id" => $this->userDetail->id]));

        $this->videoEditing = EmployeeVideoEditing::where("employee_details_id", $this->employeeDetail->id)
            ->where("project_company_id", $projectCompany->id)->first();

        $this->employeeContent = EmployeeContent::where("employee_details_id", $this->employeeDetail->id)
            ->where("project_company_id", $projectCompany->id)->first();

        $this->employeeArtist = EmployeeArtist::where("employee_details_id", $this->employeeDetail->id)
            ->where("project_company_id", $projectCompany->id)->first();

        $this->skills = Skill::getAllShort()->get();
        $this->teams = Team::getAllShort()->get();
        $this->designations = Designation::getAllShort()->get();
        $this->currencies = Currency::getAllShort()->get();

        if (!is_null($this->employeeDetail)) {
            $this->employeeDetail = $this->employeeDetail->withCustomFields();
            $this->fields = $this->employeeDetail->getCustomFieldGroupsWithFields()->fields;
        }
//        $roleTeamLead = Role::with('roleuser.user')
//            ->updateOrCreate([
//                'name' => __('app.roleEmployee.teamLead'),
//                'display_name' => __('app.roleEmployee.teamLead'),
//            ]);

        $this->usersTeamLeaders = User::getTeamLeadUser()->get();
        $this->genders = DefaultConfig::getGender();

        return $this->data;
    }

    public function setUpdate($id, $request, $data)
    {
        $this->data = $data;

        $data = $request->all();
        $data['user_id'] = $id;
        $request['user_id'] = $id;
        $data['date_format'] = $this->global->date_format;
        $data['time_format'] = $this->global->time_format;
        $data['mobile'] =
            str_replace($this->searchArray, '', $data['mobile'] ?? $data['phone']);

        if ($request->hasFile('image'))
            $data['image'] = Files::upload($request->image, 'avatar', 300);

        $this->employeesRepository->updateUserProfile($data);
        $this->employeesRepository->createOrUpdateEmployeesSkills($data);
        $this->employeesRepository->updateEmployeesPortfolios($data);
        $this->employeesRepository->createOrUpdateEmployeesLanguages($data);
        $address = $this->addressRepository->update($data['address_id'], $data);

        if ($address['status'] == true)
            $request['address_id'] = $address['data']['original']['id'] ?? $address['data']['changed']['id'];

        $employee = $this->employeesRepository->updateDTO(null, $id, EmployeesDetailsDTo::getArray($request->all()));

        if ($request['responsibilities']) {
            $employee->responsibilities()->sync($request["responsibilities"]);
        }

        $this->updateDetailsEmployeeByProject($request, $employee);

        $this->employeeDepartments($employee, $request);

        if ($employee->candidate) {

            $tempInfo = $this->employeesRepository->getArrayForUpdateHrDetails($data);
//               dd($tempArray);
            $tempArray['date_format'] = $this->global->date_format;

            $hrOr = $this->hrRepository->find($employee->candidate->id);

            $hr = $this->hrRepository->updateDTO($employee->candidate->id, HrDTo::getArray($tempInfo));

            $skillRequest = collect(json_decode($request->skills))->pluck('skill_id')->values();

            $hr->skills()->sync($skillRequest);


            if ($hr->wasChanged())
                $this->hrActivity($hrOr->getOriginal(), $hr->getChanges());

            $this->hrService->updateHrDepartmentFromEmployee($hr, $request);

            $this->hrRepository->hrLanguagesCheckChangesByHrDetailId(
                $hr->id,
                $data
            );
        }

        // To add custom fields data
        if ($request->get('custom_fields_data')) {
            $employee->updateCustomFieldData($request->get('custom_fields_data'));
        }

        session()->forget('user');

        return $this->data;
    }

    public function updateDetailsEmployeeByProject($request, $employee)
    {
        $projectCompany = DefaultConfig::getProjectCompanyForEmployees();

        $request['project_company_id'] = $projectCompany->id;
        $employeeArtist = $this->employeeArtistRepository->findByProject($employee->id, $projectCompany->id);
        $this->employeeArtistRepository->update($employeeArtist ? $employeeArtist->id : null, null, EmployeesArtistDTo::getArray($request->all(), $employee->id));

        $employeeContent = $this->employeeContentRepository->findByProject($employee->id, $projectCompany->id);
        $this->employeeContentRepository->update($employeeContent ? $employeeContent->id : null, null, EmployeesContentDTo::getArray($request->all(), $employee->id));

        $videoEditing = $this->editingRepository->findByProject($employee->id, $projectCompany->id);
        $this->editingRepository->update($videoEditing ? $videoEditing->id : null, null, EmployeesVideoEditingDTo::getArray($request->all(), $employee->id));

    }

    public function getInterviewDataByEmployee($id)
    {
        $clientCandidates = DB::table('client_candidates')
            ->select(
                'client_details.id as id',
                'client_details.company_name as company',
                'client_candidates.interview_date',
                'client_candidates.time_interview as interview_time',
                'client_candidates.start_date',
                'client_candidates.fire_date',
                'client_candidates_employment_statuses.name as status_employment_name',
                'client_status_candidates.name as status_candidate_name',
                DB::raw(" (CASE WHEN (client_candidates.start_date IS NOT NULL) THEN 'true' ELSE 'false' END) AS hired"),
                DB::raw(" (CASE WHEN (client_candidates.fire_date IS NOT NULL) THEN 'true' ELSE 'false' END) AS fired"),
            )
            ->leftJoin('client_details', 'client_candidates.client_detail_id', '=', 'client_details.id')
            ->leftJoin('client_status_candidates', 'client_candidates.status_id', '=', 'client_status_candidates.id')
            ->leftJoin('employee_details', 'client_candidates.user_id', '=', 'employee_details.user_id')
            ->leftJoin('client_candidates_employment_statuses', 'client_candidates.employment_status_id', '=', 'client_candidates_employment_statuses.id')
            ->where('client_candidates.user_id', '=', $id)
            ->get();

        $clientCandidates = $clientCandidates->map(function ($item, $key) {

            $startDate = Carbon::parse($item->start_date);
            $fireDate = ($item->fire_date) ? Carbon::parse($item->fire_date) : Carbon::now();

            $day = $startDate->diffInDays($fireDate);
            $month = $startDate->diffInMonths($fireDate);

            $item->work_day = $day;
            $item->work_month = $month;

            return $item;
        });

        $items = $clientCandidates;
        $total = $clientCandidates->count();
        $totalInterviewDateCount = $clientCandidates->whereNotNull('interview_date')->count();
        $interviewByCompanies = $clientCandidates->whereNotNull('interview_date')->values();
        $hiredByCompanies = $clientCandidates->whereNotNull('start_date')->values();
        $firedByCompanies = $clientCandidates->whereNotNull('fire_date')->values();
        $totalHired = $clientCandidates->whereNotNull('start_date')->count();
        $totalFired = $clientCandidates->whereNotNull('fire_date')->count();


        return ['data' =>
            array(
                'total' => $total,
                'items' => $items,
                'total_interview_date' => $totalInterviewDateCount,
                'interview_by_companies' => $interviewByCompanies,
                'total_hired' => $totalHired,
                'hired_by_companies' => $hiredByCompanies,
                'total_fired' => $totalFired,
                'fired_by_companies' => $firedByCompanies
            )];
    }

    public function setDestroy($id)
    {
        $result = false;

        $user = User::withoutGlobalScope('active')->findOrFail($id);

        if ($user->id != 1) {
            $universalSearches = UniversalSearch::where('searchable_id', $id)->where('module_type', 'employee')->get();

            if ($universalSearches)
                foreach ($universalSearches as $universalSearch)
                    UniversalSearch::destroy($universalSearch->id);

            $emplDetails = EmployeeDetails::where('user_id', $id)->first();

            if ($emplDetails) {

                $correspondence = EmployeeHrCorrespondence::where('employee_details_id', $emplDetails->id)->first();
                if ($correspondence && !$correspondence->hr_detail_id) {
                    $correspondence->delete();
                }

                EmployeeDetails::destroy($emplDetails->id);
            }
            $user->delete();

            $result = true;
        }
        return $result;
    }

    public function employeeDepartments($employee, $request)
    {

        $employee_departments = json_decode($request->employee_departments);

        if (!$employee_departments)
            return;
        foreach ($employee_departments as $employee_department) {

            $this->employDepartmentSingle($employee_department, $employee);
        }
    }

    public function employDepartmentSingle($employee_department, $employee)
    {
        if (isset($employee_department->employee_department_id) && $employee_department->employee_department_id)
            $employeeDepartment = EmployeeTeam::findOrFail($employee_department->employee_department_id);
        else
            $employeeDepartment = new EmployeeTeam();
        $employeeDepartment->team_id = $employee_department->department_id;
        $employeeDepartment->user_id = $employee->user_id;
        $employeeDepartment->save();

        $employeeDepartment->positions()->sync($employee_department->positions);
    }

    public function detachLanguages($request)
    {
        $data = EmployeeLanguages::findOrFail($request->empllangId);
        if (!$data)
            throw new Exception('No EmployeeLanguages data by id ' . $request->empllangId);
        $data->delete();
        return;
    }

    public function detachEmployeeDepartment($request)
    {
        $data = EmployeeTeam::findOrFail($request->employee_department_id);
        if (!$data)
            throw new Exception('No Employee Department data by id ' . $request->empllangId);
        $data->delete();
        return;
    }

    public function detachPosition($request)
    {
        $userId = $request->userId;
        $positionId = $request->positionId;

        $user = User::withoutGlobalScopes(['active'])->findOrFail($userId);
        $position = Position::findOrFail($positionId);
        $skills = $position->skills->pluck('id')->toArray();

        foreach ($skills as $skill) {
            $emplSkill = EmployeeSkill::where('user_id', $userId)->where('skill_id', $skill)->first();
            if ($emplSkill)
                EmployeeSkill::destroy($emplSkill->id);
        }

        $user->positions()->detach($position);
        return;
    }

    public function changeStatusEmployees($request, $data)
    {
        $this->data = $data;
        $data = $request->toArray();
        $data['user_id'] = $request->id;

        $this->employeesRepository->updateUserProfile($data);
        $statusesDidntStart = EmployeeStatus::where("name", DefaultConfig::getStatusesDidntStart())->first();

        if ($statusesDidntStart->id == $request->statusEmpl) {
            $empl = EmployeeDetails::where("user_id", $data['user_id'])->first();
            $data['last_date'] = $empl->joining_date;
        }
        $employee = $this->employeesRepository->updateDTO(null, $data['user_id'], EmployeesDetailsDTo::getArray($data));


        if ($employee->candidate) {
            $tempInfo = $this->employeesRepository->getArrayForUpdateHrDetails($data);
            $tempInfo['date_format'] = company_setting()->date_format;

            $hrOr = $this->hrRepository->find($employee->candidate->id);
            $hr = $this->hrRepository->updateDTO($employee->candidate->id, HrDTo::getArray($tempInfo));

            if ($hr->wasChanged())
                $this->hrActivity($hrOr->getOriginal(), $hr->getChanges());

        }
        return;
    }

    public function changeStatusModal($employeeID, $statusID, $data)
    {
        $this->data = $data;

        $this->employeesId = $employeeID;
        $this->statusId = $statusID;
        $this->employeeStatuses = EmployeeStatus::getAllShort()->get();
        $this->employee = EmployeeDetails::where('user_id', '=', $this->employeesId)->first();

        return $this->data;
    }

    public function changeStatusEmployeesUpdate($request, $data)
    {
        $this->data = $data;

        $data = $request->toArray();
        $data['user_id'] = $request->id;

//        dd($data);
//
//        $this->employeesRepository->updateUserProfile($data);
        $this->employeesRepository->updateDTO(null, $data['user_id'], EmployeesDetailsDTo::getArray($request->all()));


        $statusEmpl = EmployeeStatus::findOrFail($request->statusEmpl);
        $statusUser = "active";
        if (in_array($statusEmpl->name, DefaultConfig::getEmployeesStatusesDeactive()))
            $statusUser = 'deactive';
        $user = User::withoutGlobalScope('active')->findOrFail($request->id);
        $user->status = $statusUser;
        $user->save();

        $data['last_date'] =
            ($request->last_date && $request->last_date != '') ?
                Carbon::createFromFormat($this->global->date_format, $request->last_date)->format('Y-m-d') : null;
        $employeesResultUpdate = $this->employeesRepository->update(null, $data['user_id'], $data);
        $employee = $this->employeesRepository->find(
            $employeesResultUpdate['data']['original']['id'] ?? $employeesResultUpdate['data']['changed']['id']
        );

        if ($employee->candidate) {
            $tempArray = $this->employeesRepository->getArrayForUpdateHrDetails($data);
            $tempArray['date_format'] = $this->global->date_format;
            $hrResultUpdate = $this->hrRepository->update($employee->candidate->id, $tempArray);
            if ($hrResultUpdate['status'] == true)
                $this->hrActivity($hrResultUpdate['data']['original'], $hrResultUpdate['data']['changed']);
        }

        return $this->data;
    }

    public function getTasks($userId, $hideCompleted)
    {
        $taskBoardColumn = TaskboardColumn::where('slug', 'incomplete')->first();

        $tasks = Task::join('task_users', 'task_users.task_id', '=', 'tasks.id')
            ->leftJoin('projects', 'projects.id', '=', 'tasks.project_id')
            ->join('taskboard_columns', 'taskboard_columns.id', '=', 'tasks.board_column_id')
            ->select('tasks.id', 'projects.project_name', 'tasks.heading', 'tasks.due_date', 'tasks.status', 'tasks.project_id', 'taskboard_columns.column_name', 'taskboard_columns.label_color')
            ->where('task_users.user_id', $userId);

        if ($hideCompleted == '1')
            $tasks->where('tasks.board_column_id', $taskBoardColumn->id);

        return $tasks;
    }

    public function getTimeLogs($userId)
    {
        $timeLogs = ProjectTimeLog::join('projects', 'projects.id', '=', 'project_time_logs.project_id')
            ->select('project_time_logs.id', 'projects.project_name', 'project_time_logs.start_time', 'project_time_logs.end_time', 'project_time_logs.total_hours', 'project_time_logs.memo', 'project_time_logs.project_id', 'project_time_logs.total_minutes')
            ->where('project_time_logs.user_id', $userId);
        $timeLogs->get();

        return $timeLogs;
    }

    public function assignRole($request)
    {

        $userId = $request->userId;
        $roleId = $request->role;
        $employeeRole = Role::where('name', 'employee')->first();
        $user = User::withoutGlobalScope('active')->findOrFail($userId);

        RoleUser::where('user_id', $user->id)->delete();
        $user->roles()->attach($employeeRole->id);

        if ($employeeRole->id != $roleId)
            $user->roles()->attach($roleId);

        $employeeRoleLG = Role::whereIn('name', Tools::getRoleLgManager())->get();
        $employeeRoleAdmin = Role::where('name', 'admin')->first();

        if (count($employeeRoleLG) > 0 && in_array($roleId, $employeeRoleLG->pluck('id')->toArray()) || $employeeRoleAdmin->id == $roleId) {
            LeadAgent::updateOrCreate([
                'user_id' => $userId
            ]);
        }
        return;
    }

    public function assignProjectAdmin($request)
    {
        $userId = $request->userId;
        $projectId = $request->projectId;
        $project = Project::findOrFail($projectId);
        $project->project_admin = $userId;
        $project->save();

        return;
    }

    public function setUpdateEmployeesByRequest($request)
    {
        $data = $request->toArray();
        $resultUpdateEmployee = $this->employeesRepository->update(null, $request->user_id, $data);

        $this->employeesHrVideoInterviewServices->updateForTask($resultUpdateEmployee['data']['original']['id'], $request);
        return;
    }

    public function getAllCorrespondencesByEmployee($userId, $controller)
    {
//        $controller->employee = EmployeeDetails::with("correspondences.createdBy")->where("user_id", $userId)->first();
//        $controller->employee = EmployeeDetails::with("correspondences.createdBy")->where("user_id", $userId)->first();

        $controller->employee = User::with([
            'employeePortfolios',
            'positions',
            'leadAccount',
            'leadAccount.user' => function ($query) {
                $query->withoutGlobalScope('active');
            },
            'employeeSkills.skill',

            'employeeLanguages.language',
            'employeeLanguages.langLevel',
            'employeeDetail.designation',
            'employeeDetail.department',
            'employeeDetail.position',
            'employeeDetail.office',
            'employeeDetail.emplstatus',
//            'employeeDetail.empaddress',
            'employeeDetail.empaddress.country',
            'employeeDetail.empaddress.city',
            'employeeDetail.candidate.hrManager',
            'employeeDetail.teamLead',
            'employeeDetail.videoEditing',
            'employeeDetail.currency',
            'employeeDetail.correspondences.createdBy',
            'educations.tag',
            'educations.degree',
            'educations',
            'workExperiences',
            'workExperiences.tags',
            'employeeDepartments.department',
            'employeeDepartments.positions',

        ])->withoutGlobalScope('active')->findOrFail($userId);
        $controller->employeeDetail = EmployeeDetails::with("correspondences.createdBy")->where('user_id', '=', $controller->employee->id)->first();

        $controller->employeeDocs = EmployeeDocs::where('user_id', '=', $controller->employee->id)->get();

        if (!is_null($controller->employeeDetail)) {
            $controller->employeeDetail = $controller->employeeDetail->withCustomFields();
            $controller->fields = $controller->employeeDetail->getCustomFieldGroupsWithFields()->fields;
        }

        $completedTaskColumn = TaskboardColumn::where('slug', 'completed')->first();
        $controller->taskCompleted = Task::join('task_users', 'task_users.task_id', '=', 'tasks.id')
            ->where('task_users.user_id', $userId)
            ->where('tasks.board_column_id', $completedTaskColumn->id)
            ->count();

        $hoursLogged = ProjectTimeLog::where('user_id', $userId)->sum('total_minutes');

        $timeLog = intdiv($hoursLogged, 60) . ' hrs ';

        if (($hoursLogged % 60) > 0)
            $timeLog .= ($hoursLogged % 60) . ' mins';

        $controller->hoursLogged = $timeLog;

        $controller->activities = UserActivity::where('user_id', $userId)->orderBy('id', 'desc')->get();
        $controller->projects = Project::select('projects.id', 'projects.project_name', 'projects.deadline', 'projects.completion_percent')
            ->join('project_members', 'project_members.project_id', '=', 'projects.id')
            ->where('project_members.user_id', '=', $userId)
            ->get();
        $controller->leaves = Leave::byUser($userId);
        $controller->leavesCount = Leave::byUserCount($userId);

        $controller->leaveTypes = LeaveType::byUser($userId);
        $controller->allowedLeaves = LeaveType::sum('no_of_leaves');
    }

    public function storeCorrespondencesByEmployee($request, $controller)
    {
        $employee = EmployeeDetails::find($request->employee_details_id);

//        dd($request->employee_details_id);
        $employeeCorrespondence = new EmployeeHrCorrespondence();
        $employeeCorrespondence->employee_details_id = $request->employee_details_id;
        $employeeCorrespondence->value = $request->value;
        $employeeCorrespondence->created_by = $controller->user->id;
        $employeeCorrespondence->hr_detail_id = $employee && $employee->candidate ? $employee->candidate->id : null;
        $employeeCorrespondence->save();
    }

    public function changePrimaryPositionByEmployee($request)
    {
        $employee = EmployeeDetails::where("user_id", $request->user_id)->first();

        $employee->position_id = $request->position_id;
        $employee->save();
    }

    public function changeDepartmentByEmployee($request)
    {
        $employee = EmployeeDetails::where("user_id", $request->user_id)->first();

        $employee->department_id = $request->department_id;
        $employee->save();
    }

    public function changeEmployeeDepartment($request)
    {
        $employee = EmployeeDetails::where("user_id", $request->user_id)->first();

        $employee_department = json_decode($request->employee_department);

        $this->employDepartmentSingle($employee_department, $employee);
    }


    public function editLanguagesByUserModal($controller, $id)
    {
        $controller->employee = User::with([
            'employeeLanguages.language',
            'employeeLanguages.langLevel',

        ])->withoutGlobalScope('active')->findOrFail($id);

        $controller->languages = Languages::getAllShort()->get();
        $controller->langLevels = LangLevel::all();
    }

    public function setEditLanguagesByUserModal($controller, $request)
    {
        $this->employeesRepository->createOrUpdateEmployeesLanguages($request->all());
    }

    public function editSkillsByUserModal($controller, $id)
    {
        $controller->employee = User::with([
            'employeeSkills.skill',
        ])->withoutGlobalScope('active')->findOrFail($id);

        $controller->skills = Skill::getAllShort()->get();
    }

    public function setEditSkillsByUserModal($controller, $request)
    {
        $this->createOrUpdateEmployeeSkills($request);
    }

    public function createOrUpdateEmployeeSkills($request)
    {
        $result = false;
        $skills = json_decode($request->skills);
        if (isset($skills) &&
            $skills &&
            count($skills) > 0
        ) {
            foreach ($skills as $skill) {
                $userSkill = (isset($skill->skill_detail_id) && $skill->skill_detail_id) ?
                    EmployeeSkill::findOrFail($skill->skill_detail_id) : new EmployeeSkill();
                $userSkill->skill_id = $skill->skill_id;
                $userSkill->user_id = $request->user_id;
                $userSkill->level = isset($skill->level) && $skill->level ? $skill->level : 0;
                $userSkill->save();
            }
            $result = true;
        }
        return $result;
    }

    public function destroySkills($controller, $request)
    {
        EmployeeSkill::destroy($request->employee_skill_id);
    }

    public function checkShortName($controller, $request)
    {
        if ($request['user_id']) {
            $detailID = EmployeeDetails::where('user_id', $request['user_id'])->first();

            $request->validate([
                'short_name' => 'nullable|unique:employee_details,short_name,' . $detailID->id,
            ]);
        } else {
            $request->validate([
                'short_name' => 'nullable|unique:employee_details,short_name',
            ]);
        }

        return 'Short Name is available';
    }

    public function checkSlug($controller, $request)
    {
        if ($request['user_id']) {
            $detailID = EmployeeDetails::where('user_id', $request['user_id'])->first();

            $request->validate([
                'slug' => 'nullable|unique:employee_details,slug,' . $detailID->id,
            ]);
        } else {
            $request->validate([
                'slug' => 'nullable|unique:employee_details,slug',
            ]);
        }

        return 'Slug is available';
    }

    public function getUserReportsByDataRange($id, $request)
    {
        $employee = User::withoutGlobalScope('active')->findOrFail($id);

        $this->startDate = $request->startDate ? Tools::formatDate($request->startDate)->format('Y-m-d') : Carbon::now()->startOfMonth()->format('Y-m-d');
        $this->endDate = $request->endDate ? Tools::formatDate($request->endDate)->format('Y-m-d') : Carbon::now()->endOfMonth()->format('Y-m-d');

        $period = array_reverse(CarbonPeriod::create($this->startDate, $this->endDate)->toArray());
        $endDateWithToday = Carbon::parse($this->endDate)->addDay()->format('Y-m-d');

        $offsetString = Tools::getOffsetTimezone();

        $attendancePresences = DB::table('attendance_presences')
            ->select('request_time', 'answer_time', 'screenshot', 'screenshot_url')
            ->selectRaw("DATE_FORMAT(TIMEDIFF(answer_time, request_time), '%H:%i') as duration")
            ->selectRaw("IF(TIMESTAMPDIFF(MINUTE, request_time, answer_time) > 30 , 1 , 0) as is_overdue")
            ->selectRaw("DATE(CONVERT_TZ(request_time,'+00:00', '$offsetString')) as request_date")
            ->whereBetween('request_time', [$this->startDate, $endDateWithToday])
            ->where('user_id', $employee->id)
            ->orderBy('request_time')
            ->get();

        $attendancesDB = DB::table('attendances')->select([
            'attendances.report',
            'attendances.clock_out_time',
            'client_details.company_name'
        ])
            ->selectRaw("CAST(clock_out_time as date) as clock_out_date")
            ->where('attendances.user_id', $employee->id)
            ->whereBetween('attendances.clock_out_time', [$this->startDate, $endDateWithToday])
            ->whereNotNull('attendances.report')
            ->leftJoin('client_details', 'attendances.client_details_id', '=', 'client_details.id')
            ->get();

        $result = collect();

        foreach ($period as $date) {
            $toDateString = $date->toDateString();

            $attendances = $attendancesDB->where('clock_out_date', $toDateString);
            $attendancePresence = $attendancePresences->where('request_date', $toDateString)->values();

            $data = [
                'attendances' => $attendances->map(function ($item) {
                    return collect($item)->except(['clock_out_date']);
                })->values() ?: [],

                'screenshots' => $attendancePresence->map(function ($item) use ($id) {
                    if ($item->screenshot) {
                        $basePath = "screenshots/{$item->request_date}/$id/{$item->screenshot}";

                        if (Storage::disk('local')->exists($basePath)) {
                            $path = asset("user-uploads/$basePath");
                        }
                    }

                    $item->screen_path = $path ?? null;

                    return collect($item)->except(['request_date', 'screenshot']);
                })->values() ?: [],
            ];


            $result->put($toDateString, $data);
        }

        return $result;
    }

    public static function getDataFilterEmployees($controller)
    {
        $controller->employees = User::getEmployees()->orderBy('users.name')->get();
        $controller->departments = Team::getAllShort()->get();
        $controller->positions = Position::getAllShort()->get();
        $controller->designations = Designation::getAllShort()->get();
        $controller->employeeStatuses = EmployeeStatus::getAllShort()->get();
        $controller->departmentTypes = DefaultConfig::getTypesForDepartments();


        $controller->actives = DefaultConfig::getUserActivityStatues();

    }
}
