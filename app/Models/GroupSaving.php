<?php

namespace App\Models;

use App\Models\Traits\Filterable;
use App\Models\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Models\Audit;

/**
 * App\Models\GroupSaving
 *
 * @property string $id
 * @property string $name
 * @property string $owner_id
 * @property float $amount
 * @property string $plan
 * @property int|null $day_of_month
 * @property int|null $day_of_week
 * @property int|null $hour_of_day
 * @property string|null $start_date
 * @property string|null $end_date
 * @property string $no_of_participant
 * @property string $status
 * @property string|null $description
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read Collection|GroupSavingHistory[] $groupSavingHistories
 * @property-read int|null $group_saving_histories_count
 * @property-read Collection|GroupSavingUser[] $groupSavingUser
 * @property-read int|null $group_saving_user_count
 * @property-read User $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\GroupSaving filter(\App\Filters\BaseFilter $filter)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\GroupSaving newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\GroupSaving newQuery()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\GroupSaving onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\GroupSaving query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\GroupSaving whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\GroupSaving whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\GroupSaving whereDayOfMonth($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\GroupSaving whereDayOfWeek($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\GroupSaving whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\GroupSaving whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\GroupSaving whereEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\GroupSaving whereHourOfDay($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\GroupSaving whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\GroupSaving whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\GroupSaving whereNoOfParticipant($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\GroupSaving whereOwnerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\GroupSaving wherePlan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\GroupSaving whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\GroupSaving whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\GroupSaving whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\GroupSaving withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\GroupSaving withoutTrashed()
 * @mixin \Eloquent
 * @property int $no_of_participants
 * @property-read Collection|Audit[] $audits
 * @property-read int|null $audits_count
 * @property-read Collection|GroupSavingUser[] $groupSavingParticipants
 * @property-read int|null $group_saving_participants_count
 * @property-read User $owner
 * @method static \Illuminate\Database\Eloquent\Builder|GroupSaving whereNoOfParticipants($value)
 */
class GroupSaving extends Model implements Auditable
{
    use UsesUuid;
    use SoftDeletes;
    use Filterable;
    use \OwenIt\Auditing\Auditable;

    protected $guarded = [];

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function groupSavingHistories()
    {
        return $this->hasMany(GroupSavingHistory::class);
    }

    public function groupSavingParticipants()
    {
        return $this->hasMany(GroupSavingUser::class);
    }
}
