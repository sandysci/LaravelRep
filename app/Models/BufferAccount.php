<?php

namespace App\Models;

use App\Models\Traits\Filterable;
use App\Models\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\BufferAccount
 *
 * @property string $id
 * @property string $user_id
 * @property float $amount
 * @property string|null $bufferable_type
 * @property string|null $bufferable_id
 * @property string|null $status
 * @property string $type
 * @property string $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BufferAccount newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BufferAccount newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BufferAccount query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BufferAccount whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BufferAccount whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BufferAccount whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BufferAccount whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BufferAccount whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BufferAccount whereModelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BufferAccount whereModelType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BufferAccount whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BufferAccount whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BufferAccount whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BufferAccount whereUserId($value)
 * @mixin \Eloquent
 */
class BufferAccount extends Model
{
    use UsesUuid;
    use SoftDeletes;
    use Filterable;
}
