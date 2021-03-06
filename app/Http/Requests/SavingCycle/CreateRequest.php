<?php

namespace App\Http\Requests\SavingCycle;

use App\Domain\Dto\Request\SavingCycle\CreateDto;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

/**
 * Class CreateRequest
 * @package App\Http\Requests\SavingCycle
 * @property string name
 * @property float amount
 * @property string|null target_amount,
 * @property string plan,
 * @property string payment_auth,
 * @property string start_date,
 * @property string end_date,
 * @property integer|null hour_of_day,
 * @property integer|null day_of_week,
 * @property integer|null day_of_month,
 * @property string|null withdrawal_date,
 * @property string|null description,
 * @property string|null status
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
            'target_amount' => 'integer',
            'plan' => 'required|in:daily,weekly,monthly',
            'hour_of_day' => 'required|integer|between:1,24',
            'payment_auth' => 'required|string',
            'start_date' => 'required|date|after:today',
            'end_date' => 'required|date|after:start_date',
            'description' => 'string',
        ];
    }

    protected function getValidatorInstance()
    {
        $validator = parent::getValidatorInstance();

        $validator->sometimes('day_of_month', 'required|integer|between:1,31', function ($input) {
            return $input->plan === "monthly";
        });

        $validator->sometimes('day_of_week', 'required|integer|between:1,7', function ($input) {
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
            $this->target_amount,
            $this->plan,
            $this->payment_auth,
            $this->start_date,
            $this->end_date,
            $this->hour_of_day,
            $this->day_of_week,
            $this->day_of_month,
            $this->withdrawal_date,
            $this->description,
            $this->status
        );
    }
}
