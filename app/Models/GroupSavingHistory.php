<?php

namespace App\Models;

use App\Models\Traits\Filterable;
use App\Models\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\GroupSavingHistory
 *
 * @property string $id
 * @property string $user_id
 * @property string $group_saving_id
 * @property string $reference
 * @property float $amount
 * @property string|null $status
 * @property string $type
 * @property int|null $attempt
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\GroupSaving $groupSaving
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\GroupSavingHistory filter(\App\Filters\BaseFilter $filter)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\GroupSavingHistory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\GroupSavingHistory newQuery()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\GroupSavingHistory onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\GroupSavingHistory query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\GroupSavingHistory whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\GroupSavingHistory whereAttempt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\GroupSavingHistory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\GroupSavingHistory whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\GroupSavingHistory whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\GroupSavingHistory whereGroupSavingId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\GroupSavingHistory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\GroupSavingHistory whereReference($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\GroupSavingHistory whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\GroupSavingHistory whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\GroupSavingHistory whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\GroupSavingHistory whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\GroupSavingHistory withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\GroupSavingHistory withoutTrashed()
 * @mixin \Eloquent
 */
class GroupSavingHistory extends Model
{
    use UsesUuid;
    use SoftDeletes;
    use Filterable;

    protected $guarded = [];

    public function groupSaving()
    {
        return $this->belongsTo(GroupSaving::class);
    }
}
