<?php

namespace App\Models;

use App\Models\Traits\Filterable;
use App\Models\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

/**
 * App\Models\SavingCycle
 *
 * @property string $id
 * @property string $name
 * @property string $user_id
 * @property float $amount
 * @property string $plan
 * @property string|null $day_of_month
 * @property string|null $day_of_week
 * @property string|null $hour_of_day
 * @property string $payment_gateway_type
 * @property string $payment_gateway_id
 * @property string|null $start_date
 * @property string|null $end_date
 * @property string|null $withdrawal_date
 * @property string $status
 * @property string $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SavingCycle newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SavingCycle newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SavingCycle query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SavingCycle whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SavingCycle whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SavingCycle whereDayOfMonth($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SavingCycle whereDayOfWeek($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SavingCycle whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SavingCycle whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SavingCycle whereEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SavingCycle whereHourOfDay($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SavingCycle whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SavingCycle whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SavingCycle wherePaymentGatewayId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SavingCycle wherePaymentGatewayType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SavingCycle wherePlan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SavingCycle whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SavingCycle whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SavingCycle whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SavingCycle whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SavingCycle whereWithdrawalDate($value)
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\OwenIt\Auditing\Models\Audit[] $audits
 * @property-read int|null $audits_count
 * @property-read \App\Models\Card $paymentGateway
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\SavingCycleHistory[] $savingCycleHistories
 * @property-read int|null $saving_cycle_histories_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Transaction[] $transactions
 * @property-read int|null $transactions_count
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SavingCycle filter(\App\Filters\BaseFilter $filter)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\SavingCycle onlyTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\SavingCycle withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\SavingCycle withoutTrashed()
 * @property string|null $target_amount
 * @method static \Illuminate\Database\Eloquent\Builder|SavingCycle whereTargetAmount($value)
 */
class SavingCycle extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use UsesUuid;
    use SoftDeletes;
    use Filterable;

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function savingCycleHistories()
    {
        return $this->hasMany(SavingCycleHistory::class);
    }

    public function paymentGateway()
    {
        return $this->belongsTo(Card::class);
    }

    public function transactions()
    {
        return $this->morphMany(Transaction::class, 'transactionable');
    }
}
