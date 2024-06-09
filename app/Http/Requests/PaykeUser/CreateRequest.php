<?php

namespace App\Http\Requests\PaykeUser;

use App\Models\PaykeUser;
use Illuminate\Foundation\Http\FormRequest;

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
        return PaykeUser::validation_rules;
    }

    public function paykeHostId(): int
    {
        return (int)explode("_", $this->input("payke_host_db_id"))[0];
    }

    public function paykeDbId(): int
    {
        return (int)explode("_", $this->input("payke_host_db_id"))[1];
    }

    public function paykeResourceId(): int
    {
        return (int)$this->input('payke_resource_id');
    }

    public function to_payke_user(): PaykeUser
    {
        $user = new PaykeUser();

        if($this->input("id")) $user->id = $this->input("id");
        if($this->input("status") !== null) $user->status = $this->input("status");
        $user->tag_id = $this->input("tag_id");
        $user->payke_host_id = $this->paykeHostId();
        $user->payke_db_id = $this->paykeDbId();
        $user->payke_resource_id = $this->input('payke_resource_id');
        $user->deploy_setting_no = $this->input('deploy_setting_no');
        $user->user_app_name = $this->input('user_app_name');
        $user->set_user_folder_id($this->paykeHostId(), $this->paykeDbId());
        $user->set_app_url($user->PaykeHost->hostname, $user->user_app_name);
        $user->enable_affiliate = (bool)$this->input('enable_affiliate');
        $user->user_name = $this->input('user_name');
        $user->email_address = $this->input('email_address');
        $user->memo = $this->input('memo') ?? "";
        $user->payke_order_id = $this->input('payke_order_id');

        return $user;
    }
}
