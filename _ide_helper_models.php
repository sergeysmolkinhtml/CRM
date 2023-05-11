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
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeDetails newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeDetails newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeDetails query()
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeDetails whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeDetails whereAddressId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeDetails whereBirthday($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeDetails whereCityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeDetails whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeDetails whereEmployeeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeDetails whereFacebook($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeDetails whereHourlyRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeDetails whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeDetails whereJobTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeDetails whereLinkedin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeDetails whereMobileWorkEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeDetails whereNameEng($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeDetails whereOfficeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeDetails wherePhotoUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeDetails wherePtFt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeDetails whereResumeUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeDetails whereSkype($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeDetails whereSlackUsername($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeDetails whereStartPosition($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeDetails whereStatusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeDetails whereTelegram($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeDetails whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeDetails whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeDetails whereViber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeDetails whereVideo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeDetails whereWhatsapp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeDetails whereWorkEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmployeeDetails whereWorkHemail($value)
 */
	class EmployeeDetails extends \Eloquent {}
}

namespace App\Models\Employee{
/**
 * App\Models\Employee\EmployeeStatus
 *
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Employee\EmployeeDetails> $user
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
 * @property-read \App\Models\Employee\EmployeeDetails|null $employee
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

