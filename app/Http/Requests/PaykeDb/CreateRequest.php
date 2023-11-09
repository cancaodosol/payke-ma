<?php

namespace App\Http\Requests\PaykeDb;

use App\Models\PaykeDb;
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
            'payke_host_id' => 'required'
            ,'db_host' => 'required'
            ,'db_username' => 'required'
            ,'db_password' => 'required'
            ,'db_database' => 'required'
        ];
    }

    public function to_payke_db(): PaykeDb
    {
        $db = new PaykeDb();

        $db->payke_host_id = $this->payke_host_id;
        $db->db_host = $this->db_host;
        $db->db_username = $this->db_username;
        $db->db_password = $this->db_password;
        $db->db_database = $this->db_database;

        return $db;
    }
}
