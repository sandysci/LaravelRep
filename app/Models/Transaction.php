<?php

namespace App\Models;

use App\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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
 * @property string|null $model_type
 * @property string|null $model_id
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
 */
class Transaction extends Model
{
    use UsesUuid, SoftDeletes;
}
