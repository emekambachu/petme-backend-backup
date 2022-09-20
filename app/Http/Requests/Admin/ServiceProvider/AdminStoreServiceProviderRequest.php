<?php

namespace App\Http\Requests\Admin\ServiceProvider;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class AdminStoreServiceProviderRequest extends FormRequest
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
    public function rules(): array
    {
        return [
            'name' => 'required|string|unique:service_providers,name',
            'email' => 'required|string|email|unique:service_providers,email',
            'mobile' => 'nullable|numeric',
            'address' => 'nullable|string',
            'services' => 'required|string',
            'opening_hours' => 'nullable|string',
            'photo' => 'required|image|mimes:jpg,jpeg,png|max:5000',
        ];
    }

    public function messages(): array
    {
        return [
            'name.unique' => 'This title already exists!',
            'email.unique' => 'This email already exists!',
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
