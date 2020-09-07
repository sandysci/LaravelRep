<?php

namespace App\Services;

use App\Domain\Dto\Request\GroupSavingUser\CreateDto;
use App\Domain\Dto\Value\GroupSavingUser\GroupSavingUserDto;
use App\Helpers\ApiResponse;
use App\Models\GroupSaving;
use App\Models\GroupSavingUser;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class GroupSavingUserService
{

    public function __construct(MailService $mailService)
    {
        $this->mailService = $mailService;
    }

    public function store(
        GroupSaving $groupSaving,
        string $participantEmail,
        ?string $callbackUrl = "http://google.com"
    ): GroupSavingUserDto {
        DB::beginTransaction();
        try {
            $getGroupSaving = GroupSavingUser::where('group_saving_id', $groupSaving->id)->count();

            $noOfParticipants = (int) $groupSaving->no_of_participants;
            if (
                $getGroupSaving === $noOfParticipants &&
                $groupSaving->owner->email !== $participantEmail
            ) {
                return new GroupSavingUserDto(false, [], 'No Group Saving available');
            }

            if ($getGroupSaving < $noOfParticipants) {
                $groupSavingUser = GroupSavingUser::firstOrCreate([
                    'group_saving_id' => $groupSaving->id,
                    'participant_email' => $participantEmail
                ]);

                $this->sendEmailToGroupParticipant($groupSaving, $participantEmail, $callbackUrl);
                DB::commit();

                return new GroupSavingUserDto(true, $groupSavingUser->toArray(), 'Group saving');
            } else {
                return new GroupSavingUserDto(false, [], 'You can only add ' . $noOfParticipants . ' participants');
            }
        } catch (\Exception $ex) {
            DB::rollback();

            Log::debug('Exception: ' . $ex->getMessage());
            return new GroupSavingUserDto(false, [], 'Exception Error');
        }
    }

    public function addUsersToGroupSaving(User $user, string $groupSavingId, CreateDto $dto): GroupSavingUserDto
    {
        $groupSaving = GroupSaving::with('groupSavingParticipants')->where([
            'owner_id' => $user->id,
            'id' => $groupSavingId
        ])->first();

        if (is_null($groupSaving)) {
            return new GroupSavingUserDto(false, [], 'No Group Saving available');
        }

        $noOfParticipants = (int) $groupSaving->no_of_participants;

        if (count($dto->emails) > $noOfParticipants) {
            return new GroupSavingUserDto(false, [], 'You can only add ' . $noOfParticipants . ' participants');
        }

        if (count($dto->emails) > $noOfParticipants) {
            return new GroupSavingUserDto(false, [], 'You can only add ' . $noOfParticipants . ' participants');
        }

        $getGroupSavingUsersCount = GroupSavingUser::where(['group_saving_id' => $groupSaving->id])->count();

        if ($getGroupSavingUsersCount + count($dto->emails) > $noOfParticipants || $getGroupSavingUsersCount === $noOfParticipants) {
            return new GroupSavingUserDto(false, [], 'You can only add ' . $noOfParticipants - $getGroupSavingUsersCount . ' participants');
        }

        foreach ($dto->emails as $email) {
            $this->store($groupSaving, $email, $dto->callbackUrl);
        }

        return new GroupSavingUserDto(true, [], "You have added new users to the group savings plan");
    }

    public function sendEmailToGroupParticipant(GroupSaving $groupSaving, string $email, string $callbackUrl): void
    {

        $this->mailService->sendEmail(
            $email,
            "You have been added to a new group savings plan",
            [
                "greeting" => "Hello,",
                "introLines" => [
                    "You have been added to the $groupSaving->name group savings plan by {$groupSaving->owner->name}",
                    "If interested, You will be required to accept the request after creating an account on our platform",
                    "This plan will require that you play the sum of $groupSaving->amount $groupSaving->plan",
                    "Please, note that the saving plan will only start if all members accept the request to join the group"
                ],
                "content" =>   "Thanks, for using Adashi",
                "actionUrl" =>  $callbackUrl,
                "actionText" => "Click to accept the request"
            ]
        );
    }
}
