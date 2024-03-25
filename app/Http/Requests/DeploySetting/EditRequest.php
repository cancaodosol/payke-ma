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
        $settings = [];

        $payke_resource_id = new DeploySetting();
        $payke_resource_id->key = "payke_resource_id";
        $payke_resource_id->value = $this->payke_resource_id;
        $settings[] = $payke_resource_id;

        return $settings;
    }
}
