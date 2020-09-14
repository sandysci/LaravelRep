<?php

namespace App\Http\Requests\GroupSavingUser;

use App\Domain\Dto\Request\GroupSavingUser\EditGroupSavingUserStatusDto;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateStatusRequest extends FormRequest
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
            'status' => 'required|boolean',
            'groupSavingId' => 'required|string',
            'paymentAuth' => 'string'
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

    public function convertToDto(): EditGroupSavingUserStatusDto
    {
        return new EditGroupSavingUserStatusDto(
            $this->status,
            $this->groupSavingId,
            $this->paymentAuth
        );
    }
}
