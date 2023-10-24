<?php

namespace App\Http\Requests\PaykeUser;

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
        return [
            'user_name' => 'required',
            'email_address' => '',
            'payke_app_name' => ''
        ];
    }

    public function userName(): string
    {
        return $this->intput('user_name');
    }

    public function emailAddress(): string
    {
        return $this->intput('email_address');
    }

    public function paykeAppName(): string
    {
        return $this->intput('payke_app_name');
    }

    public function paykeHostId(): int
    {
        return (int)explode("_", $this->input("payke_host_db"))[0];
    }

    public function paykeDbId(): int
    {
        return (int)explode("_", $this->input("payke_host_db"))[1];
    }

    public function paykeResourceId(): int
    {
        return (int)$this->intput('payke_resource');
    }

    public function can_affi(): bool
    {
        return (bool)$this->intput('can_affi');
    }

    public function memo(): string
    {
        return $this->intput('memo');
    }
}
