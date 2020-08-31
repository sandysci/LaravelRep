<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\PasswordReset
 *
 * @property int $id
 * @property string $email
 * @property string $token
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property string|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PasswordReset newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PasswordReset newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PasswordReset query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PasswordReset whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PasswordReset whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PasswordReset whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PasswordReset whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PasswordReset whereToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PasswordReset whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class PasswordReset extends Model
{
    protected $fillable = [
        'email', 'token'
    ];
}
