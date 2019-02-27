<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NewsRequest extends FormRequest
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
            'id' => 'required',
            'slug' => 'required|string|min:1',
            'author' => 'required|string|min:1',
            'content_flat' => 'nullable|string',
            'content_render' => 'nullable|string',
            'date' => 'required|date',
            'name' => 'required|string|min:1',
            'section' => 'nullable|string',
            'state' => 'required|string',
            'tags' => 'nullable|array|min:0',
            'type' => 'required|string|min:1',
        ];
    }
}
