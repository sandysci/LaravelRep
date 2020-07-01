<?php

namespace App\Models;

use App\Models\Traits\Filterable;
use App\Models\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\BankDetail
 *
 * @property string $id
 * @property string $user_id
 * @property int|null $account_number
 * @property string|null $bank_code
 * @property string|null $recipient_code
 * @property string|null $meta
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BankDetail newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BankDetail newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BankDetail query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BankDetail whereAccountNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BankDetail whereBankCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BankDetail whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BankDetail whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BankDetail whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BankDetail whereMeta($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BankDetail whereRecipientCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BankDetail whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BankDetail whereUserId($value)
 * @mixin \Eloquent
 */
class BankDetail extends Model
{
    use UsesUuid, SoftDeletes, Filterable;
}
