<?php

namespace App\Models;

use App\Models\Traits\Filterable;
use App\Models\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\SavingCycleHistory
 *
 * @property int $id
 * @property string $saving_cycle_id
 * @property string|null $status
 * @property string $type
 * @property string $description
 * @property int|null $attempt
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SavingCycleHistory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SavingCycleHistory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SavingCycleHistory query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SavingCycleHistory whereAttempt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SavingCycleHistory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SavingCycleHistory whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SavingCycleHistory whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SavingCycleHistory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SavingCycleHistory whereSavingCycleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SavingCycleHistory whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SavingCycleHistory whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SavingCycleHistory whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property string $user_id
 * @property string $reference
 * @property float $amount
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SavingCycleHistory whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SavingCycleHistory whereReference($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SavingCycleHistory whereUserId($value)
 */
class SavingCycleHistory extends Model
{
    use UsesUuid;
    use SoftDeletes;
    use Filterable;
}
