<?php

namespace App\Http\Requests\User\Pet\Diet;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UserStorePetDietRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *s
     * @return array
     */
    public function rules(): array
    {
        return [
            'user_id' => 'required|integer|exists:users,id',
            'pet_id' => 'required|integer|exists:pets,id',
            'food_name' => 'required|string',
            'day' => 'required|string',
            'date' => 'nullable',
        ];
    }

    public function messages(): array
    {
        return [
            'user_id.exists' => 'This user does not exist!',
            'user_id.required' => 'A user is required!',
            'pet_id.exists' => 'This pet does not exist!',
            'pet_id.required' => 'A pet is required!',
        ];
    }

    protected function failedValidation(Validator $validator){
        // return errors in json object/array
        $message = $validator->errors()->all();
        throw new HttpResponseException(response()->json([
            'success' => false,
            'errors' => $message
        ]));
    }
}
