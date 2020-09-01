<?php

namespace App\Services;

use App\Domain\Dto\Request\GroupSaving\CreateDto;
use App\Models\GroupSaving;
use App\Models\User;
use Illuminate\Support\Collection;

class GroupSavingService
{
    protected $groupSaving;

    public function __construct(GroupSaving $groupSaving)
    {
        $this->groupSaving = $groupSaving;
    }

    public function store(CreateDto $request, User $user): GroupSaving
    {
        $groupSaving =  GroupSaving::create([
            'name' => $request->name,
            'user_id' => $user->id,
            'amount' => $request->amount,
            'plan' => $request->plan,
            'day_of_month' => $request->day_of_month ?? 31,
            'day_of_week' => $request->day_of_week ?? 1,
            'hour_of_day' => $request->hour_of_day ?? 24,
            'status' => $request->status,
            'description' => $request->description
        ]);

        $this->sendEmailToGroupOwner($groupSaving);

        return $groupSaving;
    }

    public function getAllUserGroupSavings(): Collection
    {
        return GroupSaving::where('owner_id', request()->user()->id)->with(
            'groupSavingHistories',
            'groupSavingParticipants'
        )->get();
    }

    public function getGroupSavings(array $conditions, array $with = []): Collection
    {
        //Add with to avoid N + 1 issues
        return $this->savingCycle->where($conditions)->with('savingCycleHistories')->get();
    }


    public function getAllGroupSavings(): Collection
    {
        return $this->savingCycle->with('savingCycleHistories')->get();
    }

    public function sendEmailToGroupOwner(GroupSaving $groupSaving): void
    {

        $this->mailService->sendEmail(
            $groupSaving->user->email,
            "You have created a new group savings plan",
            [
                "greeting" => "Hello," . $groupSaving->user->name,
                "introLines" => [
                    "Kindly, You just created a new group savings plan",
                    "You will be required to add the ' .$groupSaving->no_of_participant. ' emails of the other participants",
                    "After adding a user, the user will be send a notification, that will prompt the user to accept the request",
                    "Please, note that the saving plan will only start if all members accept the request to join the group"
                ],
                "content" =>   "Thanks, for using Adashi"
            ]
        );
    }
}
