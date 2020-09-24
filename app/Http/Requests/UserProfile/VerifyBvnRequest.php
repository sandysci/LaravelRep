<?php

namespace App\Http\Requests\UserProfile;

use App\Domain\Dto\Request\UserProfile\VerifyBvnDto;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class VerifyBvnRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'bvn' => 'required|string',
            'otp' => 'required|string',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        $message = $validator->errors()->all();
        $error  = collect($message)->unique()->first();
        throw new HttpResponseException(
            response()->json(['status' => 'error', 'data' => $message, 'message' => $error], 422)
        );
    }


    public function convertToDto(): VerifyBvnDto
    {
        return new VerifyBvnDto(
            $this->bvn,
            $this->otp
        );
    }
}
