<?php


namespace App\Traits;


use App\ClientMainCommunication;
use App\ClientStatus;
use App\ClientStatusCandidate;
use App\Country;
use App\Currency;
use App\HrExcelKnowledge;
use App\HrSalary;
use App\HrShift;
use App\HrSource;
use App\HrStatusAdmin;
use App\HrStatusCandidate;
use App\LeadAccount;
use App\LeadAgent;
use App\LeadCompanyClientJob;
use App\LeadCompanyIndustry;
use App\LeadCompanySize;
use App\LeadFacebookAccount;
use App\LeadSource;
use App\LeadStatus;
use App\Team;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

trait ActivityInTextTrait
{

    public function hrActivity($original = null, $changed = null, $created = false, $folder = "hr/activity/")
    {
        if (!\File::exists(public_path('user-uploads/' . $folder))) {
            \File::makeDirectory(public_path('user-uploads/' . $folder), 0775, true);
        }

        if ($created) {
            $nameFile = $folder . $original["id"] . '.txt';

            $exists = Storage::disk('local')->exists($nameFile);

            if (!$exists) {
                Storage::disk('local')->put($nameFile, '');
            }

            $description = '';
            $date = Carbon::now()->timezone($this->global->timezone)->format('d-m-Y H:i:s');
            $description .= "<strong class='font-bold' >$date: </strong>";

            $description .= " <strong class='font-semi-bold' > <a target='_blank' href=" . route('admin.employees.show', [$this->user->id]) . ">" . $this->user->name . "</a></strong><br>";

            $description .= "<span> Added Candidate</span>";

            Storage::prepend($nameFile, $description);
        }

        if ($original && $changed) {
//
            $nameFile = $folder . $original["id"] . '.txt';

            $exists = Storage::disk('local')->exists($nameFile);

            if (!$exists) {
                Storage::disk('local')->put($nameFile, '');
            }

            $description = '';
            $date = Carbon::now()->timezone($this->global->timezone)->format('d-m-Y H:i:s');
            $description .= "<strong class='font-bold' >$date: </strong>";

            $description .= " <strong class='font-semi-bold' > <a target='_blank' href=" . route('admin.employees.show', [$this->user->id]) . ">" . $this->user->name . "</a></strong><br>";


            if (array_key_exists('source_id', $changed)) {
                $originalSource = $original['source_id'] ? HrSource::findOrFail($original['source_id']) : '';
                $originalSource = $original['source_id'] ? $originalSource->name : '';

                $changedSource = $changed['source_id'] ? HrSource::findOrFail($changed['source_id']) : '';
                $changedSource = $changed['source_id'] ? $changedSource->name : '';

                $description .= "<span> Changed Source from $originalSource to $changedSource </span><br>";
            }

            if (array_key_exists('name_candidate', $changed)) {
                $originalName = $original['name_candidate'];
                $originalName = $originalName ?? '';

                $changedName = $changed['name_candidate'];

                $description .= "<span> Changed name candidate from $originalName to $changedName</span><br>";
            }

            if (array_key_exists('hr_manager', $changed)) {
                $originalHrManager = $original['hr_manager'] ? User::withoutGlobalScopes(['active'])->findOrFail($original['hr_manager']) : '';
                $originalHrManager = $original['hr_manager'] ? $originalHrManager->name : '';

                $changedHrManager = $changed['hr_manager'] ? User::withoutGlobalScopes(['active'])->findOrFail($changed['hr_manager']) : '';
                $changedHrManager = $changed['hr_manager'] ? $changedHrManager->name : '';
                $description .= "<span> Changed HR manager from $originalHrManager to $changedHrManager</span><br>";
            }

            if (array_key_exists('courses', $changed)) {
                $originalCourses = $original['courses'];
                $originalCourses = $originalCourses ?? '';

                $changedCourses = $changed['courses'];
                $changedCourses = $changedCourses ?? '';

                if ($originalCourses && $changedCourses) {
                    $description .= "<span> Changed Courses from $originalCourses to $changedCourses</span><br>";
                } elseif ($originalCourses && !$changedCourses) {
                    $description .= "<span> Removed Courses $originalCourses </span><br>";
                } elseif (!$originalCourses && $changedCourses) {
                    $description .= "<span> Added Courses $changedCourses </span><br>";
                }
            }

            if (array_key_exists('video_url', $changed)) {
                $originalVideoUrl = $original['video_url'];
                $originalVideoUrl = $originalVideoUrl ?? '';

                $changedVideoUrl = $changed['video_url'];
                $changedVideoUrl = $changedVideoUrl ?? '';

                if ($originalVideoUrl && $changedVideoUrl) {
                    $description .= "<span> Changed Video Url from $originalVideoUrl to $changedVideoUrl</span><br>";
                } elseif ($originalVideoUrl && !$changedVideoUrl) {
                    $description .= "<span> Removed Video Url $originalVideoUrl </span><br>";
                } elseif (!$originalVideoUrl && $changedVideoUrl) {
                    $description .= "<span> Added Video Url $changedVideoUrl </span><br>";
                }
            }

            if (array_key_exists('age', $changed)) {
                $originalAge = $original['age'];
                $originalAge = $originalAge ?? '';

                $changedAge = $changed['age'];
                $changedAge = $changedAge ?? '';

                if ($originalAge && $changedAge) {
                    $description .= "<span> Changed Age from $originalAge to $changedAge</span><br>";
                } elseif ($originalAge && !$changedAge) {
                    $description .= "<span> Removed Age $originalAge </span><br>";
                } elseif (!$originalAge && $changedAge) {
                    $description .= "<span> Added Age $changedAge </span><br>";
                }
            }

            if (array_key_exists('shift_id', $changed)) {

                $originalShift = $original['shift_id'] ? HrShift::findOrFail($original['shift_id']) : '';
                $originalShift = $originalShift ? $originalShift->name : '';

                $changedShift = $changed['shift_id'] ? HrShift::findOrFail($changed['shift_id']) : '';
                $changedShift = $changedShift ? $changedShift->name : '';

                if ($originalShift && $changedShift) {
                    $description .= "<span> Changed Shift from $originalShift to $changedShift</span><br>";
                } elseif ($originalShift && !$changedShift) {
                    $description .= "<span> Removed Shift $originalShift </span><br>";
                } elseif (!$originalShift && $changedShift) {
                    $description .= "<span> Added Shift $changedShift </span><br>";
                }
            }

            if (array_key_exists('status_candidate_id', $changed)) {

                $originalStatusCandidate = $original['status_candidate_id'] ? HrStatusCandidate::findOrFail($original['status_candidate_id']) : '';
                $originalStatusCandidate = $originalStatusCandidate ? $originalStatusCandidate->name : '';

                $changedStatusCandidate = $changed['status_candidate_id'] ? HrStatusCandidate::findOrFail($changed['status_candidate_id']) : '';
                $changedStatusCandidate = $changedStatusCandidate ? $changedStatusCandidate->name : '';

                if ($originalStatusCandidate && $changedStatusCandidate) {
                    $description .= "<span> Changed Status Candidate from $originalStatusCandidate to $changedStatusCandidate</span><br>";
                } elseif ($originalStatusCandidate && !$changedStatusCandidate) {
                    $description .= "<span> Removed Status Candidate $originalStatusCandidate </span><br>";
                } elseif (!$originalStatusCandidate && $changedStatusCandidate) {
                    $description .= "<span> Added Status Candidate $changedStatusCandidate </span><br>";
                }

            }

            if (array_key_exists('expected_salary_id', $changed)) {

                $originalExpectedSalary = $original['expected_salary_id'] ? HrSalary::findOrFail($original['expected_salary_id']) : '';
                $originalExpectedSalary = $originalExpectedSalary ? $originalExpectedSalary->name : '';

                $changedExpectedSalary = $changed['expected_salary_id'] ? HrSalary::findOrFail($changed['expected_salary_id']) : '';
                $changedExpectedSalary = $changedExpectedSalary ? $changedExpectedSalary->name : '';

                if ($originalExpectedSalary && $changedExpectedSalary) {
                    $description .= "<span> Changed Expected Salary from $originalExpectedSalary to $changedExpectedSalary</span><br>";
                } elseif ($originalExpectedSalary && !$changedExpectedSalary) {
                    $description .= "<span> Removed Expected Salary $originalExpectedSalary </span><br>";
                } elseif (!$originalExpectedSalary && $changedExpectedSalary) {
                    $description .= "<span> Added Expected Salary $changedExpectedSalary </span><br>";
                }

            }

            if (array_key_exists('currency_id', $changed)) {

                $originalCurrency = $original['currency_id'] ? Currency::findOrFail($original['currency_id']) : '';
                $originalCurrency = $originalCurrency ? $originalCurrency->name : '';

                $changedCurrency = $changed['currency_id'] ? Currency::findOrFail($changed['currency_id']) : '';
                $changedCurrency = $changedCurrency ? $changedCurrency->name : '';

                if ($originalCurrency && $changedCurrency) {
                    $description .= "<span> Changed Currency from $originalCurrency to $changedCurrency</span><br>";
                } elseif ($originalCurrency && !$changedCurrency) {
                    $description .= "<span> Removed Currency $originalCurrency </span><br>";
                } elseif (!$originalCurrency && $changedCurrency) {
                    $description .= "<span> Added Currency $changedCurrency </span><br>";
                }

            }

            if (array_key_exists('start_salary_id', $changed)) {

                $originalStartSalary = $original['start_salary_id'] ? HrSalary::findOrFail($original['start_salary_id']) : '';
                $originalStartSalary = $originalStartSalary ? $originalStartSalary->name : '';

                $changedStartSalary = $changed['start_salary_id'] ? HrSalary::findOrFail($changed['start_salary_id']) : '';
                $changedStartSalary = $changedStartSalary ? $changedStartSalary->name : '';

                if ($originalStartSalary && $changedStartSalary) {
                    $description .= "<span> Changed Start Salary from $originalStartSalary to $changedStartSalary</span><br>";
                } elseif ($originalStartSalary && !$changedStartSalary) {
                    $description .= "<span> Removed Start Salary $originalStartSalary </span><br>";
                } elseif (!$originalStartSalary && $changedStartSalary) {
                    $description .= "<span> Added Start Salary $changedStartSalary </span><br>";
                }

            }

            if (array_key_exists('note', $changed)) {

                $originalComment = $original['note'];
                $originalComment = $originalComment ?? '';

                $changedComment = $changed['note'];
                $changedComment = $changedComment ?? '';

                if ($originalComment && $changedComment) {
                    $description .= "<span> Changed Comment from $originalComment to $changedComment</span><br>";
                } elseif ($originalComment && !$changedComment) {
                    $description .= "<span> Removed Comment $originalComment </span><br>";
                } elseif (!$originalComment && $changedComment) {
                    $description .= "<span> Added Comment $changedComment </span><br>";
                }

            }
            if (array_key_exists('note_admin', $changed)) {

                $originalCommentsAdmin = $original['note_admin'];
                $originalCommentsAdmin = $originalCommentsAdmin ?? '';

                $changedCommentsAdmin = $changed['note_admin'];
                $changedCommentsAdmin = $changedCommentsAdmin ?? '';

                if ($originalCommentsAdmin && $changedCommentsAdmin) {
                    $description .= "<span> Changed Comment Admins from $originalCommentsAdmin to $changedCommentsAdmin</span><br>";
                } elseif ($originalCommentsAdmin && !$changedCommentsAdmin) {
                    $description .= "<span> Removed Comment Admins $originalCommentsAdmin </span><br>";
                } elseif (!$originalCommentsAdmin && $changedCommentsAdmin) {
                    $description .= "<span> Added Comment Admins $changedCommentsAdmin </span><br>";
                }

            }

            if (array_key_exists('status_admin_id', $changed)) {

                $originalStatusAdmin = $original['status_admin_id'] ? HrStatusAdmin::findOrFail($original['status_admin_id']) : '';
                $originalStatusAdmin = $originalStatusAdmin ? $originalStatusAdmin->name : '';

                $changedStatusAdmin = $changed['status_admin_id'] ? HrStatusAdmin::findOrFail($changed['status_admin_id']) : '';
                $changedStatusAdmin = $changedStatusAdmin ? $changedStatusAdmin->name : '';

                if ($originalStatusAdmin && $changedStatusAdmin) {
                    $description .= "<span> Changed Status Admin from $originalStatusAdmin to $changedStatusAdmin</span><br>";
                } elseif ($originalStatusAdmin && !$changedStatusAdmin) {
                    $description .= "<span> Removed Status Admin $originalStatusAdmin </span><br>";
                } elseif (!$originalStatusAdmin && $changedStatusAdmin) {
                    $description .= "<span> Added Status Admin $changedStatusAdmin </span><br>";
                }

            }

            if (array_key_exists('phone', $changed)) {

                $originalPhone = $original['phone'];
                $originalPhone = $originalPhone ?? '';

                $changedPhone = $changed['phone'];
                $changedPhone = $changedPhone ?? '';

                if ($originalPhone && $changedPhone) {
                    $description .= "<span> Changed Phone from $originalPhone to $changedPhone</span><br>";
                } elseif ($originalPhone && !$changedPhone) {
                    $description .= "<span> Removed Phone $originalPhone </span><br>";
                } elseif (!$originalPhone && $changedPhone) {
                    $description .= "<span> Added Phone $changedPhone </span><br>";
                }

            }
            if (array_key_exists('email', $changed)) {

                $originalEmail = $original['email'];
                $originalEmail = $originalEmail ?? '';

                $changedEmail = $changed['email'];
                $changedEmail = $changedEmail ?? '';

                if ($originalEmail && $changedEmail) {
                    $description .= "<span> Changed Email from $originalEmail to $changedEmail</span><br>";
                } elseif ($originalEmail && !$changedEmail) {
                    $description .= "<span> Removed Email $originalEmail </span><br>";
                } elseif (!$originalEmail && $changedEmail) {
                    $description .= "<span> Added Email $changedEmail </span><br>";
                }

            }
            if (array_key_exists('viber', $changed)) {

                $originalViber = $original['viber'];
                $originalViber = $originalViber ?? '';

                $changedViber = $changed['viber'];
                $changedViber = $changedViber ?? '';

                if ($originalViber && $changedViber) {
                    $description .= "<span> Changed Viber from $originalViber to $changedViber</span><br>";
                } elseif ($originalViber && !$changedViber) {
                    $description .= "<span> Removed Viber $originalViber </span><br>";
                } elseif (!$originalViber && $changedViber) {
                    $description .= "<span> Added Viber $changedViber </span><br>";
                }

            }

            if (array_key_exists('last_contact_date', $changed)) {

                $originalLastContactDate = $original['last_contact_date'] ?? '';
                $changedLastContactDate = $changed['last_contact_date'] ?? '';
                $textLastContactDate = 'Last Contact Date';

                if ($originalLastContactDate && $changedLastContactDate) {
                    $description .= "<span> Changed $textLastContactDate from $originalLastContactDate to $changedLastContactDate</span><br>";
                } elseif ($originalLastContactDate && !$changedLastContactDate) {
                    $description .= "<span> Removed $textLastContactDate $originalLastContactDate </span><br>";
                } elseif (!$originalLastContactDate && $changedLastContactDate) {
                    $description .= "<span> Added $textLastContactDate $changedLastContactDate </span><br>";
                }

            }


            if (array_key_exists('instagram', $changed)) {

                $originalInstagram = $original['instagram'];
                $originalInstagram = $originalInstagram ?? '';

                $changedInstagram = $changed['instagram'];
                $changedInstagram = $changedInstagram ?? '';

                if ($originalInstagram && $changedInstagram) {
                    $description .= "<span> Changed Instagram from $originalInstagram to $changedInstagram</span><br>";
                } elseif ($originalInstagram && !$changedInstagram) {
                    $description .= "<span> Removed Instagram $originalInstagram </span><br>";
                } elseif (!$originalInstagram && $changedInstagram) {
                    $description .= "<span> Added Instagram $changedInstagram </span><br>";
                }

            }
            if (array_key_exists('photo_url', $changed)) {

                $originalPhotoUrl = $original['photo_url'];
                $originalPhotoUrl = $originalPhotoUrl ?? '';

                $changedPhotoUrl = $changed['photo_url'];
                $changedPhotoUrl = $changedPhotoUrl ?? '';

                if ($originalPhotoUrl && $changedPhotoUrl) {
                    $description .= "<span> Changed Photo Url from $originalPhotoUrl to $changedPhotoUrl</span><br>";
                } elseif ($originalPhotoUrl && !$changedPhotoUrl) {
                    $description .= "<span> Removed Photo Url $originalPhotoUrl </span><br>";
                } elseif (!$originalPhotoUrl && $changedPhotoUrl) {
                    $description .= "<span> Added Photo Url $changedPhotoUrl </span><br>";
                }

            }
            if (array_key_exists('resume_url', $changed)) {

                $originalResumeUrl = $original['resume_url'];
                $originalResumeUrl = $originalResumeUrl ?? '';

                $changedResumeUrl = $changed['resume_url'];
                $changedResumeUrl = $changedResumeUrl ?? '';

                if ($originalResumeUrl && $changedResumeUrl) {
                    $description .= "<span> Changed Resume Url from $originalResumeUrl to $changedResumeUrl</span><br>";
                } elseif ($originalResumeUrl && !$changedResumeUrl) {
                    $description .= "<span> Removed Resume Url $originalResumeUrl </span><br>";
                } elseif (!$originalResumeUrl && $changedResumeUrl) {
                    $description .= "<span> Added Resume Url $changedResumeUrl </span><br>";
                }

            }
            if (array_key_exists('test_link', $changed)) {

                $originalTestLink = $original['test_link'];
                $originalTestLink = $originalTestLink ?? '';

                $changedTestLink = $changed['test_link'];
                $changedTestLink = $changedTestLink ?? '';

                if ($originalTestLink && $changedTestLink) {
                    $description .= "<span> Changed Test Url from $originalTestLink to $changedTestLink</span><br>";
                } elseif ($originalTestLink && !$changedTestLink) {
                    $description .= "<span> Removed Test Url $originalTestLink </span><br>";
                } elseif (!$originalTestLink && $changedTestLink) {
                    $description .= "<span> Added Test Url $changedTestLink </span><br>";
                }

            }
            if (array_key_exists('start_date', $changed)) {

                $originalStartDate = $original['start_date'];
                $originalStartDate = $originalStartDate ? Carbon::parse($original['start_date'])->timezone($this->global->timezone)->format('d-m-Y') : '';

                $changedStartDate = $changed['start_date'];
                $changedStartDate = $changedStartDate ? Carbon::parse($changed['start_date'])->timezone($this->global->timezone)->format('d-m-Y') : '';

                if ($originalStartDate && $changedStartDate) {
                    $description .= "<span> Changed Start Date from $originalStartDate to $changedStartDate</span><br>";
                } elseif ($originalStartDate && !$changedStartDate) {
                    $description .= "<span> Removed Start Date $originalStartDate </span><br>";
                } elseif (!$originalStartDate && $changedStartDate) {
                    $description .= "<span> Added Start Date $changedStartDate </span><br>";
                }
            }

            if (array_key_exists('start_time', $changed)) {

                $originalStartTime = $original['start_time'];
                $originalStartTime = $originalStartTime ? Carbon::parse($original['start_time'])->format('H:i:s') : '';

                $changedStartTime = $changed['start_time'];
                $changedStartTime = $changedStartTime ? Carbon::parse($changed['start_time'])->format('H:i:s') : '';

                if ($originalStartTime && $changedStartTime) {
                    $description .= "<span> Changed Start Time from $originalStartTime to $changedStartTime</span><br>";
                } elseif ($originalStartTime && !$changedStartTime) {
                    $description .= "<span> Removed Start Time $originalStartTime </span><br>";
                } elseif (!$originalStartTime && $changedStartTime) {
                    $description .= "<span> Added Start Time $changedStartTime </span><br>";
                }
            }

            if (array_key_exists('end_date', $changed)) {

                $originalEndDate = $original['end_date'];
                $originalEndDate = $originalEndDate ? Carbon::parse($original['end_date'])->timezone($this->global->timezone)->format('d-m-Y') : '';

                $changedEndDate = $changed['end_date'];
                $changedEndDate = $changedEndDate ? Carbon::parse($changed['end_date'])->timezone($this->global->timezone)->format('d-m-Y') : '';

                if ($originalEndDate && $changedEndDate) {
                    $description .= "<span> Changed End Date from $originalEndDate to $changedEndDate</span><br>";
                } elseif ($originalEndDate && !$changedEndDate) {
                    $description .= "<span> Removed End Date $originalEndDate </span><br>";
                } elseif (!$originalEndDate && $changedEndDate) {
                    $description .= "<span> Added End Date $changedEndDate </span><br>";
                }

            }
            if (array_key_exists('portfolio_url', $changed)) {

                $originalPortfolioUrl = $original['portfolio_url'];
                $originalPortfolioUrl = $originalPortfolioUrl ?? '';

                $changedPortfolioUrl = $changed['portfolio_url'];
                $changedPortfolioUrl = $changedPortfolioUrl ?? '';

                if ($originalPortfolioUrl && $changedPortfolioUrl) {
                    $description .= "<span> Changed Portfolio Url from $originalPortfolioUrl to $changedPortfolioUrl</span><br>";
                } elseif ($originalPortfolioUrl && !$changedPortfolioUrl) {
                    $description .= "<span> Removed Portfolio Url $originalPortfolioUrl </span><br>";
                } elseif (!$originalPortfolioUrl && $changedPortfolioUrl) {
                    $description .= "<span> Added Portfolio Url $changedPortfolioUrl </span><br>";
                }

            }
            if (array_key_exists('excel_knowledge_id', $changed)) {

                $originalExcelKnowledge = $original['excel_knowledge_id'] ? HrExcelKnowledge::findOrFail($original['excel_knowledge_id']) : '';
                $originalExcelKnowledge = $originalExcelKnowledge ? $originalExcelKnowledge->name : '';

                $changedExcelKnowledge = $changed['excel_knowledge_id'] ? HrExcelKnowledge::findOrFail($changed['excel_knowledge_id']) : '';
                $changedExcelKnowledge = $changedExcelKnowledge ? $changedExcelKnowledge->name : '';

                if ($originalExcelKnowledge && $changedExcelKnowledge) {
                    $description .= "<span> Changed Excel Knowledge from $originalExcelKnowledge to $changedExcelKnowledge</span><br>";
                } elseif ($originalExcelKnowledge && !$changedExcelKnowledge) {
                    $description .= "<span> Removed Excel Knowledge $originalExcelKnowledge </span><br>";
                } elseif (!$originalExcelKnowledge && $changedExcelKnowledge) {
                    $description .= "<span> Added Excel Knowledge $changedExcelKnowledge </span><br>";
                }

            }
            if (array_key_exists('team_lead', $changed)) {

                $originalTeamLead = $original['team_lead'];
                $originalTeamLead = $originalTeamLead ? "true" : "false";

                $changedTeamLead = $changed['team_lead'];
                $changedTeamLead = $changedTeamLead ? "true" : "false";


                $description .= "<span> Changed Team Lead from $originalTeamLead to $changedTeamLead</span><br>";

            }
            if (array_key_exists('telegram', $changed)) {

                $originalTelegram = $original['telegram'];
                $originalTelegram = $originalTelegram ?? '';

                $changedTelegram = $changed['telegram'];
                $changedTelegram = $changedTelegram ?? '';

                if ($originalTelegram && $changedTelegram) {
                    $description .= "<span> Changed Telegram from $originalTelegram to $changedTelegram</span><br>";
                } elseif ($originalTelegram && !$changedTelegram) {
                    $description .= "<span> Removed Telegram $originalTelegram </span><br>";
                } elseif (!$originalTelegram && $changedTelegram) {
                    $description .= "<span> Added Telegram $changedTelegram </span><br>";
                }

            }

            if (array_key_exists('resume_editor', $changed)) {

                $originalResumeEditor = $original['resume_editor'];
                $originalResumeEditor = $originalResumeEditor ?? '';

                $changedResumeEditor = $changed['resume_editor'];
                $changedResumeEditor = $changedResumeEditor ?? '';

                $description .= "<span> Changed Resume</span><br>";

            }

            if (array_key_exists('department_id', $changed)) {

                $originalDepartmentCandidate = $original['department_id'] ? Team::findOrFail($original['department_id']) : '';
                $originalDepartmentCandidate = $originalDepartmentCandidate ? $originalDepartmentCandidate->team_name : '';

                $changedDepartmentCandidate = $changed['department_id'] ? Team::findOrFail($changed['department_id']) : '';
                $changedDepartmentCandidate = $changedDepartmentCandidate ? $changedDepartmentCandidate->team_name : '';

                if ($originalDepartmentCandidate && $changedDepartmentCandidate) {
                    $description .= "<span> Changed Department Candidate from $originalDepartmentCandidate to $changedDepartmentCandidate</span><br>";
                } elseif ($originalDepartmentCandidate && !$changedDepartmentCandidate) {
                    $description .= "<span> Removed Department Candidate $originalDepartmentCandidate </span><br>";
                } elseif (!$originalDepartmentCandidate && $changedDepartmentCandidate) {
                    $description .= "<span> Added Department Candidate $changedDepartmentCandidate </span><br>";
                }

            }

            if (array_key_exists('team_lead_id', $changed)) {
                $originalTeamLeadId = $original['team_lead_id'] ? User::withoutGlobalScope('active')->findOrFail($original['team_lead_id']) : '';
                $originalTeamLeadId = $originalTeamLeadId ? $originalTeamLeadId->name : '';

                $changedTeamLeadId = $changed['team_lead_id'] ? User::withoutGlobalScope('active')->findOrFail($changed['team_lead_id']) : '';
                $changedTeamLeadId = $changedTeamLeadId ? $changedTeamLeadId->name : '';

                if ($originalTeamLeadId && $changedTeamLeadId) {
                    $description .= "<span> Changed Team Lead from $originalTeamLeadId to $changedTeamLeadId</span><br>";
                } elseif ($originalTeamLeadId && !$changedTeamLeadId) {
                    $description .= "<span> Removed Team Lead $originalTeamLeadId </span><br>";
                } elseif (!$originalTeamLeadId && $changedTeamLeadId) {
                    $description .= "<span> Added Team Lead $changedTeamLeadId </span><br>";
                }
            }

            if (array_key_exists('note_team_lead', $changed)) {
                $originalNoteTeamLead = $original['note_team_lead'] ?? '';
                $changedNoteTeamLead = $changed['note_team_lead'] ?? '';

                if ($originalNoteTeamLead && $changedNoteTeamLead) {
                    $description .= "<span> Changed Comment TeamLead from $originalNoteTeamLead to $changedNoteTeamLead</span><br>";
                } elseif ($originalNoteTeamLead && !$changedNoteTeamLead) {
                    $description .= "<span> Removed Comment TeamLead $originalNoteTeamLead </span><br>";
                } elseif (!$originalNoteTeamLead && $changedNoteTeamLead) {
                    $description .= "<span> Added Comment TeamLead $changedNoteTeamLead </span><br>";
                }
            }

            if (array_key_exists('approved_status_team_lead', $changed)) {
                $resultApprovedStatusTeamLead = ($changed['approved_status_team_lead'] == 0) ? 'Not Approved' : 'Approved';
                $description .= "<span> Changed Approved Status Team Lead $resultApprovedStatusTeamLead</span><br>";
            }

            Storage::prepend($nameFile, $description);

//            var_dump($original);
//            var_dump($changed);
////            var_dump("Левченко Святослав Контент");
//
//            var_dump($this->user->name);
        }//if

    }//hrActivity

