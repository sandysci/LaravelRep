<?php

namespace App\Services;

use App\Domain\Dto\Request\GroupSavingUser\CreateDto;
use App\Domain\Dto\Request\GroupSavingUser\EditGroupSavingUserStatusDto;
use App\Domain\Dto\Value\Card\CardValidationResponseDto;
use App\Domain\Dto\Value\GroupSavingUser\GroupSavingUserDto;
use App\Models\GroupSaving;
use App\Models\GroupSavingUser;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;

class GroupSavingUserService
{
    protected GroupSavingService $groupSavingService;

    public function __construct(
        GroupSavingService $groupSavingService
    ) {
        $this->groupSavingService = $groupSavingService;
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
            if ($getGroupSaving === $noOfParticipants &&
                $groupSaving->owner->email !== $participantEmail
            ) {
                return new GroupSavingUserDto(false, [], 'No Group Saving available');
            }

            if ($getGroupSaving < $noOfParticipants) {
                $groupSavingUser = GroupSavingUser::firstOrCreate([
                    'group_saving_id' => $groupSaving->id,
                    'participant_email' => $participantEmail,
                    'group_owner_approval' => 'approved'
                ]);

                $this->sendEmailToGroupParticipant($groupSaving, $participantEmail, $callbackUrl);
                DB::commit();

                return new GroupSavingUserDto(true, $groupSavingUser->toArray(), 'Group saving');
            } else {
                return new GroupSavingUserDto(false, [], 'You can only add ' . $noOfParticipants . ' participants');
            }
        } catch (Exception $ex) {
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

        if ($getGroupSavingUsersCount + count($dto->emails) > $noOfParticipants ||
            $getGroupSavingUsersCount === $noOfParticipants
        ) {
            return new GroupSavingUserDto(
                false,
                [],
                'You can only add ' . ($noOfParticipants - $getGroupSavingUsersCount) . ' participants'
            );
        }

        foreach ($dto->emails as $email) {
            $this->store($groupSaving, $email, $dto->callbackUrl);
        }

        return new GroupSavingUserDto(true, [], "You have added new users to the group savings plan");
    }

    public function acceptGroupSavingRequest(User $user, EditGroupSavingUserStatusDto $dto): GroupSavingUserDto
    {
        if (!$dto->status) {
            return $this->rejectJoinGroupSavingRequest($user, $dto);
        }
        $groupSaving = $this->groupSavingService->getGroupSaving($dto->groupSavingId, [
            'groupSavingParticipants'
        ]);

        if (!$groupSaving) {
            return new GroupSavingUserDto(false, [], 'Invalid Group Saving Plan');
        }
        $groupSavingUser = GroupSavingUser::where([
            'group_saving_id' => $groupSaving->id,
            'participant_email' => $user->email
        ])->first();

        $validation = $this->acceptGroupRequestValidation($dto, $user, $groupSavingUser);

        //Runs a validation
        if (!$validation->status) {
            return new GroupSavingUserDto(false, [], $validation->message);
        }
        //Card/Payment Gateway Validation
        $cardValidation = $this->paymentAuthValidation($user, $dto->paymentAuth, $groupSaving);
        if (!$cardValidation->status) {
            return new GroupSavingUserDto(false, [], $cardValidation->message);
        }

        $groupSavingUser->status = 'approved';
        $groupSavingUser->payment_gateway_id = $cardValidation->card->id;
        $groupSavingUser->payment_gateway_type = get_class($cardValidation->card);
        $groupSavingUser->save();

        //Update Start date and status for group saving
        $startGroupSaving = $this->groupSavingService->startGroupSaving($dto->groupSavingId);

        return new GroupSavingUserDto(true, optional($startGroupSaving)->toArray(), 'Group saving request approved');
    }

