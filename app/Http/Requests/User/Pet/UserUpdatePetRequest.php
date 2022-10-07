<?php

namespace App\Http\Requests\User\Pet;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UserUpdatePetRequest extends FormRequest
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
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'user_id' => 'required|integer|exists:users,id',
            'pet_type_id' => 'required|integer|exists:pet_types,id',
            'name' => 'required|string',
            'gender' => 'required|string',
            'registration_number' => 'required|string',
            'dob' => 'required|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2000',
        ];
    }

    public function messages(): array
    {
        return [
            'user_id.exists' => 'This user does not exist!',
            'user_id.required' => 'A user is required for this pet!',
            'pet_type_id.required' => 'Pet type is required!',
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
