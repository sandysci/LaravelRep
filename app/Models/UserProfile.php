<?php

namespace App\Models;

use App\Models\Traits\Filterable;
use App\Models\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

/**
 * App\Models\UserProfile
 *
 * @property string $id
 * @property string $user_id
 * @property string|null $firstname
 * @property string|null $lastname
 * @property string|null $address
 * @property string|null $avatar
 * @property string|null $next_of_kin_name
 * @property string|null $next_of_kin_number
 * @property string|null $meta
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserProfile newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserProfile newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserProfile query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserProfile whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserProfile whereAvatar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserProfile whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserProfile whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserProfile whereFirstname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserProfile whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserProfile whereLastname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserProfile whereMeta($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserProfile whereNextOfKinName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserProfile whereNextOfKinNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserProfile whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserProfile whereUserId($value)
 * @mixin \Eloquent
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserProfile filter(\App\Filters\BaseFilter $filter)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\UserProfile onlyTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\UserProfile withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\UserProfile withoutTrashed()
 * @property string|null $date_of_birth
 * @property string|null $bvn
 * @property int $bvn_verified
 * @property-read \Illuminate\Database\Eloquent\Collection|\OwenIt\Auditing\Models\Audit[] $audits
 * @property-read int|null $audits_count
 * @method static \Illuminate\Database\Eloquent\Builder|UserProfile whereBvn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserProfile whereBvnVerified($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserProfile whereDateOfBirth($value)
 */
class UserProfile extends Model implements Auditable
{
    use UsesUuid;
    use SoftDeletes;
    use Filterable;
    use \OwenIt\Auditing\Auditable;

    protected $guarded = [];
    protected $touches = [
        'user'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'bvn'
    ];

    public const BVN_OTP_LENGTH = 6;
    public const BVN_OTP_VERIFICATION_PERIOD = 10; //In Minutes
    public const BVN_CACHE_VERIFICATION_PERIOD = 600; //In Seconds

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function setBvnAttribute($value)
    {
        if (empty($value)) { // will check for empty string
            $this->attributes['bvn'] = null;
        } else {
            $this->attributes['bvn'] = $value;
        }
    }
}