    public function rejectJoinGroupSavingRequest(User $user, EditGroupSavingUserStatusDto $dto): GroupSavingUserDto
    {
        if ($dto->status) {
            return $this->acceptGroupSavingRequest($user, $dto);
        }

        $groupSaving = $this->groupSavingService->getGroupSaving($dto->groupSavingId, [
            'groupSavingParticipants'
        ]);

        if (!$groupSaving) {
            return new GroupSavingUserDto(false, [], 'Invalid Group Saving Plan');
        }

        $groupSavingUser = GroupSavingUser::where([
            'group_saving_id' => $groupSaving->id,
            'participant_email' => $user->email
        ])->first();

        if (!$groupSavingUser) {
            return new GroupSavingUserDto(false, [], 'Sorry, this user don\'t belong to this group');
        }
        if ($groupSavingUser->status === 'approved') {
            return new GroupSavingUserDto(false, [], 'This request can\'t be approve twice');
        }

        $groupSavingUser->status = 'disapproved';
        $groupSavingUser->save();

        return new GroupSavingUserDto(true, optional($groupSavingUser)->toArray(), 'Group saving request rejected');
    }

    protected function sendEmailToGroupParticipant(GroupSaving $groupSaving, string $email, string $callbackUrl): void
    {
        $mailService = app(MailService::class);

        $mailService->sendEmail(
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

    protected function acceptGroupRequestValidation(
        EditGroupSavingUserStatusDto $dto,
        User $user,
        ?GroupSavingUser $groupSavingUser
    ) {
        if (!$groupSavingUser) {
            return new GroupSavingUserDto(false, [], 'Sorry, this user don\'t belong to this group');
        }
        if ($groupSavingUser->status === 'approved') {
            return new GroupSavingUserDto(false, [], 'This request can\'t be approve twice');
        }

        if ($groupSavingUser->group_owner_approval === 'approved') {
            return new GroupSavingUserDto(false, [], 'The Group owner will need to approve your request');
        }

        if (!$dto->paymentAuth) {
            return new GroupSavingUserDto(false, [], 'Please add a valid payment card');
        }

        if (!$user->userProfile->bvn_verified) {
            return new GroupSavingUserDto(
                false,
                [],
                'You need to add a valid BVN, before you can join a group saving plan'
            );
        }
        return new GroupSavingUserDto(true, [], 'Validation passed');
    }

    protected function paymentAuthValidation(
        User $user,
        string $paymentAuth,
        GroupSaving $groupSaving
    ): CardValidationResponseDto {
        // Check if payment gateway exists
        $cardService = app(CardService::class);
        $paymentDetail = $cardService->getUserCard($user, $paymentAuth);
        // Check if reusable
        if (!$paymentDetail->reusable) {
            return new CardValidationResponseDto(false, null, 'This card is not reusable');
        }
        $cardExpiredDate = Carbon::createFromDate($paymentDetail->exp_year, $paymentDetail->exp_month, 1);

        $cardValidationDate = Carbon::now();
        //Check card duration
        if ($groupSaving->plan === "monthly") {
            $cardValidationDate = $cardValidationDate
                    ->addMonths($groupSaving->no_of_participants  + GroupSavingUser::MONTHLY_PLAN_CARD_VALIDATION);
        }

        if ($groupSaving->plan === "weekly") {
            $cardValidationDate = $cardValidationDate
                ->addWeeks($groupSaving->no_of_participants  + GroupSavingUser::WEEKLY_PLAN_CARD_VALIDATION);
        }

        if ($groupSaving->plan === "daily") {
            $cardValidationDate = $cardValidationDate
                ->addDays($groupSaving->no_of_participants  + GroupSavingUser::DAILY_PLAN_CARD_VALIDATION);
        }

        if ($cardValidationDate->isAfter($cardExpiredDate)) {
            return new CardValidationResponseDto(
                false,
                null,
                'Sorry, this card can\'t be used for this transaction,
                as it will expire before the end of the group saving plan'
            );
        }

        return new CardValidationResponseDto(true, $paymentDetail, 'Value Card');
    }
}
