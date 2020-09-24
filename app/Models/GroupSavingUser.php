<?php

namespace App\Models;

use App\Models\Traits\Filterable;
use App\Models\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\GroupSavingUser
 *
 * @property string $id
 * @property string $group_saving_id
 * @property string $participant_email
 * @property string|null $payment_gateway_type
 * @property string|null $payment_gateway_id
 * @property string $status
 * @property string $group_owner_approval
 * @property int $payout
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\GroupSaving $groupSaving
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\GroupSavingUser filter(\App\Filters\BaseFilter $filter)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\GroupSavingUser newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\GroupSavingUser newQuery()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\GroupSavingUser onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\GroupSavingUser query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\GroupSavingUser whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\GroupSavingUser whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\GroupSavingUser whereGroupOwnerApproval($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\GroupSavingUser whereGroupSavingId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\GroupSavingUser whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\GroupSavingUser whereParticipantEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\GroupSavingUser wherePaymentGatewayId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\GroupSavingUser wherePaymentGatewayType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\GroupSavingUser wherePayout($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\GroupSavingUser whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\GroupSavingUser whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\GroupSavingUser withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\GroupSavingUser withoutTrashed()
 * @mixin \Eloquent
 */
class GroupSavingUser extends Model
{
    use UsesUuid;
    use SoftDeletes;
    use Filterable;

    protected $guarded = [];
    public const MONTHLY_PLAN_CARD_VALIDATION = 3;
    public const WEEKLY_PLAN_CARD_VALIDATION = 3;
    public const DAILY_PLAN_CARD_VALIDATION = 14;

    public function user()
    {
        return $this->belongsTo(User::class, 'participant_email', 'email');
    }

    public function groupSaving()
    {
        return $this->belongsTo(GroupSaving::class);
    }
}
