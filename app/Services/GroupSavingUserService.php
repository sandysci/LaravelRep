<?php

namespace App\Services;

use App\Domain\Dto\Value\GroupSavingUser\GroupSavingUserDto;
use App\Helpers\ApiResponse;
use App\Models\GroupSaving;
use App\Models\GroupSavingUser;
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
        try {
            $getGroupSaving = GroupSavingUser::where('group_saving_id', $groupSaving->id)->count();

            if (
                $getGroupSaving === $groupSaving->no_of_participants &&
                $groupSaving->owner->email !== $participantEmail
            ) {
                throw new \Exception('Maxinum number of participants reached');
            } elseif ($getGroupSaving <= $groupSaving->no_of_participants) {
                $groupSavingUser = GroupSavingUser::firstOrCreate([
                    'group_saving_id' => $groupSaving->id,
                    'participant_email' => $participantEmail
                ]);

                $this->sendEmailToGroupParticipant($groupSaving, $participantEmail, $callbackUrl);

                return new GroupSavingUserDto(true, $groupSavingUser->toArray(), 'Group saving');
            }
            return $getGroupSaving;
        } catch (\Exception $ex) {
            Log::debug('Exception: ' . $ex->getMessage());
            return new GroupSavingUserDto(false, [], 'Error');
        }
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
