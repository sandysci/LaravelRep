<?php

namespace App\Models;

use App\Models\Traits\Filterable;
use App\Models\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

/**
 * App\Models\Transaction
 *
 * @property string $id
 * @property string $user_id
 * @property string $reference
 * @property float $amount
 * @property string|null $payment_gateway_type
 * @property string|null $payment_gateway_id
 * @property string|null $status
 * @property string $type
 * @property int|null $attempt
 * @property string|null $transactionable_type
 * @property string|null $transactionable_id
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Transaction newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Transaction newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Transaction query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Transaction whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Transaction whereAttempt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Transaction whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Transaction whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Transaction whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Transaction whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Transaction whereModelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Transaction whereModelType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Transaction wherePaymentGatewayId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Transaction wherePaymentGatewayType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Transaction whereReference($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Transaction whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Transaction whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Transaction whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Transaction whereUserId($value)
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\OwenIt\Auditing\Models\Audit[] $audits
 * @property-read int|null $audits_count
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $transactionable
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Transaction filter(\App\Filters\BaseFilter $filter)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Transaction onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Transaction whereTransactionableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Transaction whereTransactionableType($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Transaction withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Transaction withoutTrashed()
 */
class Transaction extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use UsesUuid;
    use SoftDeletes;
    use Filterable;

    protected $guarded = [];

    public function transactionable()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
