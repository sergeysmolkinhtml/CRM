<?php

namespace App\Http\Controllers\Admin;

use App\ClientNeededCandidateStatus;
use App\Company;
use App\EmailNotificationSetting;
use App\EmployeeStatus;
use App\Event;
use App\EventAttendee;
use App\GdprSetting;
use App\HrActivity;
use App\HrSource;
use App\HrStatusCandidate;
use App\LanguageSetting;
use App\LeadAgent;
use App\LeaveType;
use App\ModuleSetting;
use App\Notification;
use App\Notifications\EventInvite;
use App\Notifications\LicenseExpire;
use App\Package;
use App\PackageSetting;
use App\ProjectActivity;
use App\PushNotificationSetting;
use App\Role;
use App\StickyNote;
use App\Traits\FileSystemSettingTrait;
use App\UniversalSearch;
use App\UserActivity;
use App\UserChat;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\App;
use App\ThemeSetting;
use Illuminate\Support\Facades\Auth;
use App\GlobalSetting;
use App\TaskHistory;
use App\User;
use Illuminate\Support\Facades\Redirect;

class AdminBaseController extends Controller
{
    use FileSystemSettingTrait;

    /**
     * @var array
     */
    public $data = [];

    /**
     * @param $name
     * @param $value
     */
    public function __set($name, $value)
    {
        $this->data[$name] = $value;
    }

    /**
     * @param $name
     * @return mixed
     */
    public function __get($name)
    {
        return $this->data[$name];
    }

    /**
     * @param $name
     * @return bool
     */
    public function __isset($name)
    {
        return isset($this->data[$name]);
    }


    /**
     * UserBaseController constructor.
     */
    public function __construct()
    {
        parent::__construct();
        // Inject currently logged in user object into every view of user dashboard
        $this->middleware(function ($request, $next) {

            $this->global = $this->company = company_setting();

            $this->isRole = 'admin';

            $this->superadmin = global_settings();

            $this->pushSetting = push_setting();
            $this->companyName = $this->global->company_name;

            $this->adminTheme = admin_theme();
            $this->languageSettings = language_setting();

            App::setLocale($this->global->locale);
            Carbon::setLocale($this->global->locale);
            setlocale(LC_TIME, $this->global->locale . '_' . strtoupper($this->global->locale));
            $this->setFileSystemConfigs();


            $this->isClient = User::withoutGlobalScope(CompanyScope::class)
                ->join('role_user', 'role_user.user_id', '=', 'users.id')
                ->join('roles', 'roles.id', '=', 'role_user.role_id')
                ->select('users.id', 'users.name', 'users.email', 'users.created_at')
                ->where('roles.name', 'client')
                ->where('role_user.user_id', user()->id)
                ->where('users.company_id', user()->company_id)
                ->first();

            $this->user = user();
            $this->unreadNotificationCount = count($this->user->unreadNotifications);

            // For GDPR
            try {
                $this->gdpr = GdprSetting::first();

                if (!$this->gdpr) {
                    $gdpr = new GdprSetting();
                    $gdpr->company_id = Auth::user()->company_id;
                    $gdpr->save();

                    $this->gdpr = $gdpr;
                }
            } catch (\Exception $e) {
            }

            $company = $this->global;
            $expireOn = $company->licence_expire_on;
            $currentDate = Carbon::now();

            $packageSettingData = package_setting();
            $this->packageSetting = ($packageSettingData->status == 'active') ? $packageSettingData : null;

            if ((!is_null($expireOn) && $expireOn->lessThan($currentDate))) {

                $this->checkLicense($company);
            }

            $this->modules = $this->user->modules;

            $this->unreadMessageCount = UserChat::where('to', $this->user->id)->where('message_seen', 'no')->count();
            $this->unreadTicketCount = Notification::where('notifiable_id', $this->user->id)
                ->where('type', 'App\Notifications\NewTicket')
                ->whereNull('read_at')
                ->count();

            $this->unreadExpenseCount = Notification::where('notifiable_id', $this->user->id)
                ->where('type', 'App\Notifications\NewExpenseAdmin')
                ->whereNull('read_at')
                ->count();

            $this->stickyNotes = StickyNote::where('user_id', $this->user->id)
                ->orderBy('updated_at', 'desc')
                ->get();

            $this->worksuitePlugins = worksuite_plugins();

            if (config('filesystems.default') == 's3') {
                $this->url = "https://" . config('filesystems.disks.s3.bucket') . ".s3.amazonaws.com/";
            }

            $this->createLeavesType();
            $this->createRole();
            $this->deleteHrActivities();
            $this->createNewItems();

            return $next($request);
        });
    }

