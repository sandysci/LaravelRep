<?php

namespace App\Models;

use App\Models\Traits\Filterable;
use App\Models\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\Otp
 *
 * @property int $id
 * @property string $identifier_type
 * @property string $identifier_id
 * @property string $token
 * @property int $validity
 * @property int $valid
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Otp newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Otp newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Otp query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Otp whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Otp whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Otp whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Otp whereIdentifierId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Otp whereIdentifierType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Otp whereToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Otp whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Otp whereValid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Otp whereValidity($value)
 * @mixin \Eloquent
 */
class Otp extends Model
{
    use UsesUuid;
    use SoftDeletes;
    use Filterable;

    protected $guarded = [];
}
