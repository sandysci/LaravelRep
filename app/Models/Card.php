<?php

namespace App\Models;

use App\Models\Traits\Filterable;
use App\Models\Traits\UsesUuid;
use Eloquent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * App\Models\Card
 *
 * @property string $id
 * @property string $user_id
 * @property string|null $reference
 * @property string|null $channel
 * @property string|null $gw_customer_id
 * @property string|null $gw_customer_code
 * @property string|null $gw_authorization_code
 * @property string|null $card_type
 * @property string|null $last4
 * @property string|null $exp_month
 * @property string|null $exp_year
 * @property string|null $country_code
 * @property string|null $bank
 * @property string|null $brand
 * @property string|null $description
 * @property int|null $reusable
 * @property string|null $signature
 * @property string|null $bank_number
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Card newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Card newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Card query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Card whereBank($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Card whereBankNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Card whereBrand($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Card whereCardType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Card whereChannel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Card whereCountryCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Card whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Card whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Card whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Card whereExpMonth($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Card whereExpYear($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Card whereGwAuthorizationCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Card whereGwCustomerCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Card whereGwCustomerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Card whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Card whereLast4($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Card whereReference($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Card whereReusable($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Card whereSignature($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Card whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Card whereUserId($value)
 * @mixin Eloquent
 * @property-read User $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Card filter(\App\Filters\BaseFilter $filter)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Card onlyTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Card withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Card withoutTrashed()
 */
class Card extends Model
{
    use UsesUuid;
    use SoftDeletes;
    use Filterable;

    protected $fillable = [
        'user_id',
        'channel',
        'reference',
        'gw_customer_id',
        'gw_customer_code',
        'gw_authorization_code',
        'card_type',
        'last4',
        'exp_month',
        'exp_year',
        'country_code',
        'bank',
        'brand',
        'reusable',
        'description',
        'bank_number',
        'signature'
    ];


    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'reference',
        'gw_customer_id',
        'gw_customer_code',
        'gw_authorization_code'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