    public function logProjectActivity($projectId, $text)
    {
        $activity = new ProjectActivity();
        $activity->project_id = $projectId;
        $activity->activity = $text;
        $activity->save();
    }

    public function logUserActivity($userId, $text)
    {
        $activity = new UserActivity();
        $activity->user_id = $userId;
        $activity->activity = $text;
        $activity->save();
    }

    public function logSearchEntry($searchableId, $title, $route, $type)
    {
        $search = new UniversalSearch();
        $search->searchable_id = $searchableId;
        $search->title = $title;
        $search->route_name = $route;
        $search->module_type = $type;
        $search->save();
    }

    public function logTaskActivity($taskID, $userID, $text, $boardColumnId, $subTaskId = null)
    {
        $activity = new TaskHistory();
        $activity->task_id = $taskID;

        if (!is_null($subTaskId)) {
            $activity->sub_task_id = $subTaskId;
        }

        $activity->user_id = $userID;
        $activity->details = $text;
        $activity->board_column_id = $boardColumnId;
        $activity->save();
    }

    public function checkLicense($company)
    {
        $packageSettingData = PackageSetting::first();
        $packageSetting = ($packageSettingData->status == 'active') ? $packageSettingData : null;
        $packages = Package::all();

        $trialPackage = $packages->filter(function ($value, $key) {
            return $value->default == 'trial';
        })->first();

        $defaultPackage = $packages->filter(function ($value, $key) {
            return $value->default == 'yes';
        })->first();

        $otherPackage = $packages->filter(function ($value, $key) {
            return $value->default == 'no';
        })->first();

        if ($packageSetting && !is_null($trialPackage)) {
            $selectPackage = $trialPackage;
        } elseif ($defaultPackage)
            $selectPackage = $defaultPackage;
        else {
            $selectPackage = $otherPackage;
        }

        // Set default package for license expired companies.
        if ($selectPackage) {
            $currentPackage = $company->package;
            ModuleSetting::where('company_id', $company->id)->delete();

            $moduleInPackage = (array)json_decode($selectPackage->module_in_package);
            $clientModules = ['projects', 'tickets', 'invoices', 'estimates', 'events', 'tasks', 'messages', 'payments', 'contracts', 'notices'];
            if ($moduleInPackage) {
                foreach ($moduleInPackage as $module) {

                    if (in_array($module, $clientModules)) {
                        $moduleSetting = new ModuleSetting();
                        $moduleSetting->company_id = $company->id;
                        $moduleSetting->module_name = $module;
                        $moduleSetting->status = 'active';
                        $moduleSetting->type = 'client';
                        $moduleSetting->save();
                    }

                    $moduleSetting = new ModuleSetting();
                    $moduleSetting->company_id = $company->id;
                    $moduleSetting->module_name = $module;
                    $moduleSetting->status = 'active';
                    $moduleSetting->type = 'employee';
                    $moduleSetting->save();

                    $moduleSetting = new ModuleSetting();
                    $moduleSetting->company_id = $company->id;
                    $moduleSetting->module_name = $module;
                    $moduleSetting->status = 'active';
                    $moduleSetting->type = 'admin';
                    $moduleSetting->save();
                }
            }

            if ($currentPackage->default == 'trial' && !is_null($packageSetting) && !is_null($defaultPackage)) {
                $company->package_id = $defaultPackage->id;
                $company->licence_expire_on = null;
            } elseif ($packageSetting && !is_null($trialPackage)) {
                $company->package_id = $selectPackage->id;
                $noOfDays = (!is_null($packageSetting->no_of_days) && $packageSetting->no_of_days != 0) ? $packageSetting->no_of_days : 30;
                $company->licence_expire_on = Carbon::now()->addDays($noOfDays)->format('Y-m-d');
            } elseif (is_null($packageSetting) && !is_null($defaultPackage)) {
                $company->package_id = $defaultPackage->id;
                $company->licence_expire_on = null;
            }
            $company->status = 'license_expired';
            $company->save();

            if ($company->company_email) {
                $companyUser = auth()->user();
                $companyUser->notify(new LicenseExpire(($companyUser)));
            }
        }
    }

