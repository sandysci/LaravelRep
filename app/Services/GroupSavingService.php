<?php

namespace App\Services;

use App\Domain\Dto\Request\GroupSaving\CreateDto;
use App\Models\GroupSaving;
use App\Models\GroupSavingUser;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class GroupSavingService
{
    protected GroupSavingUserService $groupSavingUserService;
    protected MailService $mailService;

    public function __construct(
        GroupSavingUserService $groupSavingUserService,
        MailService $mailService
    ) {
        $this->groupSavingUserService = $groupSavingUserService;
        $this->mailService = $mailService;
    }

    public function store(CreateDto $dto, User $user): GroupSaving
    {
        $groupSaving =  GroupSaving::create([
            'name' => $dto->name,
            'owner_id' => $user->id,
            'amount' => $dto->amount,
            'plan' => $dto->plan,
            'no_of_participants' => $dto->noOfParticipants,
            'day_of_month' => $dto->dayOfMonth ?? 31,
            'day_of_week' => $dto->dayOfWeek ?? 1,
            'hour_of_day' => $dto->hourOfDay ?? 24,
            'description' => $dto->description
        ]);

        $this->sendEmailToGroupOwner($groupSaving);

        $this->groupSavingUserService->store($groupSaving, $user->email, $dto->callbackUrl);

        return $groupSaving;
    }

    public function addUsersToGroupSaving(User $user, string $groupSavingId, array $emails): GroupSaving
    {
        $groupSaving = GroupSaving::with('groupSavingParticipants')->where([
            'owner_id' => $user->id,
            'id' => $groupSavingId
        ])->first();

        if (!$groupSaving) {
            return collect([]);
        }

        foreach ($emails as $email) {
            $this->groupSavingUserService->store($groupSaving, $email);
        }
        return $groupSaving;
    }

    public function getAllUserGroupSavings(User $user): Collection
    {
        return GroupSaving::with(
            'groupSavingHistories',
            'groupSavingParticipants'
        )->where('owner_id', $user->id)->get();
    }

    public function getGroupSavings(array $conditions, array $with = []): Collection
    {
        //Add with to avoid N + 1 issues
        return GroupSaving::where($conditions)->with('groupSavingHistories')->get();
    }

    public function getGroupSaving(string $id, array $with = []): ?GroupSaving
    {
        return GroupSaving::where('id', $id)->with($with)->first();
    }

    public function getAllGroupSavings(): Collection
    {
        return GroupSaving::with('groupSavingHistories')->get();
    }

    public function startGroupSaving(string $groupSavingId): ?GroupSaving
    {
        $groupSaving = GroupSaving::where('id', $groupSavingId)->first();
        if (!$groupSaving) {
            return null;
        }

        $groupSavingUsers = GroupSavingUser::where([
            'id' => $groupSavingId,
            'status' => 'approved',
            'group_owner_approval' => 'approved'
        ])->count();

        if ($groupSavingUsers !== $groupSaving->no_of_participants) {
            return null;
        }

        $groupSaving->start_date = Carbon::now();
        $groupSaving->save();

        //Send email to all participants

        return $groupSaving;
    }

    protected function sendEmailToGroupOwner(GroupSaving $groupSaving): void
    {
        $this->mailService->sendEmail(
            $groupSaving->owner->email,
            "You have created a new group savings plan",
            [
                "greeting" => "Hello,", $groupSaving->owner->name,
                "introLines" => [
                    "Kindly, You just created a new group savings plan",
                    "You will be required to add the $groupSaving->no_of_participants emails of the other participants",
                    "After adding a user, the user will be send a notification, that will prompt the user to accept the request",
                    "Please, note that the saving plan will only start if all members accept the request to join the group"
                ],
                "content" =>   "Thanks, for using Adashi"
            ]
        );
    }
}
