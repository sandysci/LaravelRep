<?php

namespace App\Services;

use App\Models\BufferAccount;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class BufferAccountService
{
    public function store(User $user, $amount, Model $bufferable, array $payload): BufferAccount
    {
        $bufferAccount = new BufferAccount();
        $bufferAccount->user_id = $user->id;
        $bufferAccount->amount = $amount;
        $bufferAccount->bufferable_id = $bufferable->id;
        $bufferAccount->bufferable_type = get_class($bufferable);
        $bufferAccount->status = $payload["status"];
        $bufferAccount->type = $payload["type"];
        $bufferAccount->description = $payload["description"];

        $bufferAccount->save();
        return $bufferAccount;
    }
}
