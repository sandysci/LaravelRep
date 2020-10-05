<?php

namespace App\Services;

use App\Domain\Dto\Request\SavingCycle\CreateDto;
use App\Models\SavingCycle;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class SavingCycleService
{
    protected $mailService;

    public function __construct(MailService $mailService)
    {
        $this->mailService = $mailService;
    }
    public function store(User $user, CreateDto $request, Model $paymentGateway): SavingCycle
    {
        $savingCycle = SavingCycle::create([
            'name' => $request->name,
            'user_id' => $user->id,
            'amount' => $request->amount,
            'target_amount' => $request->target_amount,
            'plan' => $request->plan,
            'day_of_month' => $request->day_of_month ?? 31,
            'day_of_week' => $request->day_of_week ?? 1,
            'hour_of_day' => $request->hour_of_day ?? 24,
            'payment_gateway_type' => get_class($paymentGateway),
            'payment_gateway_id' => $paymentGateway->id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'withdrawal_date' => $request->withdrawal_date,
            'status' => "active",
            'description' => $request->description
        ]);
        $this->sendEmailToUser($savingCycle);
        return $savingCycle;
    }

    public function getAllUserSavingCycles(): Collection
    {
        return SavingCycle::where('user_id', request()->user())->with('savingCycleHistories')->get();
    }

    public function getSavingCycles(array $conditions, array $with = []): Collection
    {
        //Add with to avoid N + 1 issues
        return SavingCycle::where($conditions)->with('savingCycleHistories')->get();
    }


    public function getAllSavingCycles(): Collection
    {
        return SavingCycle::with('savingCycleHistories')->get();
    }

    public function updateSavingCycleStatus(string $id): ?SavingCycle
    {
        $savingCycle = SavingCycle::find($id);

        if ($savingCycle) {
            $savingCycle->status = $id;
            $savingCycle->save();
        }

        return $savingCycle;
    }

    public function sendEmailToUser(SavingCycle $savingCycle): void
    {
        $this->mailService->sendEmail(
            $savingCycle->user->email,
            "You have created a new savings plan",
            [
                "introLines" => ["Kindly, You just created a new savings plan, you will be debited #" . $savingCycle->amount],
                "content" =>   "Thanks, for using Adashi",
                "greeting" => "Hello," . $savingCycle->user->name,
            ]
        );
    }
}
