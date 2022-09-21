<?php

namespace App\Http\Requests\Admin\ServiceProvider;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class AdminUpdateServiceProviderRequest extends FormRequest
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
            'name' => ['required', Rule::unique('service_providers')
                ->ignore($this->service_provider->id ?? 0)],
            'email' => 'required|string|email|unique:service_providers,email',
            'mobile' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
            'address' => 'required|string',
            'services' => 'required|string',
            'opening_hours' => 'required|string',
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
