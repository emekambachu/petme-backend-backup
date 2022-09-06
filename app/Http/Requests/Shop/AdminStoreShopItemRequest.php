<?php

namespace App\Http\Requests\Shop;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class AdminStoreShopItemRequest extends FormRequest
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
            'name' => 'required|string|unique:shop_items,name',
            'description' => 'required|string',
            'quantity' => 'required|integer',
            'shop_metric_id' => 'nullable|integer',
            'shop_category_id' => 'nullable|integer',
            'cost' => 'required|numeric',
            'status' => 'nullable|string',
            'owner' => 'nullable|string',
            'images' => 'required|image|mimes:jpg,jpeg,png|max:2000',
            'images.*' => 'image|mimes:jpg,jpeg,png|max:2000',
        ];
    }

    public function messages()
    {
        return [
            'name.unique' => 'This name already exists!',
            'shop_metric_id.required' => 'Select metric!',
            'shop_category_id.required' => 'Select Category!',
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