    public function storeEvent($name, $company_name, $where, $contacts, $comments, $start_date, $start_time, $color,
                               $url = null, $agentId = null, $candidatesId = null, $salesManager = null)
    {


        $dateInterview = Carbon::createFromFormat($this->global->date_format, $start_date)->format('Y-m-d') . ' '
            . Carbon::createFromFormat($this->global->time_format, $start_time)->format('H:i:s');
        $eventFollowUp = Event::where('event_name', __('modules.eventName.followUp') . " : $company_name ")->first();
        $eventCall = Event::where('event_name', __('modules.eventName.call') . " : $company_name ")->first();
        $eventEvent = Event::where('event_name', __('modules.eventName.event') . " : $company_name ")->first();
        $eventInterview = Event::where('event_name', __('modules.eventName.interview') . " : $company_name ")->get();

        $description = '';

        if ($agentId) {
            $lead_agent = LeadAgent::with(['user' => function ($query) {
                $query->withoutGlobalScopes(['active']);
            }])->findOrFail($agentId);

            $lead_agent_name = $lead_agent->user->name;
            $description .= "Lead Agent: $lead_agent_name\n";
        }
        if ($salesManager) {
            $sales_manager = User::withoutGlobalScopes(['active'])->findOrFail($salesManager);

            $sales_manager_name = $sales_manager->name;
            $description .= "Sales Manager: $sales_manager_name\n";
        }


        $description .= "Company name: $company_name\n";


        foreach ($contacts as $key => $client) {

            $description .= "Contact name: $client\n";
        }
        $description .= "Comments: \n" . $comments;

        if ($candidatesId) {
            $description .= "Candidates: \n";

            foreach ($candidatesId as $candItem) {
                $user = User::withoutGlobalScope('active')->findOrFail($candItem);

                $description .= "$user->name: \n";
            }
        }

        if (
            $eventFollowUp ||
            $eventCall ||
            $eventEvent ||
            $eventInterview
        ) {
            if ($eventFollowUp)
                Event::destroy($eventFollowUp->id);
            if ($eventCall)
                Event::destroy($eventCall->id);
            if ($eventEvent)
                Event::destroy($eventEvent->id);
            if (count($eventInterview) > 0) {

                foreach ($eventInterview as $event) {
                    if ($event->start_date_time == $dateInterview && $event->description == $description)
                        return;

                    if ($event->start_date_time == $dateInterview && $event->description != $description)
                        Event::destroy($event->id);
                }
            }

        }

        $event = new Event();
        $event->event_name = "$name : $company_name ";
        $event->where = $where;
        $event->url = $url;

        $event->description = $description;
        $event->start_date_time = Carbon::createFromFormat($this->global->date_format, $start_date)->format('Y-m-d') . ' '
            . Carbon::createFromFormat($this->global->time_format, $start_time)->format('H:i:s');
        $event->end_date_time = Carbon::createFromFormat($this->global->date_format, $start_date)->format('Y-m-d') . ' '
            . Carbon::createFromFormat($this->global->time_format, $start_time)->addHours()->format('H:i:s');


        $event->repeat = 'no';

        $event->repeat_every = 1;
        $event->repeat_cycles = null;
        $event->label_color = $color;
        $event->save();

        $roleSalesManager = User::getSalesManager()->get();

//        $usersIds = ($roleSalesManager && $roleSalesManager->roleuser) ? $roleSalesManager->roleuser->pluck('user_id')->toArray() : [];
        $sales_manager_ids = $roleSalesManager->pluck('user_id')->toArray();

//        $sales_manager_ids = ($roleSalesManager && $roleSalesManager->roleuser) ? $roleSalesManager->
//        roleuser->pluck('user_id')->toArray() : [];

        if ($agentId) {
            $lead_agent = LeadAgent::findOrFail($agentId);
            $sales_manager_ids[] = $lead_agent->user_id;
        }

        if ($candidatesId) {
            $sales_manager_ids = array_merge($sales_manager_ids, $candidatesId);
        }
        foreach ($sales_manager_ids as $userId) {
            EventAttendee::firstOrCreate(['user_id' => $userId, 'event_id' => $event->id]);
        }
        $attendees = User::whereIn('id', $sales_manager_ids)->get();
        \Illuminate\Support\Facades\Notification::send($attendees, new EventInvite($event));
    }

