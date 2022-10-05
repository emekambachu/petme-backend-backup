<?php

namespace App\Http\Requests\Admin\Shop;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class AdminAddShopItemDiscountRequest extends FormRequest
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
            'shop_item_id' => 'required|integer',
            'percent' => 'required|integer',
        ];
    }

    public function messages(): array
    {
        return [
            'shop_item_id.required' => 'A product shop item is required!',
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
