<?php

namespace App\Http\Requests\DeploySetting;

use App\Models\DeploySetting;
use Illuminate\Foundation\Http\FormRequest;

class EditBaseRequest extends FormRequest
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
        return [ ];
    }

    public function to_setting_models(): array
    {
        $no = 0;

        $settings = [
            (new DeploySetting())->set_no($no)->set_key_and_value("payke_api_url", $this->payke_api_url),
            (new DeploySetting())->set_no($no)->set_key_and_value("payke_api_token", $this->payke_api_token)
        ];

        return $settings;
    }
}
