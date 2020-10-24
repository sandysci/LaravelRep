<?php

namespace App\Http\Requests\GroupSavingUser;

use App\Domain\Dto\Request\GroupSavingUser\EditGroupSavingUserStatusDto;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

/**
 * Class UpdateStatusRequest
 * @package App\Http\Requests\GroupSavingUser
 * @property boolean status
 * @property string group_saving_id
 * @property string payment_auth

 */
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
            'group_saving_id' => 'required|string',
            'payment_auth' => 'string'
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
            $this->group_saving_id,
            $this->payment_auth
        );
    }
}
