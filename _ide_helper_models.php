<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * @property string $id
 * @property string $visa_request_id
 * @property \Illuminate\Support\Carbon $scheduled_at
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Notification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \App\Models\VisaRequest $visaRequest
 * @method static \Database\Factories\AppoitmentFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Appoitment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Appoitment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Appoitment query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Appoitment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Appoitment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Appoitment whereScheduledAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Appoitment whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Appoitment whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Appoitment whereVisaRequestId($value)
 */
	class Appoitment extends \Eloquent {}
}

namespace App\Models{
/**
 * @property string $id
 * @property string $visa_request_id
 * @property string $file_path
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\VisaRequest|null $visaRequest
 * @method static \Database\Factories\BackupFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Backup newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Backup newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Backup query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Backup whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Backup whereFilePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Backup whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Backup whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Backup whereVisaRequestId($value)
 */
	class Backup extends \Eloquent {}
}

namespace App\Models{
/**
 * @property string $id
 * @property string $name
 * @property string $iso_code
 * @property string $phone_code
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\CountryVisaType> $countryVisaTypes
 * @property-read int|null $country_visa_types_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Profil> $profil
 * @property-read int|null $profil_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\VisaRequest> $visaRequests
 * @property-read int|null $visa_requests_count
 * @method static \Database\Factories\CountryFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Country newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Country newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Country query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Country whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Country whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Country whereIsoCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Country whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Country wherePhoneCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Country whereUpdatedAt($value)
 */
	class Country extends \Eloquent {}
}

namespace App\Models{
/**
 * @property string $id
 * @property string $country_id
 * @property string $visa_type_id
 * @property string $price_base
 * @property string|null $price_per_child
 * @property int $processing_duration_min
 * @property int $processing_duration_max
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Country $country
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\RequiredDocument> $requiredDocuments
 * @property-read int|null $required_documents_count
 * @property-read \App\Models\VisaType $visaType
 * @method static \Database\Factories\CountryVisaTypeFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CountryVisaType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CountryVisaType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CountryVisaType query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CountryVisaType whereCountryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CountryVisaType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CountryVisaType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CountryVisaType wherePriceBase($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CountryVisaType wherePricePerChild($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CountryVisaType whereProcessingDurationMax($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CountryVisaType whereProcessingDurationMin($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CountryVisaType whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CountryVisaType whereVisaTypeId($value)
 */
	class CountryVisaType extends \Eloquent {}
}

namespace App\Models{
/**
 * @property string $id
 * @property string $visa_request_id
 * @property string $name
 * @property string $file_path
 * @property int $is_validated
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\VisaRequest $visaRequest
 * @method static \Database\Factories\DocumentFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Document newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Document newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Document query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Document whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Document whereFilePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Document whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Document whereIsValidated($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Document whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Document whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Document whereVisaRequestId($value)
 */
	class Document extends \Eloquent {}
}

namespace App\Models{
/**
 * @property string $id
 * @property string $title
 * @property array<array-key, mixed> $content
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Database\Factories\DocumentationFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Documentation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Documentation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Documentation query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Documentation whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Documentation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Documentation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Documentation whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Documentation whereUpdatedAt($value)
 */
	class Documentation extends \Eloquent {}
}

namespace App\Models{
/**
 * @property string $id
 * @property string $question
 * @property string $answer
 * @property string $category
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Database\Factories\FaqChabotFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FaqChabot newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FaqChabot newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FaqChabot query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FaqChabot whereAnswer($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FaqChabot whereCategory($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FaqChabot whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FaqChabot whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FaqChabot whereQuestion($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FaqChabot whereUpdatedAt($value)
 */
	class FaqChabot extends \Eloquent {}
}

namespace App\Models{
/**
 * @property string $id
 * @property string $user_id
 * @property string $action
 * @property string $description
 * @property string $address
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $user
 * @method static \Database\Factories\LogFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Log newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Log newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Log query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Log whereAction($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Log whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Log whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Log whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Log whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Log whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Log whereUserId($value)
 */
	class Log extends \Eloquent {}
}

namespace App\Models{
/**
 * @property string $id
 * @property string $user_id
 * @property string $visa_request_id
 * @property string $content
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $user
 * @property-read \App\Models\VisaRequest $visaRequest
 * @method static \Database\Factories\MessageFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Message newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Message newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Message query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Message whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Message whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Message whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Message whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Message whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Message whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Message whereVisaRequestId($value)
 */
	class Message extends \Eloquent {}
}

namespace App\Models{
/**
 * @property string $id
 * @property string $type
 * @property string $notifiable_type
 * @property int $notifiable_id
 * @property string $data
 * @property string|null $read_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User|null $user
 * @method static \Database\Factories\NotificationFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification whereData($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification whereNotifiableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification whereNotifiableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification whereReadAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notification whereUpdatedAt($value)
 */
	class Notification extends \Eloquent {}
}

