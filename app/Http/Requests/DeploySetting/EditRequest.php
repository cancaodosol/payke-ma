<?php

namespace App\Http\Requests\DeploySetting;

use App\Models\DeploySetting;
use Illuminate\Foundation\Http\FormRequest;

class EditRequest extends FormRequest
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
        $settings = [
            (new DeploySetting())->set_key_and_value("payke_resource_id", $this->payke_resource_id),
            (new DeploySetting())->set_key_and_value("payke_x_auth_token", $this->payke_x_auth_token)
        ];

        return $settings;
    }
}
