<?php

namespace App\Http\Requests\Home\Blog;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class HomeBlogSubmitCommentRequest extends FormRequest
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
            'comment' => 'required|string',
            'blog_post_id' => 'required|integer',
        ];
    }

    public function messages()
    {
        return [
            'blog_post_id.required' => 'A post is required for this comment!',
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
