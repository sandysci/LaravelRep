<?php

namespace App\Services;

use App\Models\SavingCycle;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class SavingCycleService
{
    protected $savingCycle;

    public function __construct(SavingCycle $savingCycle)
    {
        $this->savingCycle = $savingCycle;
    }

    public function store(User $user, Request $payload, Model $paymentGateway): SavingCycle
    {
        return $this->savingCycle->create([
            'name' => $payload->name,
            'user_id' => $user->id,
            'amount' => $payload->amount,
            'plan' => $payload->plan,
            'day_of_month' => $payload->day_of_month ?? 31,
            'day_of_week' => $payload->day_of_week ?? 1,
            'hour_of_day' => $payload->hour_of_day ?? 24,
            'payment_gateway_type' => get_class($paymentGateway),
            'payment_gateway_id' => $paymentGateway->id,
            'start_date' => $payload->start_date,
            'end_date' => $payload->end_date,
            'withdrawal_date' => $payload->withdrawal_date,
            'status' => $payload->status,
            'description' => $payload->description
        ]);
    }

    public function getAllUserSavingCycles(): Collection
    {
        return $this->savingCycle->where('user_id', request()->user())->get();
    }

    public function getSavingCycles(array $conditions, array $with = []): Collection
    {
        //Add with to avoid N + 1 issues
        return $this->savingCycle->where($conditions)->with($with)->get();
    }

    public function getAllSavingCycles(): Collection
    {
        return $this->savingCycle->get();
    }

    public function updateSavingCycleStatus(string $id): ?SavingCycle
    {
        $savingCycle = $this->savingCycle->find($id);

        if ($savingCycle) {
            $savingCycle->status = $id;
            $savingCycle->save();
        }

        return $savingCycle;
    }
}
