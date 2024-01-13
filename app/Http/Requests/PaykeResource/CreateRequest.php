<?php

namespace App\Http\Requests\PaykeResource;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\UploadedFile;

class CreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'payke-zip' => 'required'
        ];
    }

    public function paykeZip(): UploadedFile
    {
        return $this->file('payke-zip');
    }

    public function memo()
    {
        return $this->input('memo') ?? '' ;
    }
}
