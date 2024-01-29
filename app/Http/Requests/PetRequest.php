<?php

namespace App\Http\Requests;

use App\Enum\StatusEnum;
use Illuminate\Foundation\Http\FormRequest;

class PetRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255'
            ],
            'status' => [
                'required',
                'string',
                'in:' . implode(',', array_keys(StatusEnum::getList()))
            ]
        ];
    }

    public function messages(): array
    {
        return [
            //name
            'name.required' => 'Pole nazwa jest wymagane',
            'name.string' => 'Pole nazwa musi być ciągiem znaków',
            'name.max' => 'Podana wartość jest za długa (max 255 znaków)',
            //status
            'status.required' => 'Pole status jest wymagane',
            'status.string' => 'Pole status musi być ciągiem znaków',
            'status.in' => 'Zła wartość',
        ];
    }
}
