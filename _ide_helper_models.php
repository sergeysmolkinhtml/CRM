<?php

// @formatter:off
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Config{
/**
 * App\Config\EmailNotificationSettings
 *
 * @method static \Illuminate\Database\Eloquent\Builder|EmailNotificationSettings newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EmailNotificationSettings newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EmailNotificationSettings query()
 */
	class EmailNotificationSettings extends \Eloquent {}
}

namespace App\Config{
/**
 * App\Config\PushNotificationSettings
 *
 * @method static \Illuminate\Database\Eloquent\Builder|PushNotificationSettings newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PushNotificationSettings newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PushNotificationSettings query()
 */
	class PushNotificationSettings extends \Eloquent {}
}

namespace App\Config{
/**
 * App\Config\SMTPSettings
 *
 * @property-read mixed $set_smtp_message
 * @method static \Illuminate\Database\Eloquent\Builder|SMTPSettings newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SMTPSettings newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SMTPSettings query()
 */
	class SMTPSettings extends \Eloquent {}
}

namespace App\Config{
/**
 * App\Config\SlackSettings
 *
 * @property-read mixed $slack_logo_url
 * @method static \Illuminate\Database\Eloquent\Builder|SlackSettings newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SlackSettings newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SlackSettings query()
 */
	class SlackSettings extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Attendance
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Attendance newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Attendance newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Attendance query()
 */
	class Attendance extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\BaseModel
 *
 * @method static \Illuminate\Database\Eloquent\Builder|BaseModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BaseModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BaseModel query()
 */
	class BaseModel extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\City
 *
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|City newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|City newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|City query()
 * @method static \Illuminate\Database\Eloquent\Builder|City whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|City whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|City whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|City whereUpdatedAt($value)
 */
	class City extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Client
 *
 * @property int $id
 * @property string $contact_name
 * @property string $contact_email
 * @property string $contact_phone_number
 * @property string $company_name
 * @property string $company_address
 * @property string $company_city
 * @property string $company_zip
 * @property int $company_vat
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Contact> $contacts
 * @property-read int|null $contacts_count
 * @property-read mixed $primary_contact
 * @property-read \App\Models\Contact|null $primaryContact
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Project> $projects
 * @property-read int|null $projects_count
 * @method static \Database\Factories\ClientFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Client newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Client newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Client query()
 * @method static \Illuminate\Database\Eloquent\Builder|Client whereCompanyAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Client whereCompanyCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Client whereCompanyName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Client whereCompanyVat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Client whereCompanyZip($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Client whereContactEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Client whereContactName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Client whereContactPhoneNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Client whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Client whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Client whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Client whereUpdatedAt($value)
 */
	class Client extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Company
 *
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @method static \Illuminate\Database\Eloquent\Builder|Company newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Company newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Company query()
 */
	class Company extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Contact
 *
 * @property int $id
 * @property string $external_id
 * @property string $name
 * @property string $email
 * @property string|null $primary_number
 * @property string|null $secondary_number
 * @property int $client_id
 * @property int $is_primary
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Client $client
 * @method static \Illuminate\Database\Eloquent\Builder|Contact filterFields(array $filters)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Contact newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Contact onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Contact query()
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereClientId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereExternalId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereIsPrimary($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact wherePrimaryNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereSecondaryNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Contact withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Contact withoutTrashed()
 */
	class Contact extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Country
 *
 * @property int $id
 * @property string $name
 * @property int $is_visible
 * @property string $iso_alpha2
 * @property string $iso_alpha3
 * @property int $iso_numeric
 * @property string $currency_code
 * @property string $currency_name
 * @property string $currency_symbol
 * @property string $flag
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Country newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Country newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Country query()
 * @method static \Illuminate\Database\Eloquent\Builder|Country whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Country whereCurrencyCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Country whereCurrencyName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Country whereCurrencySymbol($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Country whereFlag($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Country whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Country whereIsVisible($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Country whereIsoAlpha2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Country whereIsoAlpha3($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Country whereIsoNumeric($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Country whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Country whereUpdatedAt($value)
 */
	class Country extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Currency
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Currency newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Currency newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Currency query()
 */
	class Currency extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Designation
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Designation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Designation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Designation query()
 */
	class Designation extends \Eloquent {}
}

namespace App\Models\Employee{
/**
 * App\Models\Employee\EducationDegree
 *
 * @method static \Illuminate\Database\Eloquent\Builder|EducationDegree newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EducationDegree newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EducationDegree query()
 */
	class EducationDegree extends \Eloquent {}
}

namespace App\Models\Employee{
/**
 * App\Models\Employee\EducationExperienceTag
 *
 * @method static \Illuminate\Database\Eloquent\Builder|EducationExperienceTag newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EducationExperienceTag newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EducationExperienceTag query()
 */
	class EducationExperienceTag extends \Eloquent {}
}

namespace App\Models\Employee{
/**
 * App\Models\Employee\Employee
 *
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|Employee newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Employee newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Employee query()
 */
	class Employee extends \Eloquent {}
}

namespace App\Models\Employee{
/**
 * App\Models\Employee\EmployeeArtist
 *
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeArtist newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeArtist newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeArtist query()
 */
	class EmployeeArtist extends \Eloquent {}
}

namespace App\Models\Employee{
/**
 * App\Models\Employee\EmployeeContent
 *
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeContent newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeContent newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeContent query()
 */
	class EmployeeContent extends \Eloquent {}
}

namespace App\Models\Employee{
/**
 * App\Models\Employee\EmployeeDetails
 *
 * @property int $id
 * @property int $user_id
 * @property string|null $employee_id
 * @property string|null $name_eng
 * @property string|null $birthday
 * @property string|null $start_position
 * @property string|null $resume_url
 * @property string|null $photo_url
 * @property string $job_title
 * @property string|null $address
 * @property int|null $hourly_rate
 * @property string|null $viber
 * @property string|null $telegram
 * @property string|null $whatsapp
 * @property string|null $facebook
 * @property string|null $linkedin
 * @property string|null $skype
 * @property string|null $work_email
 * @property string|null $mobile_work_email
 * @property string|null $work_hemail
 * @property string|null $pt_ft
 * @property string|null $video
 * @property string|null $slack_username
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $status_id
 * @property int|null $office_id
 * @property int|null $address_id
 * @property int|null $city_id
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Interaction> $interactions
 * @property-read int|null $interactions_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeDetail newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeDetail newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeDetail query()
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeDetail whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeDetail whereAddressId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeDetail whereBirthday($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeDetail whereCityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeDetail whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeDetail whereEmployeeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeDetail whereFacebook($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeDetail whereHourlyRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeDetail whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeDetail whereJobTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeDetail whereLinkedin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeDetail whereMobileWorkEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeDetail whereNameEng($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeDetail whereOfficeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeDetail wherePhotoUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeDetail wherePtFt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeDetail whereResumeUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeDetail whereSkype($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeDetail whereSlackUsername($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeDetail whereStartPosition($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeDetail whereStatusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeDetail whereTelegram($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeDetail whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeDetail whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeDetail whereViber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeDetail whereVideo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeDetail whereWhatsapp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeDetail whereWorkEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeDetail whereWorkHemail($value)
 */
	class EmployeeDetails extends \Eloquent {}
}

namespace App\Models\Employee{
/**
 * App\Models\Employee\EmployeeDocs
 *
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeDocs newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeDocs newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeDocs query()
 */
	class EmployeeDocs extends \Eloquent {}
}

namespace App\Models\Employee{
/**
 * App\Models\Employee\EmployeeHrCorrespondence
 *
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeHrCorrespondence newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeHrCorrespondence newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeHrCorrespondence query()
 */
	class EmployeeHrCorrespondence extends \Eloquent {}
}

namespace App\Models\Employee{
/**
 * App\Models\Employee\EmployeeLanguages
 *
 * @property int $id
 * @property int|null $level_id
 * @property int|null $lang_id
 * @property int $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeLanguages newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeLanguages newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeLanguages query()
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeLanguages whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeLanguages whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeLanguages whereLangId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeLanguages whereLevelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeLanguages whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeLanguages whereUserId($value)
 */
	class EmployeeLanguages extends \Eloquent {}
}

namespace App\Models\Employee{
/**
 * App\Models\Employee\EmployeeOffice
 *
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeOffice newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeOffice newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeOffice query()
 */
	class EmployeeOffice extends \Eloquent {}
}

namespace App\Models\Employee{
/**
 * App\Models\Employee\EmployeeSkill
 *
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeSkill newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeSkill newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeSkill query()
 */
	class EmployeeSkill extends \Eloquent {}
}

namespace App\Models\Employee{
/**
 * App\Models\Employee\EmployeeStatus
 *
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Employee\EmployeeDetail> $user
 * @property-read int|null $user_count
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeStatus newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeStatus newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeStatus query()
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeStatus whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeStatus whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeStatus whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeStatus whereUpdatedAt($value)
 */
	class EmployeeStatus extends \Eloquent {}
}

namespace App\Models\Employee{
/**
 * App\Models\Employee\EmployeeTeam
 *
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeTeam newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeTeam newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeTeam query()
 */
	class EmployeeTeam extends \Eloquent {}
}

namespace App\Models\Employee{
/**
 * App\Models\Employee\EmployeeVideoEditing
 *
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeVideoEditing newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeVideoEditing newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeVideoEditing query()
 */
	class EmployeeVideoEditing extends \Eloquent {}
}

namespace App\Models\Employee{
/**
 * App\Models\Employee\Responsibility
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Responsibility newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Responsibility newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Responsibility query()
 */
	class Responsibility extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\FileStorage
 *
 * @method static \Illuminate\Database\Eloquent\Builder|FileStorage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FileStorage newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FileStorage query()
 */
	class FileStorage extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\HrDetails
 *
 * @method static \Illuminate\Database\Eloquent\Builder|HrDetails newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|HrDetails newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|HrDetails query()
 */
	class HrDetails extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\HrStatusCandidate
 *
 * @method static \Illuminate\Database\Eloquent\Builder|HrStatusCandidate newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|HrStatusCandidate newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|HrStatusCandidate query()
 */
	class HrStatusCandidate extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Interaction
 *
 * @property int $id
 * @property int $employee_details_id
 * @property string $type
 * @property string $description
 * @property string $datetime
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Employee\EmployeeDetail|null $employee
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|Interaction newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Interaction newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Interaction query()
 * @method static \Illuminate\Database\Eloquent\Builder|Interaction whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Interaction whereDatetime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Interaction whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Interaction whereEmployeeDetailsId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Interaction whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Interaction whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Interaction whereUpdatedAt($value)
 */
	class Interaction extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\LangLevel
 *
 * @method static \Illuminate\Database\Eloquent\Builder|LangLevel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LangLevel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LangLevel query()
 */
	class LangLevel extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Languages
 *
 * @property int $id
 * @property string $name
 * @property string|null $code
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Languages newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Languages newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Languages query()
 * @method static \Illuminate\Database\Eloquent\Builder|Languages whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Languages whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Languages whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Languages whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Languages whereUpdatedAt($value)
 */
	class Languages extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\LeadAgent
 *
 * @method static \Illuminate\Database\Eloquent\Builder|LeadAgent newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LeadAgent newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LeadAgent query()
 */
	class LeadAgent extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Leave
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Leave newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Leave newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Leave query()
 */
	class Leave extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\LeaveType
 *
 * @method static \Illuminate\Database\Eloquent\Builder|LeaveType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LeaveType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LeaveType query()
 */
	class LeaveType extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Position
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Position newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Position newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Position query()
 */
	class Position extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Project
 *
 * @property int $id
 * @property string $title
 * @property string $description
 * @property int $user_id
 * @property int $client_id
 * @property string $deadline
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Client $client
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, \Spatie\MediaLibrary\MediaCollections\Models\Media> $media
 * @property-read int|null $media_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Task> $tasks
 * @property-read int|null $tasks_count
 * @property-read \App\Models\User $user
 * @method static \Database\Factories\ProjectFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Project filterStatus($filter)
 * @method static \Illuminate\Database\Eloquent\Builder|Project newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Project newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Project onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Project query()
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereClientId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereDeadline($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Project withoutTrashed()
 */
	class Project extends \Eloquent implements \Spatie\MediaLibrary\HasMedia {}
}

namespace App\Models{
/**
 * App\Models\ProjectMember
 *
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectMember newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectMember newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectMember query()
 */
	class ProjectMember extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\ProjectTimeLog
 *
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectTimeLog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectTimeLog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProjectTimeLog query()
 */
	class ProjectTimeLog extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Purchasable
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Purchasable newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Purchasable newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Purchasable query()
 */
	class Purchasable extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Region
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Region newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Region newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Region query()
 */
	class Region extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Role
 *
 * @property int $id
 * @property string $name
 * @property string $guard_name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $users
 * @property-read int|null $users_count
 * @method static \Illuminate\Database\Eloquent\Builder|Role newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Role newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Role onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Role query()
 * @method static \Illuminate\Database\Eloquent\Builder|Role whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Role whereGuardName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Role whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Role whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Role whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Role withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Role withoutTrashed()
 */
	class Role extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\RoleUser
 *
 * @method static \Illuminate\Database\Eloquent\Builder|RoleUser newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RoleUser newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RoleUser query()
 */
	class RoleUser extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Skill
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Skill newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Skill newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Skill query()
 */
	class Skill extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Tag
 *
 * @property int $id
 * @property int $account_id
 * @property string $name
 * @property string $name_slug
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Tag newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Tag newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Tag query()
 * @method static \Illuminate\Database\Eloquent\Builder|Tag whereAccountId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tag whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tag whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tag whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tag whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tag whereNameSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tag whereUpdatedAt($value)
 */
	class Tag extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Task
 *
 * @property int $id
 * @property string $title
 * @property string $description
 * @property int $user_id
 * @property int $client_id
 * @property int $project_id
 * @property string $deadline
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Client $client
 * @property-read mixed $assigned_user
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, \Spatie\MediaLibrary\MediaCollections\Models\Media> $media
 * @property-read int|null $media_count
 * @property-read \App\Models\Project $project
 * @property-read \App\Models\User $user
 * @method static \Database\Factories\TaskFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Task filterStatus($filter)
 * @method static \Illuminate\Database\Eloquent\Builder|Task newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Task newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Task onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Task query()
 * @method static \Illuminate\Database\Eloquent\Builder|Task whereClientId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Task whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Task whereDeadline($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Task whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Task whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Task whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Task whereProjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Task whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Task whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Task whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Task whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Task withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Task withoutTrashed()
 */
	class Task extends \Eloquent implements \Spatie\MediaLibrary\HasMedia {}
}

namespace App\Models{
/**
 * App\Models\Taskboard
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Taskboard newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Taskboard newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Taskboard query()
 */
	class Taskboard extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Team
 *
 * @property int $id
 * @property string|null $looking_for
 * @property string|null $short_description
 * @property string|null $long_description
 * @property mixed|null $accept_with_grade
 * @property int $user_id
 * @property string|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $users
 * @property-read int|null $users_count
 * @method static \Illuminate\Database\Eloquent\Builder|Team newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Team newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Team query()
 * @method static \Illuminate\Database\Eloquent\Builder|Team whereAcceptWithGrade($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Team whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Team whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Team whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Team whereLongDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Team whereLookingFor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Team whereShortDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Team whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Team whereUserId($value)
 */
	class Team extends \Eloquent {}
}

namespace App\Models\Tools{
/**
 * App\Models\Tools\UniversalSearch
 *
 * @method static \Illuminate\Database\Eloquent\Builder|UniversalSearch newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UniversalSearch newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UniversalSearch query()
 */
	class UniversalSearch extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\User
 *
 * @property int $id
 * @property string $first_name
 * @property string|null $last_name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $address
 * @property string|null $phone_number
 * @property int|null $terms_accepted
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property string $github_id
 * @property string $github_url
 * @property-read string $full_name
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Permission> $permissions
 * @property-read int|null $permissions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Project> $projects
 * @property-read int|null $projects_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Role> $roles
 * @property-read int|null $roles_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Team> $teams
 * @property-read int|null $teams_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 * @method static \Illuminate\Database\Eloquent\Builder|User allUsers()
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|User filterFields(array $filters)
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|User permission($permissions)
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User role($roles, $guard = null)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereFirstName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereGithubId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereGithubUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereLastName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePhoneNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRole($role)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereTermsAccepted($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|User withoutTrashed()
 */
	class User extends \Eloquent implements \Illuminate\Contracts\Auth\MustVerifyEmail {}
}

namespace App\Models{
/**
 * App\Models\UserActivity
 *
 * @method static \Illuminate\Database\Eloquent\Builder|UserActivity newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserActivity newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserActivity query()
 */
	class UserActivity extends \Eloquent {}
}

