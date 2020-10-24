<?php

namespace App\Services;

use App\Domain\Dto\Request\SavingCycle\CreateDto;
use App\Models\SavingCycle;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class SavingCycleService
{
    protected MailService $mailService;

    public function __construct(MailService $mailService)
    {
        $this->mailService = $mailService;
    }
    public function store(User $user, CreateDto $dto, Model $paymentGateway): SavingCycle
    {
        $savingCycle = SavingCycle::create([
            'name' => $dto->name,
            'user_id' => $user->id,
            'amount' => $dto->amount,
            'target_amount' => $dto->targetAmount,
            'plan' => $dto->plan,
            'day_of_month' => $dto->dayOfMonth < 0 ? 31 : $dto->dayOfMonth,
            'day_of_week' => $dto->dayOfWeek < 0 ? 1 : $dto->dayOfWeek,
            'hour_of_day' => $dto->hourOfDay < 0 ? 24 : $dto->hourOfDay,
            'payment_gateway_type' => get_class($paymentGateway),
            'payment_gateway_id' => $paymentGateway->id,
            'start_date' => $dto->startDate,
            'end_date' => $dto->endDate,
            'withdrawal_date' => $dto->withdrawalDate,
            'status' => "active",
            'description' => $dto->description
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
