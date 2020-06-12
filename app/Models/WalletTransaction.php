<?php

namespace App\Models;

use App\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\WalletTransaction
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\WalletTransaction newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\WalletTransaction newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\WalletTransaction query()
 * @mixin \Eloquent
 */
class WalletTransaction extends Model
{
    use UsesUuid;
}