namespace App\Models{
/**
 * @property string $id
 * @property string $visa_request_id
 * @property string $amount
 * @property string $transaction_id
 * @property string $method
 * @property string $currency
 * @property string $status
 * @property array<array-key, mixed> $meta
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Receipt> $receipts
 * @property-read int|null $receipts_count
 * @property-read \App\Models\VisaRequest $visaRequest
 * @method static \Database\Factories\PaymentFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereCurrency($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereMeta($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereMethod($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereTransactionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereVisaRequestId($value)
 */
	class Payment extends \Eloquent {}
}

namespace App\Models{
/**
 * @property string $id
 * @property string $user_id
 * @property string $first_name
 * @property string $last_name
 * @property string $phone
 * @property string $gender
 * @property string $date_of_birth
 * @property string $place_of_birth
 * @property string $status_mat
 * @property string $country_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Country $country
 * @property-read \App\Models\User $user
 * @method static \Database\Factories\ProfilFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profil newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profil newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profil query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profil whereCountryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profil whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profil whereDateOfBirth($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profil whereFirstName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profil whereGender($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profil whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profil whereLastName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profil wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profil wherePlaceOfBirth($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profil whereStatusMat($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profil whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Profil whereUserId($value)
 */
	class Profil extends \Eloquent {}
}

namespace App\Models{
/**
 * @property string $id
 * @property string $payment_id
 * @property string $file_path
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Payment $payment
 * @method static \Database\Factories\ReceiptFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Receipt newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Receipt newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Receipt query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Receipt whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Receipt whereFilePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Receipt whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Receipt wherePaymentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Receipt whereUpdatedAt($value)
 */
	class Receipt extends \Eloquent {}
}

namespace App\Models{
/**
 * @property string $id
 * @property string $name
 * @property string $status_mat
 * @property int $min_age
 * @property int $max_age
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\CountryVisaType> $countryVisaTypes
 * @property-read int|null $country_visa_types_count
 * @method static \Database\Factories\RequiredDocumentFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RequiredDocument newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RequiredDocument newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RequiredDocument query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RequiredDocument whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RequiredDocument whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RequiredDocument whereMaxAge($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RequiredDocument whereMinAge($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RequiredDocument whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RequiredDocument whereStatusMat($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RequiredDocument whereUpdatedAt($value)
 */
	class RequiredDocument extends \Eloquent {}
}

namespace App\Models{
/**
 * @property string $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Log> $logs
 * @property-read int|null $logs_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Message> $messages
 * @property-read int|null $messages_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Permission> $permissions
 * @property-read int|null $permissions_count
 * @property-read \App\Models\Profil|null $profil
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Role> $roles
 * @property-read int|null $roles_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\VisaRequest> $visaRequests
 * @property-read int|null $visa_requests_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User permission($permissions, $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User role($roles, $guard = null, $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User withoutPermission($permissions)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User withoutRole($roles, $guard = null)
 */
	class User extends \Eloquent {}
}

namespace App\Models{
/**
 * @property string $id
 * @property string $user_id
 * @property string $visa_type_id
 * @property string $origin_country_id
 * @property string $destination_country_id
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Appoitment> $appoitments
 * @property-read int|null $appoitments_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Backup> $backups
 * @property-read int|null $backups_count
 * @property-read \App\Models\Country $destinationCountry
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Document> $documents
 * @property-read int|null $documents_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Message> $messages
 * @property-read int|null $messages_count
 * @property-read \App\Models\Country $originCountry
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Payment> $paymments
 * @property-read int|null $paymments_count
 * @property-read \App\Models\User $user
 * @property-read \App\Models\VisaType $visaType
 * @method static \Database\Factories\VisaRequestFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VisaRequest newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VisaRequest newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VisaRequest query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VisaRequest whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VisaRequest whereDestinationCountryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VisaRequest whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VisaRequest whereOriginCountryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VisaRequest whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VisaRequest whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VisaRequest whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VisaRequest whereVisaTypeId($value)
 */
	class VisaRequest extends \Eloquent {}
}

namespace App\Models{
/**
 * @property string $id
 * @property string $name
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\CountryVisaType> $countryVisaTypes
 * @property-read int|null $country_visa_types_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\VisaRequest> $visaRequests
 * @property-read int|null $visa_requests_count
 * @method static \Database\Factories\VisaTypeFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VisaType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VisaType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VisaType query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VisaType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VisaType whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VisaType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VisaType whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VisaType whereUpdatedAt($value)
 */
	class VisaType extends \Eloquent {}
}