    public function createLeavesType()
    {

        $arrayLeavesType = [
            ['type_name' => '0.25'],
            ['type_name' => '0.5'],
            ['type_name' => '0.75'],
            ['type_name' => '1.25'],
            ['type_name' => '1.5'],
            ['type_name' => '1.75'],
            ['type_name' => '2'],
            ['type_name' => '2.25'],
            ['type_name' => '1'],
            ['type_name' => '2.5',]
        ];

        foreach ($arrayLeavesType as $data) {
            LeaveType::firstOrCreate($data);
        }
    }

    public function createRole()
    {
//        $roleTeamLead = Role::
//        firstOrCreate([
//            'name' => __('app.roleEmployee.hrManagerTeamLead'),
//            'display_name' => __('app.roleEmployee.hrManagerTeamLead'),
//        ]);
//
//        $roleTeamLead = Role::
//        firstOrCreate([
//            'name' => __('app.roleEmployee.leadGeneratorTeamLeads'),
//            'display_name' => __('app.roleEmployee.leadGeneratorTeamLeads'),
//        ]);
//        $roleHrManager = Role::
//        firstOrCreate([
//            'name' => __('app.roleEmployee.hrManager'),
//            'display_name' => __('app.roleEmployee.hrManager'),
//        ]);
//
//        $role = Role::firstOrCreate([
//            'name' => __('app.roleEmployee.accountant'),
//            'display_name' => __('app.roleEmployee.accountant')
//        ]);
//        $role = Role::firstOrCreate([
//            'name' => __('app.roleEmployee.teamLead'),
//            'display_name' => __('app.roleEmployee.teamLead')
//        ]);
//
//        $role = Role::firstOrCreate([
//            'name' => __('app.roleEmployee.salesManager'),
//            'display_name' => __('app.roleEmployee.salesManager')
//        ]);
//        $role = Role::firstOrCreate([
//            'name' => __('app.roleEmployee.accountManager'),
//            'display_name' => __('app.roleEmployee.accountManager')
//        ]);
//
//        $role = Role::firstOrCreate([
//            'name' => __('app.roleEmployee.videoEditor'),
//            'display_name' => __('app.roleEmployee.videoEditor')
//        ]);
    }

    public function deleteHrActivities()
    {
        $now = Carbon::now();
        $month = $now->format('m');
        $year = $now->format('Y');


        $activitiesIds = HrActivity::whereMonth('created_at', '<', $month)
            ->whereYear('created_at', '<', $year, 'or')
            ->pluck('id');

        HrActivity::destroy($activitiesIds);
    }

    public function createNewItems()
    {
        $arrayEmployeeStatus = [
            ['name' => __('modules.statusEmployees.hired')],
            ['name' => __('modules.statusEmployees.left')],
            ['name' => __('modules.statusEmployees.didntStart')],
        ];

        $hrSources = [
            ['name' => __('modules.hrSource.jooble')],
            ['name' => __('modules.hrSource.linkedIn')],

        ];



        HrStatusCandidate::firstOrCreate([
            'name' => __('modules.hrStatuses.interview'),
        ]);

        foreach ($arrayEmployeeStatus as $data) {
            EmployeeStatus::firstOrCreate($data);
        }

        foreach ($hrSources as $data) {
            HrSource::firstOrCreate($data);
        }

        $clientCandidateNeededStatus = [
            ['name' => 'Hunting'],
            ['name' => 'Candidate'],
            ['name' => 'Required'],
            ['name' => 'Closed'],
            ['name' => 'Hired'],

        ];

        foreach ($clientCandidateNeededStatus as $data) {
            ClientNeededCandidateStatus::firstOrCreate($data);
        }


    }
}
