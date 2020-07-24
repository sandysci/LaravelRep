<?php

namespace App\Services;

use App\Models\BufferAccount;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class BufferAccountService
{
    protected $bufferAccount;

    public function __construct(BufferAccount $bufferAccount)
    {
        $this->bufferAccount = $bufferAccount;
    }

    public function store(User $user, $amount, Model $model, array $payload): BufferAccount
    {
        $bufferAccount = new BufferAccount();
        $bufferAccount->user_id = $user->id;
        $bufferAccount->amount = $amount;
        $bufferAccount->model_id = $model->id;
        $bufferAccount->model_type = get_class($model);
        $bufferAccount->status = $payload["status"];
        $bufferAccount->type = $payload["type"];
        $bufferAccount->description = $payload["description"];

        $bufferAccount->save();
        return $bufferAccount;
    }
}