    public function leadsActivity($original = null, $changed = null, $created = false, $folder = "leads/activity/")
    {
        if (!\File::exists(public_path('user-uploads/' . $folder))) {
            \File::makeDirectory(public_path('user-uploads/' . $folder), 0775, true);
        }
        $nameFile = $folder . $original["id"] . '.txt';
        $exists = Storage::disk('local')->exists($nameFile);
        if (!$exists) {
            Storage::disk('local')->put($nameFile, '');
        }

        if ($created) {
            $description = '';
            $date = Carbon::now()->timezone($this->global->timezone)->format('d-m-Y H:i:s');

            $description .= "<strong class='font-bold' >$date: </strong>";
            $description .= " <strong class='font-semi-bold' > <a target='_blank' href=" . route('admin.employees.show', [$this->user->id]) . ">" . $this->user->name . "</a></strong><br>";
            $description .= (isset($original['client_add']) && $original['client_add'] == true) ? "<span> Leads Change To Client</span>" : "<span> Added Leads</span>";

            Storage::prepend($nameFile, $description);
        }

        if ($original && $changed) {
            $description = '';
            $date = Carbon::now()->timezone($this->global->timezone)->format('d-m-Y H:i:s');
            $description .= "<strong class='font-bold' >$date: </strong>";
            $description .= "<strong class='font-semi-bold' > <a target='_blank' href=" . route('admin.employees.show', [$this->user->id]) . ">" . $this->user->name . "</a></strong><br>";

            if (array_key_exists('detach_contacts', $changed))
                $description .= "<span>Delete contacts " . $changed['detach_contacts'] . "</span><br>";

            if (array_key_exists('move_to_clients', $changed))
                $description .= "<span>Change To Client  " . $changed['move_to_clients'] . "</span><br>";

            if (array_key_exists('agent_id', $changed)) {
                $originalAgentId = LeadAgent::with(['user' => function ($query) {
                    $query->withoutGlobalScope('active');
                },])->find($original['agent_id']) ?? null;
                $originalAgentId = $originalAgentId ? $originalAgentId->user->name : '';

                $changedAgentId = LeadAgent::with(['user' => function ($query) {
                    $query->withoutGlobalScope('active');
                },])->find($changed['agent_id']) ?? null;
                $changedAgentId = $changedAgentId ? $changedAgentId->user->name : '';
                $description .= "<span> Changed LG Manager from $originalAgentId to $changedAgentId</span><br>";
            }

            if (array_key_exists('source_id', $changed)) {
                $originalSourceId = LeadSource::find($original['source_id']) ?? null;
                $originalSourceId = $originalSourceId ? $originalSourceId->type : '';

                $changedSourceId = LeadSource::find($changed['source_id']) ?? null;
                $changedSourceId = $changedSourceId ? $changedSourceId->type : '';
                $description .= "<span> Changed Lead Source from $originalSourceId to $changedSourceId</span><br>";
            }

            if (array_key_exists('status_id', $changed)) {
                $originalStatusId = LeadStatus::find($original['status_id']) ?? null;
                $originalStatusId = $originalStatusId ? $originalStatusId->type : '';

                $changedStatusId = LeadStatus::find($changed['status_id']) ?? null;
                $changedStatusId = $changedStatusId ? $changedStatusId->type : '';
                $description .= "<span> Changed Lead Status from $originalStatusId to $changedStatusId</span><br>";
            }

            if (array_key_exists('account_id', $changed)) {
                $originalAccountId = LeadAccount::find($original['account_id']) ?? null;
                $originalAccountId = $originalAccountId ? $originalAccountId->name : '';

                $changedAccountId = LeadAccount::find($changed['account_id']) ?? null;
                $changedAccountId = $changedAccountId ? $changedAccountId->name : '';
                $description .= "<span> Changed LinkedIn Accounts from $originalAccountId to $changedAccountId</span><br>";
            }

            if (array_key_exists('follow_up_date', $changed)) {
                $originalFollowUpDate = $original['follow_up_date'] ? Carbon::parse($original['follow_up_date'])->timezone($this->global->timezone)->format('d-m-Y') : '';
                $changedFollowUpDate = $changed['follow_up_date'] ? Carbon::parse($changed['follow_up_date'])->timezone($this->global->timezone)->format('d-m-Y') : '';

                if ($originalFollowUpDate && $changedFollowUpDate) {
                    $description .= "<span> Changed Follow Up Date from $originalFollowUpDate to $changedFollowUpDate</span><br>";
                } elseif ($originalFollowUpDate && !$changedFollowUpDate) {
                    $description .= "<span> Removed Follow Up Date $originalFollowUpDate </span><br>";
                } elseif (!$originalFollowUpDate && $changedFollowUpDate) {
                    $description .= "<span> Added Follow Up Date $changedFollowUpDate </span><br>";
                }
            }

            if (array_key_exists('call_date', $changed)) {
                $originalСallDate = $original['call_date'] ? Carbon::parse($original['call_date'])->timezone($this->global->timezone)->format('d-m-Y') : '';
                $changedСallDate = $changed['call_date'] ? Carbon::parse($changed['call_date'])->timezone($this->global->timezone)->format('d-m-Y') : '';

                if ($originalСallDate && $changedСallDate) {
                    $description .= "<span> Changed Event/Call Date from $originalСallDate to $changedСallDate</span><br>";
                } elseif ($originalСallDate && !$changedСallDate) {
                    $description .= "<span> Removed Event/Call Date $originalСallDate </span><br>";
                } elseif (!$originalСallDate && $changedСallDate) {
                    $description .= "<span> Added Event/Call Date $changedСallDate </span><br>";
                }
            }

            if (array_key_exists('time', $changed)) {
                $originalTime = $original['time'] ? Carbon::parse($original['time'])->timezone($this->global->timezone)->format('H:i:s') : '';
                $changedTime = $changed['time'] ? Carbon::parse($changed['time'])->timezone($this->global->timezone)->format('H:i:s') : '';

                if ($originalTime && $changedTime) {
                    $description .= "<span> Changed Time Event/Call from $originalTime to $changedTime</span><br>";
                } elseif ($originalTime && !$changedTime) {
                    $description .= "<span> Removed Time Event/Call $originalTime </span><br>";
                } elseif (!$originalTime && $changedTime) {
                    $description .= "<span> Added Time Event/Call $changedTime </span><br>";
                }
            }

            if (array_key_exists('company_name', $changed)) {
                $originalCompanyName = $original['company_name'];
                $originalCompanyName = $originalCompanyName ?? '';

                $changedCompanyName = $changed['company_name'];
                $changedCompanyName = $changedCompanyName ?? '';

                if ($originalCompanyName && $changedCompanyName) {
                    $description .= "<span> Changed Company Name from $originalCompanyName to $changedCompanyName</span><br>";
                } elseif ($originalCompanyName && !$changedCompanyName) {
                    $description .= "<span> Removed Company Name $originalCompanyName </span><br>";
                } elseif (!$originalCompanyName && $changedCompanyName) {
                    $description .= "<span> Added Company Name $changedCompanyName </span><br>";
                }
            }

            if (array_key_exists('website', $changed)) {
                $originalWebsite = $original['website'];
                $originalWebsite = $originalWebsite ?? '';

                $changedWebsite = $changed['website'];
                $changedWebsite = $changedWebsite ?? '';

                if ($originalWebsite && $changedWebsite) {
                    $description .= "<span> Changed Website from $originalWebsite to $changedWebsite</span><br>";
                } elseif ($originalWebsite && !$changedWebsite) {
                    $description .= "<span> Removed WebsiteWebsite $originalWebsite </span><br>";
                } elseif (!$originalWebsite && $changedWebsite) {
                    $description .= "<span> Added Website $changedWebsite </span><br>";
                }
            }

            if (array_key_exists('company_facebook', $changed)) {
                $originalCompanyFacebook = $original['company_facebook'];
                $originalCompanyFacebook = $originalCompanyFacebook ?? '';

                $changedCompanyFacebooke = $changed['company_facebook'];
                $changedCompanyFacebooke = $changedCompanyFacebooke ?? '';

                if ($originalCompanyFacebook && $changedCompanyFacebooke) {
                    $description .= "<span> Changed Company Facebook from $originalCompanyFacebook to $changedCompanyFacebooke</span><br>";
                } elseif ($originalCompanyFacebook && !$changedCompanyFacebooke) {
                    $description .= "<span> Removed Company Facebook $originalCompanyFacebook </span><br>";
                } elseif (!$originalCompanyFacebook && $changedCompanyFacebooke) {
                    $description .= "<span> Added Company Facebook $changedCompanyFacebooke </span><br>";
                }
            }

            if (array_key_exists('twitter', $changed)) {
                $originalTwitter = $original['twitter'];
                $originalTwitter = $originalTwitter ?? '';

                $changedTwitter = $changed['twitter'];
                $changedTwitter = $changedTwitter ?? '';

                if ($originalTwitter && $changedTwitter) {
                    $description .= "<span> Changed Company Twitter from $originalTwitter to $changedTwitter</span><br>";
                } elseif ($originalTwitter && !$changedTwitter) {
                    $description .= "<span> Removed Company Twitterk $originalCompanyFacebook </span><br>";
                } elseif (!$originalTwitter && $changedTwitter) {
                    $description .= "<span> Added Company Twitter $changedTwitter </span><br>";
                }
            }

            if (array_key_exists('company_linked', $changed)) {
                $originalCompanyLinked = $original['company_linked'];
                $originalCompanyLinked = $originalCompanyLinked ?? '';

                $changedCompanyLinked = $changed['company_linked'];
                $changedCompanyLinked = $changedCompanyLinked ?? '';

                if ($originalCompanyLinked && $changedCompanyLinked) {
                    $description .= "<span> Changed Company LinkedIn from $originalCompanyLinked to $changedCompanyLinked</span><br>";
                } elseif ($originalCompanyLinked && !$changedCompanyLinked) {
                    $description .= "<span> Removed Company LinkedIn $originalCompanyLinked </span><br>";
                } elseif (!$originalCompanyLinked && $changedCompanyLinked) {
                    $description .= "<span> Added Company LinkedIn $changedCompanyLinked </span><br>";
                }
            }

            if (array_key_exists('size_id', $changed)) {
                $originalSizeId = LeadCompanySize::find($original['size_id']) ?? null;
                $originalSizeId = $originalSizeId ? $originalSizeId->title : '';

                $changedSizeId = LeadCompanySize::find($changed['size_id']) ?? null;
                $changedSizeId = $changedSizeId ? $changedSizeId->title : '';
                $description .= "<span> Changed Company Size from $originalSizeId to $changedSizeId</span><br>";
            }

            if (array_key_exists('industry_id', $changed)) {
                $originalSizeId = LeadCompanyIndustry::find($original['industry_id']) ?? null;
                $originalSizeId = $originalSizeId ? $originalSizeId->title : '';

                $changedSizeId = LeadCompanyIndustry::find($changed['industry_id']) ?? null;
                $changedSizeId = $changedSizeId ? $changedSizeId->title : '';
                $description .= "<span> Changed Company Industry from $originalSizeId to $changedSizeId</span><br>";
            }

            if (array_key_exists('countries_id', $changed)) {
                $originalСountriesId = Country::find($original['countries_id']) ?? null;
                $originalСountriesId = $originalСountriesId ? $originalСountriesId->name : '';

                $changedСountriesId = Country::find($changed['countries_id']) ?? null;
                $changedСountriesId = $changedСountriesId ? $changedСountriesId->name : '';
                $description .= "<span> Changed Country from $originalСountriesId to $changedСountriesId</span><br>";
            }

            if (array_key_exists('note', $changed)) {
                $originalNote = $original['note'];
                $originalNote = $originalNote ?? '';

                $changedNote = $changed['note'];
                $changedNote = $changedNote ?? '';

                if ($originalNote && $changedNote) {
                    $description .= "<span> Changed Note from $originalNote to $changedNote</span><br>";
                } elseif ($originalNote && !$changedNote) {
                    $description .= "<span> Removed Note $originalNote </span><br>";
                } elseif (!$originalNote && $changedNote) {
                    $description .= "<span> Added Note $changedNote </span><br>";
                }
            }

            if (array_key_exists('facebook_account_id', $changed)) {
                $originalFacebookAccountsId = LeadFacebookAccount::find($original['facebook_account_id']) ?? null;
                $originalFacebookAccountsId = $originalFacebookAccountsId ? $originalFacebookAccountsId->name : '';

                $changedFacebookAccountsId = LeadFacebookAccount::find($changed['countries_id']) ?? null;
                $changedFacebookAccountsId = $changedFacebookAccountsId ? $changedFacebookAccountsId->name : '';
                $description .= "<span> Changed Facebook Account from $originalFacebookAccountsId to $changedFacebookAccountsId</span><br>";
            }


            if (array_key_exists('client_data', $changed)) {
                foreach ($changed['client_data'] as $clientItem) {

                    if (array_key_exists('client_name_new', $clientItem)) {
                        $description .= "<span> Add New Lead Details</span><br>";
                    }

                    if (array_key_exists('name', $clientItem['changed'])) {
                        $originalName = $clientItem['original']['name'] ?? '';
                        $changedName = $clientItem['changed']['name'] ?? '';
                        if ($originalName && $changedName) {
                            $description .= "<span> Changed Lead Details Name from $originalName to $changedName</span><br>";
                        } elseif ($originalName && !$changedName) {
                            $description .= "<span> Removed Lead Details Name $originalName </span><br>";
                        } elseif (!$originalName && $changedName) {
                            $description .= "<span> Added Lead Details Name $changedName </span><br>";
                        }
                    }

                    if (array_key_exists('phone', $clientItem['changed'])) {
                        $originalPhone = $clientItem['original']['phone'] ?? '';
                        $changedPhone = $clientItem['changed']['phone'] ?? '';
                        if ($originalPhone && $changedPhone) {
                            $description .= "<span> Changed Lead Details Phone from $originalPhone to $changedPhone</span><br>";
                        } elseif ($originalPhone && !$changedPhone) {
                            $description .= "<span> Removed Lead Details Phone $originalPhone </span><br>";
                        } elseif (!$originalPhone && $changedPhone) {
                            $description .= "<span> Added Lead Details Phone $changedPhone </span><br>";
                        }
                    }

                    if (array_key_exists('email', $clientItem['changed'])) {
                        $originalEmail = $clientItem['original']['email'] ?? '';
                        $changedEmail = $clientItem['changed']['email'] ?? '';
                        if ($originalEmail && $changedEmail) {
                            $description .= "<span> Changed Lead Details Email from $originalEmail to $changedEmail</span><br>";
                        } elseif ($originalEmail && !$changedEmail) {
                            $description .= "<span> Removed Lead Details Email $originalEmail </span><br>";
                        } elseif (!$originalEmail && $changedEmail) {
                            $description .= "<span> Added Lead Details Email $changedEmail </span><br>";
                        }
                    }

                    if (array_key_exists('linked', $clientItem['changed'])) {
                        $originalLinked = $clientItem['original']['linked'] ?? '';
                        $changedLinked = $clientItem['changed']['linked'] ?? '';
                        if ($originalLinked && $changedLinked) {
                            $description .= "<span> Changed Lead Details Contact's LinkedIn from $originalLinked to $changedLinked</span><br>";
                        } elseif ($originalLinked && !$changedLinked) {
                            $description .= "<span> Removed Lead Details LinkedIn $originalLinked </span><br>";
                        } elseif (!$originalLinked && $changedLinked) {
                            $description .= "<span> Added Lead Details LinkedIn $changedLinked </span><br>";
                        }
                    }

                    if (array_key_exists('skype', $clientItem['changed'])) {
                        $originalSkype = $clientItem['original']['skype'] ?? '';
                        $changedSkype = $clientItem['changed']['skype'] ?? '';
                        if ($originalSkype && $changedSkype) {
                            $description .= "<span> Changed Lead Skype from $originalSkype to $changedSkype</span><br>";
                        } elseif ($originalSkype && !$changedSkype) {
                            $description .= "<span> Removed Lead Details Skype $originalSkype </span><br>";
                        } elseif (!$originalSkype && $changedSkype) {
                            $description .= "<span> Added Lead Details Skype $changedSkype </span><br>";
                        }
                    }

                    if (array_key_exists('job_id', $clientItem['changed'])) {
                        $originalJobId = LeadCompanyClientJob::find($clientItem['original']['job_id'] ?? null) ?? null;
                        $originalJobId = $originalJobId ? $originalJobId->type : '';

                        $changedJobId = LeadCompanyClientJob::find($clientItem['changed']['job_id']) ?? null;
                        $changedJobId = $changedJobId ? $changedJobId->type : '';

                        if ($originalJobId && $changedJobId) {
                            $description .= "<span> Changed Position from $originalJobId to $changedJobId</span><br>";
                        } elseif ($originalJobId && !$changedJobId) {
                            $description .= "<span> Removed Lead Details Position $originalJobId </span><br>";
                        } elseif (!$originalJobId && $changedJobId) {
                            $description .= "<span> Added Lead Details Position $changedJobId </span><br>";
                        }
                    }
                }
            }

            Storage::prepend($nameFile, $description);
        }
    }

