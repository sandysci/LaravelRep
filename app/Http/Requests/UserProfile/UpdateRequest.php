<?php

namespace App\Http\Requests\UserProfile;

use App\Domain\Dto\Request\UserProfile\UpdateDto;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

/**
 * Class UpdateRequest
 * @package App\Http\Requests\UserProfile
 * @property string|null firstname
 * @property string|null lastname
 * @property string|null address
 * @property string|null avatar
 * @property string|null bvn
 * @property string|null next_of_kin_name
 * @property string|null next_of_kin_number
 * @property string|null next_of_kin_email
 * @property string|null date_of_birth
 * @property string|null next_of_kin_relationship
 * @property string|null meta
 */
class UpdateRequest extends FormRequest
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
            'firstname' => 'nullable|string',
            'lastname' => 'nullable|string',
            'address' => 'nullable|string',
            'avatar' => 'nullable|string',
            'bvn' => 'nullable|string',
            'next_of_kin_name' => 'nullable|string',
            'next_of_kin_number' => 'nullable|string',
            'next_of_kin_email' => 'nullable|string',
            'next_of_kin_relationship' => 'nullable|string',
            'date_of_birth' => 'nullable|date|before:13 years ago',
            'meta' => 'nullable',
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

    /**
     * @return UpdateDto
     */
    public function convertToDto(): UpdateDto
    {
        return new UpdateDto(
            $this->firstname,
            $this->lastname,
            $this->address,
            $this->avatar,
            $this->bvn,
            $this->next_of_kin_name,
            $this->next_of_kin_number,
            $this->next_of_kin_email,
            $this->next_of_kin_relationship,
            $this->date_of_birth,
            $this->meta
        );
    }
}
