<?php

namespace App\Models;

use App\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Withdrawal
 *
 * @property string $id
 * @property string $user_id
 * @property string $reference
 * @property float $amount
 * @property string|null $source_type
 * @property string|null $source_id
 * @property string|null $status
 * @property string $type
 * @property int $authorize
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property string|null $current_balance
 * @property string|null $last_balance
 * @property string $destination_type
 * @property string $destination_id
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Withdrawal newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Withdrawal newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Withdrawal query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Withdrawal whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Withdrawal whereAuthorize($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Withdrawal whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Withdrawal whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Withdrawal whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Withdrawal whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Withdrawal whereReference($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Withdrawal whereSourceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Withdrawal whereSourceType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Withdrawal whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Withdrawal whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Withdrawal whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Withdrawal whereUserId($value)
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Withdrawal whereCurrentBalance($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Withdrawal whereDestinationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Withdrawal whereDestinationType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Withdrawal whereLastBalance($value)
 */
class Withdrawal extends Model
{
    use UsesUuid;
}
