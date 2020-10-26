<?php

namespace App\Http\Requests\User;

use App\Domain\Dto\Request\User\UpdatePasswordDto;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

/**
 * Class UpdatePasswordRequest
 * @property string old_password
 * @property string new_password
 * @package App\Http\Requests\User
 */
class UpdatePasswordRequest extends FormRequest
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
            'old_password' => 'password:api',
            'new_password' => 'required|min:6|confirmed'
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

    public function convertToDto(): UpdatePasswordDto
    {
        return new UpdatePasswordDto(
            $this->old_password,
            $this->new_password
        );
    }
}
