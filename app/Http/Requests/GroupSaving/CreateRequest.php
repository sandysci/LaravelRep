<?php

namespace App\Http\Requests\GroupSaving;

use App\Domain\Dto\Request\GroupSaving\CreateDto;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

/**
 * Class CreateRequest
 * @package App\Http\Requests\GroupSaving
 * @property string name
 * @property float amount
 * @property string plan
 * @property integer no_of_participants
 * @property string callback_url
 * @property integer hour_of_day
 * @property integer day_of_week
 * @property integer day_of_month
 * @property string description
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
            'name' => 'required|string',
            'amount' => 'required|integer',
            'plan' => 'required|in:daily,weekly,monthly',
            'no_of_participants' => 'required|integer|min:2',
            'hour_of_day' => 'required|integer|between:1,24',
            'description' => 'string',
            'callback_url' => 'required|string'
        ];
    }

    protected function getValidatorInstance()
    {
        $validator = parent::getValidatorInstance();

        $validator->sometimes('dayOfMonth', 'required|integer|between:1,31', function ($input) {
            return $input->plan === "monthly";
        });

        $validator->sometimes('dayOfWeek', 'required|integer|between:1,7', function ($input) {
            return $input->plan === "weekly";
        });

        return $validator;
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
            $this->amount,
            $this->plan,
            $this->no_of_participants,
            $this->callback_url,
            $this->hour_of_day,
            $this->day_of_week,
            $this->day_of_month,
            $this->description
        );
    }
}