    public function salesActivity($original = null, $changed = null, $created = false, $folder = "sales/activity/")
    {
        if (!\File::exists(public_path('user-uploads/' . $folder))) {
            \File::makeDirectory(public_path('user-uploads/' . $folder), 0775, true);
        }
        $nameFile = $folder . $original["id"] . '.txt';
        $exists = Storage::disk('local')->exists($nameFile);
        if (!$exists) {
            Storage::disk('local')->put($nameFile, '');
        }

        if ($created) {
            $description = '';
            $date = Carbon::now()->timezone($this->global->timezone)->format('d-m-Y H:i:s');

            $description .= "<strong class='font-bold' >$date: </strong>";
            $description .= " <strong class='font-semi-bold' > <a target='_blank' href=" . route('admin.employees.show', [$this->user->id]) . ">" . $this->user->name . "</a></strong><br>";
            $description .= "<span> Added Sales</span>";

            Storage::prepend($nameFile, $description);
        }

        if ($original && $changed) {
            $description = '';
            $date = Carbon::now()->timezone($this->global->timezone)->format('d-m-Y H:i:s');
            $description .= "<strong class='font-bold' >$date: </strong>";
            $description .= "<strong class='font-semi-bold' > <a target='_blank' href=" . route('admin.employees.show', [$this->user->id]) . ">" . $this->user->name . "</a></strong><br>";

            if (array_key_exists('detach_client_need_candidate', $changed))
                $description .= "<span>Delete client need candidate " . $changed['detach_client_need_candidate'] . "</span><br>";

            if (array_key_exists('detach_client', $changed))
                $description .= "<span>Delete client " . $changed['detach_client'] . "</span><br>";


            if (array_key_exists('detach_candidate', $changed))
                $description .= "<span>Delete candidate " . $changed['detach_candidate'] . "</span><br>";


            if (array_key_exists('name', $changed)) {
                $originalName = $original['name'] ?? '';
                $changedName = $changed['name'] ?? '';

                if ($originalName && $changedName) {
                    $description .= "<span> Changed Name from $originalName to $changedName</span><br>";
                } elseif ($originalName && !$changedName) {
                    $description .= "<span> Removed Name $originalName </span><br>";
                } elseif (!$originalName && $changedName) {
                    $description .= "<span> Added Name $changedName </span><br>";
                }
            }

            if (array_key_exists('email', $changed)) {
                $originalEmail = $original['email'] ?? '';
                $changedEmail = $changed['email'] ?? '';

                if ($originalEmail && $changedEmail) {
                    $description .= "<span> Changed Email from $originalEmail to $changedEmail</span><br>";
                } elseif ($originalEmail && !$changedEmail) {
                    $description .= "<span> Removed Email $originalEmail </span><br>";
                } elseif (!$originalEmail && $changedEmail) {
                    $description .= "<span> Added Email $changedEmail </span><br>";
                }
            }

            if (array_key_exists('mobile', $changed)) {
                $originalMobile = $original['mobile'] ?? '';
                $changedMobile = $changed['mobile'] ?? '';

                if ($originalMobile && $changedMobile) {
                    $description .= "<span> Changed Mobile from $originalMobile to $changedMobile</span><br>";
                } elseif ($originalMobile && !$changedMobile) {
                    $description .= "<span> Removed Mobile $originalMobile </span><br>";
                } elseif (!$originalMobile && $changedMobile) {
                    $description .= "<span> Added Mobile $changedMobile </span><br>";
                }
            }

            if (array_key_exists('lg_manager_id', $changed)) {
                $originalAgentId = LeadAgent::with(['user' => function ($query) {
                    $query->withoutGlobalScope('active');
                },])->find($original['lg_manager_id']) ?? null;
                $originalAgentId = $originalAgentId ? $originalAgentId->user->name : '';

                $changedAgentId = LeadAgent::with(['user' => function ($query) {
                    $query->withoutGlobalScope('active');
                },])->find($changed['lg_manager_id']) ?? null;
                $changedAgentId = $changedAgentId ? $changedAgentId->user->name : '';
                $description .= "<span> Changed LG Manager from $originalAgentId to $changedAgentId</span><br>";
            }

            if (array_key_exists('lead_source_id', $changed)) {
                $originalSourceId = LeadSource::find($original['lead_source_id']) ?? null;
                $originalSourceId = $originalSourceId ? $originalSourceId->type : '';

                $changedSourceId = LeadSource::find($changed['lead_source_id']) ?? null;
                $changedSourceId = $changedSourceId ? $changedSourceId->type : '';
                $description .= "<span> Changed Lead Source from $originalSourceId to $changedSourceId</span><br>";
            }

            if (array_key_exists('account_id', $changed)) {
                $originalAccountId = LeadAccount::find($original['account_id']) ?? null;
                $originalAccountId = $originalAccountId ? $originalAccountId->name : '';

                $changedAccountId = LeadAccount::find($changed['account_id']) ?? null;
                $changedAccountId = $changedAccountId ? $changedAccountId->name : '';
                $description .= "<span> Changed LinkedIn Accounts from $originalAccountId to $changedAccountId</span><br>";
            }

            if (array_key_exists('lead_status_id', $changed)) {
                $originalStatusId = LeadStatus::find($original['lead_status_id']);
                $originalStatusId = $originalStatusId ? $originalStatusId->type : '';

                $changedStatusId = LeadStatus::find($changed['lead_status_id']);
                $changedStatusId = $changedStatusId ? $changedStatusId->type : '';
                $description .= "<span> Changed Lead Status from $originalStatusId to $changedStatusId</span><br>";
            }

            if (array_key_exists('follow_up_date', $changed)) {
                $originalFollowUpDate = $original['follow_up_date'] ? Carbon::parse($original['follow_up_date'])->timezone($this->global->timezone)->format('d-m-Y') : '';
                $changedFollowUpDate = $changed['follow_up_date'] ? Carbon::parse($changed['follow_up_date'])->timezone($this->global->timezone)->format('d-m-Y') : '';

                if ($originalFollowUpDate && $changedFollowUpDate) {
                    $description .= "<span> Changed Follow Up Date from $originalFollowUpDate to $changedFollowUpDate</span><br>";
                } elseif ($originalFollowUpDate && !$changedFollowUpDate) {
                    $description .= "<span> Removed Follow Up Date $originalFollowUpDate </span><br>";
                } elseif (!$originalFollowUpDate && $changedFollowUpDate) {
                    $description .= "<span> Added Follow Up Date $changedFollowUpDate </span><br>";
                }
            }

            if (array_key_exists('call_date', $changed)) {
                $originalСallDate = $original['call_date'] ? Carbon::parse($original['call_date'])->timezone($this->global->timezone)->format('d-m-Y') : '';
                $changedСallDate = $changed['call_date'] ? Carbon::parse($changed['call_date'])->timezone($this->global->timezone)->format('d-m-Y') : '';

                if ($originalСallDate && $changedСallDate) {
                    $description .= "<span> Changed Event/Call Date from $originalСallDate to $changedСallDate</span><br>";
                } elseif ($originalСallDate && !$changedСallDate) {
                    $description .= "<span> Removed Event/Call Date $originalСallDate </span><br>";
                } elseif (!$originalСallDate && $changedСallDate) {
                    $description .= "<span> Added Event/Call Date $changedСallDate </span><br>";
                }
            }

            if (array_key_exists('time', $changed)) {
                $originalTime = $original['time'] ? Carbon::parse($original['time'])->timezone($this->global->timezone)->format('H:i:s') : '';
                $changedTime = $changed['time'] ? Carbon::parse($changed['time'])->timezone($this->global->timezone)->format('H:i:s') : '';

                if ($originalTime && $changedTime) {
                    $description .= "<span> Changed Time Event/Call from $originalTime to $changedTime</span><br>";
                } elseif ($originalTime && !$changedTime) {
                    $description .= "<span> Removed Time Event/Call $originalTime </span><br>";
                } elseif (!$originalTime && $changedTime) {
                    $description .= "<span> Added Time Event/Call $changedTime </span><br>";
                }
            }

            if (array_key_exists('skype', $changed)) {
                $originalSkype = $original['skype'] ?? '';
                $changedSkype = $changed['skype'] ?? '';

                if ($originalSkype && $changedSkype) {
                    $description .= "<span> Changed Skype from $originalSkype to $changedSkype</span><br>";
                } elseif ($originalSkype && !$changedLinkedin) {
                    $description .= "<span> Removed Skype $originalSkype </span><br>";
                } elseif (!$originalSkype && $changedSkype) {
                    $description .= "<span> Added Skype $changedSkype </span><br>";
                }
            }

            if (array_key_exists('client_job_id', $changed)) {
                $originalPositionNeeded = LeadCompanyClientJob::find($original['client_job_id']) ?? null;
                $originalPositionNeeded = $originalPositionNeeded ? $originalPositionNeeded->title : '';

                $changedPositionNeeded = LeadCompanyClientJob::find($changed['client_job_id']) ?? null;
                $changedPositionNeeded = $changedPositionNeeded ? $changedPositionNeeded->title : '';
                $description .= "<span> Changed Client Position from $originalPositionNeeded to $changedPositionNeeded</span><br>";
            }

            if (array_key_exists('company_name', $changed)) {
                $originalCompanyName = $original['company_name'] ?? '';
                $changedCompanyName = $changed['company_name'] ?? '';

                if ($originalCompanyName && $changedCompanyName) {
                    $description .= "<span> Changed Company Name from $originalCompanyName to $changedCompanyName</span><br>";
                } elseif ($originalCompanyName && !$changedCompanyName) {
                    $description .= "<span> Removed Company Name $originalCompanyName </span><br>";
                } elseif (!$originalCompanyName && $changedCompanyName) {
                    $description .= "<span> Added Company Name $changedCompanyName </span><br>";
                }
            }

            if (array_key_exists('website', $changed)) {
                $originalWebsite = $original['website'] ?? '';
                $changedWebsite = $changed['website'] ?? '';

                if ($originalWebsite && $changedWebsite) {
                    $description .= "<span> Changed Website from $originalWebsite to $changedWebsite</span><br>";
                } elseif ($originalWebsite && !$changedWebsite) {
                    $description .= "<span> Removed WebsiteWebsite $originalWebsite </span><br>";
                } elseif (!$originalWebsite && $changedWebsite) {
                    $description .= "<span> Added Website $changedWebsite </span><br>";
                }
            }

            if (array_key_exists('company_facebook', $changed)) {
                $originalCompanyFacebook = $original['company_facebook'] ?? '';
                $changedCompanyFacebooke = $changed['company_facebook'] ?? '';

                if ($originalCompanyFacebook && $changedCompanyFacebooke) {
                    $description .= "<span> Changed Company Facebook from $originalCompanyFacebook to $changedCompanyFacebooke</span><br>";
                } elseif ($originalCompanyFacebook && !$changedCompanyFacebooke) {
                    $description .= "<span> Removed Company Facebook $originalCompanyFacebook </span><br>";
                } elseif (!$originalCompanyFacebook && $changedCompanyFacebooke) {
                    $description .= "<span> Added Company Facebook $changedCompanyFacebooke </span><br>";
                }
            }

            if (array_key_exists('twitter', $changed)) {
                $originalTwitter = $original['twitter'] ?? '';
                $changedTwitter = $changed['twitter'] ?? '';

                if ($originalTwitter && $changedTwitter) {
                    $description .= "<span> Changed Company Twitter from $originalTwitter to $changedTwitter</span><br>";
                } elseif ($originalTwitter && !$changedTwitter) {
                    $description .= "<span> Removed Company Twitterk $originalCompanyFacebook </span><br>";
                } elseif (!$originalTwitter && $changedTwitter) {
                    $description .= "<span> Added Company Twitter $changedTwitter </span><br>";
                }
            }

            if (array_key_exists('company_linked', $changed)) {
                $originalCompanyLinked = $original['company_linked'];
                $originalCompanyLinked = $originalCompanyLinked ?? '';

                $changedCompanyLinked = $changed['company_linked'];
                $changedCompanyLinked = $changedCompanyLinked ?? '';

                if ($originalCompanyLinked && $changedCompanyLinked) {
                    $description .= "<span> Changed Company LinkedIn from $originalCompanyLinked to $changedCompanyLinked</span><br>";
                } elseif ($originalCompanyLinked && !$changedCompanyLinked) {
                    $description .= "<span> Removed Company LinkedIn $originalCompanyLinked </span><br>";
                } elseif (!$originalCompanyLinked && $changedCompanyLinked) {
                    $description .= "<span> Added Company LinkedIn $changedCompanyLinked </span><br>";
                }
            }

            if (array_key_exists('size_id', $changed)) {
                $originalSizeId = LeadCompanySize::find($original['size_id']) ?? null;
                $originalSizeId = $originalSizeId ? $originalSizeId->title : '';

                $changedSizeId = LeadCompanySize::find($changed['size_id']) ?? null;
                $changedSizeId = $changedSizeId ? $changedSizeId->title : '';

                if ($originalSizeId && $changedSizeId) {
                    $description .= "<span> Changed Company Size from $originalSizeId to $changedSizeId</span><br>";
                } elseif ($originalSizeId && !$changedSizeId) {
                    $description .= "<span> Removed Company Size $originalSizeId </span><br>";
                } elseif (!$originalSizeId && $changedSizeId) {
                    $description .= "<span> Added Company Size $changedSizeId </span><br>";
                }
            }

            if (array_key_exists('industry_id', $changed)) {
                $originalSizeId = LeadCompanyIndustry::find($original['industry_id']) ?? null;
                $originalSizeId = $originalSizeId ? $originalSizeId->title : '';

                $changedSizeId = LeadCompanyIndustry::find($changed['industry_id']) ?? null;
                $changedSizeId = $changedSizeId ? $changedSizeId->title : '';
                $description .= "<span> Changed Company Industry from $originalSizeId to $changedSizeId</span><br>";
            }

            if (array_key_exists('countries_id', $changed)) {
                $originalСountriesId = Country::find($original['countries_id']) ?? null;
                $originalСountriesId = $originalСountriesId ? $originalСountriesId->name : '';

                $changedСountriesId = Country::find($changed['countries_id']) ?? null;
                $changedСountriesId = $changedСountriesId ? $changedСountriesId->name : '';
                $description .= "<span> Changed Country from $originalСountriesId to $changedСountriesId</span><br>";
            }

            if (array_key_exists('note', $changed)) {
                $originalNote = $original['note'];
                $originalNote = $originalNote ?? '';

                $changedNote = $changed['note'];
                $changedNote = $changedNote ?? '';

                if ($originalNote && $changedNote) {
                    $description .= "<span> Changed Note from $originalNote to $changedNote</span><br>";
                } elseif ($originalNote && !$changedNote) {
                    $description .= "<span> Removed Note $originalNote </span><br>";
                } elseif (!$originalNote && $changedNote) {
                    $description .= "<span> Added Note $changedNote </span><br>";
                }
            }


            if (array_key_exists('sales_manager', $changed)) {
                $originalSalesManager = User::withoutGlobalScope('active')->find($original['sales_manager']) ?? null;
                $originalSalesManager = $originalSalesManager ? $originalSalesManager->name : '';

                $changedSalesManager = User::withoutGlobalScope('active')->find($changed['sales_manager']) ?? null;
                $changedSalesManager = $changedSalesManager ? $changedSalesManager->name : '';
                $description .= "<span> Changed Sales Manager from $originalSalesManager to $changedSalesManager</span><br>";
            }

            if (array_key_exists('sales_manager_assistant', $changed)) {
                $originalSalesManagerAssistant = User::withoutGlobalScope('active')->find($original['sales_manager_assistant']) ?? null;
                $originalSalesManagerAssistant = $originalSalesManagerAssistant ? $originalSalesManagerAssistant->name : '';

                $changedSalesManagerAssistant = User::withoutGlobalScope('active')->find($changed['sales_manager_assistant']) ?? null;
                $changedSalesManagerAssistant = $changedSalesManagerAssistant ? $changedSalesManagerAssistant->name : '';
                $description .= "<span> Changed Sales Manager Assistant from $originalSalesManagerAssistant to $changedSalesManagerAssistant</span><br>";
            }

            if (array_key_exists('status_id', $changed)) {
                $originalStatusSales = ClientStatus::find($original['status_id']) ?? null;
                $originalStatusSales = $originalStatusSales ? $originalStatusSales->name : '';

                $changedStatusSales = ClientStatus::find($changed['status_id']) ?? null;
                $changedStatusSales = $changedStatusSales ? $changedStatusSales->name : '';
                $description .= "<span> Changed Status Sales from $originalStatusSales to $changedStatusSales</span><br>";
            }

            if (array_key_exists('last_commun', $changed)) {
                $originalLastCommun = $original['last_commun'] ? Carbon::parse($original['last_commun'])->timezone($this->global->timezone)->format('d-m-Y') : '';
                $changedLastCommun = $changed['last_commun'] ? Carbon::parse($changed['last_commun'])->timezone($this->global->timezone)->format('d-m-Y') : '';

                if ($originalLastCommun && $changedLastCommun) {
                    $description .= "<span> Changed Last Communication from $originalLastCommun to $changedLastCommun</span><br>";
                } elseif ($originalLastCommun && !$changedLastCommun) {
                    $description .= "<span> Removed Last Communication $originalLastCommun </span><br>";
                } elseif (!$originalLastCommun && $changedLastCommun) {
                    $description .= "<span> Added Last Communication $changedLastCommun </span><br>";
                }
            }

            if (array_key_exists('main_commun_id', $changed)) {
                $originalCommunication = ClientMainCommunication::find($original['main_commun_id']) ?? null;
                $originalCommunication = $originalCommunication ? $originalCommunication->name : '';

                $changedCommunication = ClientMainCommunication::find($changed['main_commun_id']) ?? null;
                $changedCommunication = $changedCommunication ? $changedCommunication->name : '';
                $description .= "<span> Changed Sales Main Communication from $originalCommunication to $changedCommunication</span><br>";
            }

            if (array_key_exists('client_data', $changed)) {
                foreach ($changed['client_data'] as $clientItem) {
                    if (array_key_exists('client_name_new', $clientItem) && $clientItem['client_name_new'] == true) {
                        $description .= "<span> Add New Client Details</span><br>";
                    }

                    if (array_key_exists('contact_name', $clientItem['changed'])) {
                        $originalClientName = $clientItem['original']['contact_name'] ?? '';
                        $changedClientName = $clientItem['changed']['contact_name'] ?? '';

                        if ($originalClientName && $changedClientName) {
                            $description .= "<span> Changed Client Name from $originalClientName to $changedClientName</span><br>";
                        } elseif ($originalClientName && !$changedClientName) {
                            $description .= "<span> Removed Client Name $originalClientName </span><br>";
                        } elseif (!$originalClientName && $changedClientName) {
                            $description .= "<span> Added Client Name $changedClientName </span><br>";
                        }
                    }

                    if (array_key_exists('email', $clientItem['changed'])) {
                        $originalClientEmail = $clientItem['original']['email'] ?? '';
                        $changedClientEmail = $clientItem['changed']['email'] ?? '';

                        if ($originalClientEmail && $changedClientEmail) {
                            $description .= "<span> Changed Client Email from $originalClientEmail to $changedClientEmail</span><br>";
                        } elseif ($originalClientEmail && !$changedClientEmail) {
                            $description .= "<span> Removed Client Email $originalClientEmail </span><br>";
                        } elseif (!$originalClientEmail && $changedClientEmail) {
                            $description .= "<span> Added Client Email $changedClientEmail </span><br>";
                        }
                    }

                    if (array_key_exists('phone', $clientItem['changed'])) {
                        $originalMobile = $clientItem['original']['phone'] ?? '';
                        $changedMobile = $clientItem['changed']['phone'] ?? '';

                        if ($originalMobile && $changedMobile) {
                            $description .= "<span> Changed Client Phone from $originalMobile to $changedMobile</span><br>";
                        } elseif ($originalMobile && !$changedMobile) {
                            $description .= "<span> Removed Client Phone $originalMobile </span><br>";
                        } elseif (!$originalMobile && $changedMobile) {
                            $description .= "<span> Added Client Phone $changedMobile </span><br>";
                        }
                    }

                    if (array_key_exists('linked', $clientItem['changed'])) {
                        $originalLinkedin = $clientItem['original']['linked'] ?? '';
                        $changedLinkedin = $clientItem['changed']['linked'] ?? '';

                        if ($originalLinkedin && $changedLinkedin) {
                            $description .= "<span> Changed Client Linkedin from $originalLinkedin to $changedLinkedin</span><br>";
                        } elseif ($originalLinkedin && !$changedLinkedin) {
                            $description .= "<span> Removed Client Linkedin $originalLinkedin </span><br>";
                        } elseif (!$originalLinkedin && $changedLinkedin) {
                            $description .= "<span> Added Client Linkedin $changedLinkedin </span><br>";
                        }
                    }

                    if (array_key_exists('skype', $clientItem['changed'])) {
                        $originalSkype = $clientItem['original']['skype'] ?? '';
                        $changedSkype = $clientItem['changed']['skype'] ?? '';

                        if ($originalSkype && $changedSkype) {
                            $description .= "<span> Changed Client Skype from $originalSkype to $changedSkype</span><br>";
                        } elseif ($originalSkype && !$changedSkype) {
                            $description .= "<span> Removed Client Skype $originalSkype </span><br>";
                        } elseif (!$originalSkype && $changedSkype) {
                            $description .= "<span> Added Client Skype $changedSkype </span><br>";
                        }
                    }

                    if (array_key_exists('job_id', $clientItem['changed'])) {
                        $originalPositionNeeded = isset($clientItem['original']['job_id']) ? LeadCompanyClientJob::find($clientItem['original']['job_id']) : null;
                        $originalPositionNeeded = $originalPositionNeeded ? $originalPositionNeeded->type : '';

                        $changedPositionNeeded = LeadCompanyClientJob::find($clientItem['changed']['job_id']) ?? null;
                        $changedPositionNeeded = $changedPositionNeeded ? $changedPositionNeeded->type : '';

                        if ($originalPositionNeeded && $changedPositionNeeded) {
                            $description .= "<span> Changed Client Position from $originalPositionNeeded to $changedPositionNeeded</span><br>";
                        } elseif ($originalPositionNeeded && !$changedPositionNeeded) {
                            $description .= "<span> Removed Client Position $originalPositionNeeded </span><br>";
                        } elseif (!$originalPositionNeeded && $changedPositionNeeded) {
                            $description .= "<span> Added Client Position $changedPositionNeeded </span><br>";
                        }
                    }
                }
            }

            if (array_key_exists('candidate_data', $changed)) {
                foreach ($changed['candidate_data'] as $candidateItem) {
                    if (array_key_exists('candidate_name_new', $candidateItem) && $candidateItem['candidate_name_new'] == true) {
                        $description .= "<span> Add New Candidate Details</span><br>";
                    }

                    if (array_key_exists('user_id', $candidateItem['changed'])) {
                        $originalCandidateId = isset($candidateItem['original']['user_id']) ? User::withoutGlobalScope('active')->find($candidateItem['original']['user_id']) : null;
                        $originalCandidateId = $originalCandidateId ? $originalCandidateId->name : '';

                        $changedCandidateId = isset($candidateItem['changed']['user_id']) ? User::withoutGlobalScope('active')->find($candidateItem['changed']['user_id']) : null;
                        $changedCandidateId = $changedCandidateId ? $changedCandidateId->name : '';
                        if ($originalCandidateId && $changedCandidateId) {
                            $description .= "<span> Changed Candidate from $originalCandidateId to $changedCandidateId</span><br>";
                        } elseif ($originalCandidateId && !$changedCandidateId) {
                            $description .= "<span> Removed Candidate $originalCandidateStatus </span><br>";
                        } elseif (!$originalCandidateId && $changedCandidateId) {
                            $description .= "<span> Added Candidate $changedCandidateId </span><br>";
                        }
                    }

                    if (array_key_exists('status_id', $candidateItem['changed'])) {
                        $originalCandidateStatus = isset($candidateItem['original']['status_id']) ? ClientStatusCandidate::find($candidateItem['original']['status_id']) : null;
                        $originalCandidateStatus = $originalCandidateStatus ? $originalCandidateStatus->name : '';

                        $changedCandidateStatus = isset($candidateItem['changed']['status_id']) ? ClientStatusCandidate::find($candidateItem['changed']['status_id']) : null;
                        $changedCandidateStatus = $changedCandidateStatus ? $changedCandidateStatus->name : '';
                        if ($originalCandidateStatus && $changedCandidateStatus) {
                            $description .= "<span> Changed Status Candidate from $originalCandidateStatus to $changedCandidateStatus</span><br>";
                        } elseif ($originalCandidateStatus && !$changedCandidateStatus) {
                            $description .= "<span> Removed Status $originalCandidateStatus </span><br>";
                        } elseif (!$originalCandidateStatus && $changedCandidateStatus) {
                            $description .= "<span> Added Status $changedCandidateStatus </span><br>";
                        }
                    }

                    if (array_key_exists('interview_date', $candidateItem['changed'])) {
                        $originalInterviewDate = isset($candidateItem['original']['interview_date']) ? Carbon::parse($candidateItem['original']['interview_date'])->timezone($this->global->timezone)->format('d-m-Y') : '';
                        $changedInterviewDate = isset($candidateItem['changed']['interview_date']) ? Carbon::parse($candidateItem['changed']['interview_date'])->timezone($this->global->timezone)->format('d-m-Y') : '';

                        if ($originalInterviewDate && $changedInterviewDate) {
                            $description .= "<span> Changed Interview Date from $originalInterviewDate to $changedInterviewDate</span><br>";
                        } elseif ($originalInterviewDate && !$changedInterviewDate) {
                            $description .= "<span> Removed Interview Date $originalInterviewDate </span><br>";
                        } elseif (!$originalInterviewDate && $changedInterviewDate) {
                            $description .= "<span> Added Interview Date $changedInterviewDate </span><br>";
                        }
                    }

                    if (array_key_exists('time_interview', $candidateItem['changed'])) {
                        $originalTime = isset($candidateItem['original']['time_interview']) ? Carbon::parse($candidateItem['original']['time_interview'])->timezone($this->global->timezone)->format('H:i:s') : '';
                        $changedTime = isset($candidateItem['changed']['time_interview']) ? Carbon::parse($candidateItem['changed']['time_interview'])->timezone($this->global->timezone)->format('H:i:s') : '';

                        if ($originalTime && $changedTime) {
                            $description .= "<span> Changed Interview Time from $originalTime to $changedTime</span><br>";
                        } elseif ($originalTime && !$changedTime) {
                            $description .= "<span> Removed Interview Time $originalTime </span><br>";
                        } elseif (!$originalTime && $changedTime) {
                            $description .= "<span> Added Interview Time $changedTime </span><br>";
                        }
                    }

                    if (array_key_exists('start_date', $candidateItem['changed'])) {
                        $originalStartDate = isset($candidateItem['original']['start_date']) ? Carbon::parse($candidateItem['original']['start_date'])->timezone($this->global->timezone)->format('d-m-Y') : '';
                        $changedStartDate = isset($candidateItem['changed']['start_date']) ? Carbon::parse($candidateItem['changed']['start_date'])->timezone($this->global->timezone)->format('d-m-Y') : '';

                        if ($originalStartDate && $changedStartDate) {
                            $description .= "<span> Changed Start Date from $originalStartDate to $changedStartDate</span><br>";
                        } elseif ($originalStartDate && !$changedStartDate) {
                            $description .= "<span> Removed Start Date $originalStartDate </span><br>";
                        } elseif (!$originalStartDate && $changedStartDate) {
                            $description .= "<span> Added Start Date $changedStartDate </span><br>";
                        }
                    }

                    if (array_key_exists('fire_date', $candidateItem['changed'])) {
                        $originalFireDate = isset($candidateItem['original']['fire_date']) ? Carbon::parse($candidateItem['original']['fire_date'])->timezone($this->global->timezone)->format('d-m-Y') : '';
                        $changedFireDate = isset($candidateItem['changed']['fire_date']) ? Carbon::parse($candidateItem['changed']['fire_date'])->timezone($this->global->timezone)->format('d-m-Y') : '';

                        if ($originalFireDate && $changedFireDate) {
                            $description .= "<span> Changed Fire Date from $originalFireDate to $changedFireDate</span><br>";
                        } elseif ($originalFireDate && !$changedFireDate) {
                            $description .= "<span> Removed Fire Date $originalFireDate </span><br>";
                        } elseif (!$originalFireDate && $changedFireDate) {
                            $description .= "<span> Added Fire Date $changedFireDate </span><br>";
                        }
                    }

                    if (array_key_exists('service_agreement', $candidateItem['changed'])) {
                        $originalServiceAgreement = $candidateItem['original']['service_agreement'] ?? '';
                        $changedServiceAgreement = $candidateItem['changed']['service_agreement'] ?? '';

                        if ($originalServiceAgreement && $changedServiceAgreement) {
                            $description .= "<span> Changed Service Agreementr from $originalServiceAgreement to $changedServiceAgreement</span><br>";
                        } elseif ($originalServiceAgreement && !$changedServiceAgreement) {
                            $description .= "<span> Removed Service Agreement $originalCompanyFacebook </span><br>";
                        } elseif (!$originalServiceAgreement && $changedServiceAgreement) {
                            $description .= "<span> Added Service Agreement $changedServiceAgreement </span><br>";
                        }
                    }

                    if (array_key_exists('price', $candidateItem['changed'])) {
                        $originalPrice = $candidateItem['original']['price'] ?? '';
                        $changedPrice = $candidateItem['changed']['price'] ?? '';

                        if ($originalPrice && $changedPrice) {
                            $description .= "<span> Changed Price from $originalPrice to $changedPrice</span><br>";
                        } elseif ($originalPrice && !$changedPrice) {
                            $description .= "<span> Removed Price $originalPrice </span><br>";
                        } elseif (!$originalPrice && $changedPrice) {
                            $description .= "<span> Added Price $changedPrice </span><br>";
                        }
                    }
                }
            }

            Storage::prepend($nameFile, $description);
        }
    }

}
