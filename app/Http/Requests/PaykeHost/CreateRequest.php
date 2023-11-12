<?php

namespace App\Http\Requests\PaykeHost;

use App\Models\PaykeHost;
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
        return PaykeHost::validation_rules;
    }

    public function identityFile(): UploadedFile | string
    {
        return $this->input('identity_file') ? $this->input('identity_file') : $this->file('identity_file');
    }

    public function identityFile___edit(): UploadedFile | null
    {
        return $this->file('identity_file___edit');
    }

    public function to_payke_host(): PaykeHost
    {
        $host = new PaykeHost();

        $host->status = $this->status;
        $host->name = $this->name;
        $host->hostname = $this->hostname;
        $host->remote_user = $this->remote_user;
        $host->port = $this->port;
        // $host->identity_file は、別途ファイル保存が必要。
        $host->resource_dir = $this->resource_dir;
        $host->public_html_dir = $this->public_html_dir;

        return $host;
    }
}
