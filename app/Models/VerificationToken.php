<?php

namespace App\Models;

use App\Models\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\VerificationToken
 *
 * @property string $id
 * @property string $user_id
 * @property string $token
 * @property \Illuminate\Support\Carbon $expires_at
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|VerificationToken newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|VerificationToken newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|VerificationToken query()
 * @method static \Illuminate\Database\Eloquent\Builder|VerificationToken whereExpiresAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VerificationToken whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VerificationToken whereToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VerificationToken whereUserId($value)
 * @mixin \Eloquent
 */
class VerificationToken extends Model
{
    use UsesUuid;
    use HasFactory;

     /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'expires_at'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'token',
        'expires_at'
    ];

     /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'expires_at', 'token'
    ];
    
    public const EXPIRED_AT = 30;

  
    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'token';
    }

    /**
     * @return BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return bool
     */
    public function hasExpired()
    {
        return $this->freshTimestamp()->gt($this->expires_at);
    }
}
