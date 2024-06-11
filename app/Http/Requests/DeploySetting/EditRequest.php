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
        $no = $this->no;

        $settings = [
            (new DeploySetting())->set_no($no)->set_key_and_value("setting_title", $this->setting_title),
            (new DeploySetting())->set_no($no)->set_key_and_value("sort_no", $this->sort_no),
            (new DeploySetting())->set_no($no)->set_key_and_value("is_plan", (bool)$this->is_plan),
            (new DeploySetting())->set_no($no)->set_key_and_value("payke_order_url", $this->payke_order_url),
            (new DeploySetting())->set_no($no)->set_key_and_value("plan_explain", $this->plan_explain),
            (new DeploySetting())->set_no($no)->set_key_and_value("payke_x_auth_token", $this->payke_x_auth_token),
            (new DeploySetting())->set_no($no)->set_key_and_value("payke_enable_affiliate", (bool)$this->payke_enable_affiliate),
            (new DeploySetting())->set_no($no)->set_key_and_value("payke_host_id", $this->payke_host_id),
            (new DeploySetting())->set_no($no)->set_key_and_value("payke_resource_id", $this->payke_resource_id),
            (new DeploySetting())->set_no($no)->set_key_and_value("payke_tag_id", $this->payke_tag_id)
        ];

        return $settings;
    }
}
