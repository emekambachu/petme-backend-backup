<?php

namespace App\Http\Requests\User\Pet\Vaccination;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UserUpdatePetVaccinationRequest extends FormRequest
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
            'drug' => 'required|string',
            'source' => 'required|string',
            'administer_rate' => 'required|string',
            'frequency' => 'required|string',
            'administration_duration' => 'required|string',
            'batch_number' => 'required|integer',
            'last_session' => 'nullable|string',
            'next_session' => 'nullable|string',
            'created_by' => 'required|string',
            'location' => 'nullable|string',
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
