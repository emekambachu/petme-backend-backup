<?php

namespace App\Http\Requests\User\Appointment;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UserStoreAppointmentRequest extends FormRequest
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
    public function rules()
    {
        return [
            'pet_id' => 'required|integer|exists:pets,id',
            'service_provider_id' => 'required|integer|exists:service_providers,id',
            'service_provider_category_id' => 'required|integer|exists:service_provider_categories,id',
            'appointment_type_id' => 'nullable|integer|exists:appointment_types,id',
            'note' => 'required',
            'services' => 'required',
            'appointment_time' => 'required|string',

        ];
    }

    public function messages(): array
    {
        return [
            'pet_id.required' => 'Pet is required!',
            'pet_id.exists' => 'This pet does not exist!',
            'service_provider_id.exists' => 'This service provider does not exist!',
            'service_provider_id.required' => 'Service provider is required!',
            'appointment_type_id.exists' => 'Appointment type does not exists!',
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
