<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MyFormRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'idade' => 'required|integer|digits:3',
            'endereco' => 'required|string|max:255',
            'contato' => 'required|numeric|digits:11',
        ];
    }

    public function prepareForValidation(){

        $this->merge([

            'email' => strtolower($this->email),
        ]);
    }
}
