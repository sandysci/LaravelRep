<?php

namespace App\Http\Requests\User;

use App\Domain\Dto\Request\User\CreateDto;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Propaganistas\LaravelPhone\PhoneNumber;

/**
 * Class CreateRequest
 * @package App\Http\Requests\User
 * @property string name
 * @property string email
 * @property string phone
 * @property string phone_country
 * @property string password
 * @property string callback_url
 * @property string|null type
 */
class CreateRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|indisposable|max:255|unique:users',
            'phone' => ['required', 'phone'],
            'phone_country' => ['required_with:phone',
                Rule::unique('users')->where(function ($query) {
                    return $query->where('phone', PhoneNumber::make($this->phone, $this->phone_country)->formatE164());
                })
            ],
            'password' => 'required',
            'callback_url' => 'required'
        ];
    }
    public function messages()
    {
        return [
            'email.unique' => 'You already have an existing account, Please login',
            'phone_country.unique' => 'This phone number has been registered, Please try another'
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

    public function convertToDto(): CreateDto
    {
        return new CreateDto(
            $this->name,
            $this->email,
            $this->phone,
            $this->phone_country,
            $this->password,
            $this->callback_url,
            $this->type
        );
    }
}
