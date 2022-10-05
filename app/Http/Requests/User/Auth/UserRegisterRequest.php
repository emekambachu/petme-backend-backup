<?php

namespace App\Http\Requests\User\Auth;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class UserRegisterRequest extends FormRequest
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
    public function rules(){
        return [
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users,email',
            'password' => 'required|string|confirmed',
        ];
    }

    public function messages()
    {
        return [
            'email.required' => 'Email is required!',
            'email.unique' => 'Email already exists!',
        ];
    }

    protected function failedValidation(Validator $validator){

        if($this->wantsJson()){
            $response = response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ]);
        }

        throw (new ValidationException($validator, $response))
            ->errorBag($this->errorBag)
            ->redirectTo($this->getRedirectUrl());
    }
}
