<?php

namespace App\Services;

use App\Domain\Dto\Request\UserProfile\ResolveBvnDto;
use App\Domain\Dto\Request\UserProfile\UpdateDto;
use App\Domain\Dto\Request\UserProfile\VerifyBvnDto;
use App\Domain\Dto\Value\UserProfile\BvnVerificationResponseDto;
use App\Domain\Dto\Value\UserProfile\UpdateResponseDto;
use App\Models\User;
use App\Models\UserProfile;
use App\Services\Payment\PaystackService;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Redis;

class UserProfileService
{
    protected PaystackService $paystackService;
    protected OtpService $otpService;
    protected SmsService $smsService;

    public function __construct(
        PaystackService $paystackService,
        OtpService $otpService,
        SmsService $smsService
    ) {
        $this->paystackService = $paystackService;
        $this->otpService = $otpService;
        $this->smsService = $smsService;
    }

    public function update(UpdateDto $dto, User $user): UpdateResponseDto
    {
        $currentUser = User::where('id', $user->id)->with('userProfile')->first();

        if (is_null($currentUser->userProfile)) {
            return new UpdateResponseDto(false, [], 'This profile doesn\'t exist');
        }
        $currentUser->userProfile->firstname = $dto->firstname ?? $currentUser->userProfile->firstname;
        $currentUser->userProfile->lastname = $dto->lastname ??  $currentUser->userProfile->lastname;
        $currentUser->userProfile->date_of_birth = Carbon::parse($dto->dateOfBirth) ??
                                                    $currentUser->userProfile->date_of_birth;
        $currentUser->userProfile->address = $dto->address ?? $currentUser->userProfile->address;
        $currentUser->userProfile->avatar = $dto->avatar ?? $currentUser->userProfile->avatar;
        $currentUser->userProfile->next_of_kin_name = $dto->nextOfKinName ??
                                                        $currentUser->userProfile->next_of_kin_name;
        $currentUser->userProfile->next_of_kin_number = $dto->nextOfKinNumber ??
                                                        $currentUser->userProfile->next_of_kin_number;
        $currentUser->userProfile->next_of_kin_email = $dto->nextOfKinEmail ??
                                                        $currentUser->userProfile->next_of_kin_email;
        $currentUser->userProfile->next_of_kin_relationship = $dto->nextOfKinRelationship ??
                                                                $currentUser->userProfile->next_of_kin_relationship;
        $currentUser->userProfile->meta = $dto->meta ?? $currentUser->userProfile->meta;
        $currentUser->userProfile->save();

        return new UpdateResponseDto(true, $currentUser->toArray(), 'Profile updated');
    }

    public function resolveBvn(ResolveBvnDto $dto, User $user)
    {
        $userProfile = UserProfile::where('user_id', $user->id)->with('user')->first();
        $response = $this->paystackService->resolveBvn($dto->bvn);
        if (!$response->status) {
            return new UpdateResponseDto(false, [], $response->message);
        }

        if (is_null($response->data['mobile'])) {
            return new UpdateResponseDto(false, [], 'No mobile number attached to BVN');
        }
        $otp = $this->otpService->create(
            get_class($userProfile),
            $response->data['mobile'],
            UserProfile::BVN_OTP_LENGTH,
            UserProfile::BVN_OTP_VERIFICATION_PERIOD
        );
        $redisValue = (object) [
            'bvn_data' => $response->data,
            'otp' => $otp
        ];

        Redis::set(
            'user:' . $user->id . ':bvn-verification',
            $redisValue,
            'EX',
            UserProfile::BVN_CACHE_VERIFICATION_PERIOD
        );

        $this->smsService->sendSms(
            $response->data['mobile'],
            "Adashi: Your OTP for BVN verification is " . $otp->token,
            "ADASHI"
        );
        return new UpdateResponseDto(true, [], "OTP has been sent to the phone number used in registering the BVN");
    }

    public function bvnVerification(VerifyBvnDto $dto, User $user)
    {
        $userBvnInfo = Redis::get('user:' . $user->id . ':bvn-verification');
        if (is_null($userBvnInfo)) {
            return new BvnVerificationResponseDto(false, 'Otp has expired');
        }

        if ($userBvnInfo->otp !== $dto->otp || $userBvnInfo->bvn_data['bvn'] !== $dto->bvn) {
            return new BvnVerificationResponseDto(false, 'Invalid Credentials');
        }
        $otpValidation = $this->otpService->validate(
            get_class(new UserProfile()),
            $userBvnInfo->bvn_data['mobile'],
            $dto->otp
        );

        if (!$otpValidation->status) {
            return new BvnVerificationResponseDto($otpValidation->status, $otpValidation->message);
        }
        //Check if BVN has been used by another user;
        $checkIfBvnExist = UserProfile::where(['bvn' => $dto->bvn, 'bvn_verified' => true])->first();
        if ($checkIfBvnExist) {
            return new BvnVerificationResponseDto(false, 'BVN already verified by a user');
        }

        $this->updateUserProfileUsingBvnResponse($userBvnInfo, $user);
        return new BvnVerificationResponseDto(true, 'BVN now verified and user profile updated');
    }

    private function updateUserProfileUsingBvnResponse($userBvnInfo, User $user): void
    {
        $userProfile = UserProfile::where('user_id', $user->id)->first();
        $userProfile->bvn = $userBvnInfo->bvn_data['bvn'] ?? '';
        $userProfile->bvn_verified = true;
        $userProfile->firstname = $userBvnInfo->bvn_data['first_name'] ?? $userProfile->firstname;
        $userProfile->lastname = $userBvnInfo->bvn_data['last_name'] ?? $userProfile->lastname;
        $userProfile->date_of_birth = $userBvnInfo->bvn_data['dob'] ?
            Carbon::parse($userBvnInfo->bvn_data['dob']) :
            $userProfile->date_of_birth;
        $userProfile->save();
    }
}
