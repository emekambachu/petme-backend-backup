<?php

namespace App\Http\Requests\Admin\Blog;

use http\Env\Request;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class AdminUpdatePostRequest extends FormRequest
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
            'title' => 'required|string|unique:blog_posts,title,'.\Request::instance()->id,
            'author' => 'required|string',
            'description' => 'required|string',
            'blog_category_id' => 'nullable|integer',
            'status' => 'nullable|string',
            'image' => 'required|image|mimes:jpg,jpeg,png|max:5000',
        ];
    }

    public function messages(): array
    {
        return [
            'title.unique' => 'This title already exists!',
            'blog_category_id.required' => 'Select category!',
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
