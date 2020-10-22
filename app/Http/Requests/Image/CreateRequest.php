<?php

namespace App\Http\Requests\Image;

use App\Domain\Dto\Request\Image\CreateDto;
use Illuminate\Foundation\Http\FormRequest;

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
            'image' => 'required|image|max:8192',
        ];
    }

    public function convertToDto(): CreateDto
    {
        return new CreateDto(
            $this->image
        );
    }
}
