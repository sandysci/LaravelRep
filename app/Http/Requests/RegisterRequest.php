<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class RegisterRequest extends FormRequest
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
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'sometimes|regex:/^([0-9\s\-\+\(\)]*)$/|min:10|unique:users',
            'password' => 'required',
            'callback_url' => 'required'
        ];
    }
    public function messages() {
        return [
            'email.unique' => 'You already have an existing account, Please login',
            'phone.unique' => 'This phone has been used, Please try another'
        ];
    }

    public function failedValidation(Validator $validator) {
        $message = $validator->errors()->all();
        $error  = collect($message)->unique()->first();
        throw new HttpResponseException(
            response()->json(['status' => 'error', 'data' => $message ,'message' => $error], 422));
    }

}
