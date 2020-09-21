<?php

namespace App\Http\Requests\UserProfile;

use App\Domain\Dto\Request\UserProfile\UpdateDto;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

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
            'nextOfKinName' => 'nullable|string',
            'nextOfKinNumber' => 'nullable|string',
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

    public function convertToDto(): UpdateDto
    {
        return new UpdateDto(
            $this->firstname,
            $this->lastname,
            $this->address,
            $this->avatar,
            $this->bvn,
            $this->nextOfKinName,
            $this->nextOfKinNumber,
            $this->meta
        );
    }
}
