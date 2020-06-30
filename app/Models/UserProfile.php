<?php

namespace App\Models;

use App\Models\Traits\Filterable;
use App\Models\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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
 */
class UserProfile extends Model
{
    use UsesUuid, SoftDeletes, Filterable;

    protected $guarded = [];
    protected $touches = [
        'user'
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
}
