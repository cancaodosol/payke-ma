<?php

namespace App\Http\Requests\PaykeDb;

use App\Models\PaykeDb;
use Illuminate\Foundation\Http\FormRequest;

class CreateRequestMany extends FormRequest
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
            'dbs_csv' => 'required'
        ];
    }

    public function to_payke_dbs()
    {
        $dbs = [];
        $dbs_csv = explode("\n", $this->input('dbs_csv'));
        foreach($dbs_csv as $db_csv){
            $db_array = explode(",", $db_csv);
            if(count($db_array) !== 5) return;

            $db = new PaykeDb();
            $db->payke_host_id = trim($db_array[0]);
            $db->db_host = trim($db_array[1]);
            $db->db_database = trim($db_array[2]);
            $db->db_username = trim($db_array[3]);
            $db->db_password = trim($db_array[4]);

            $dbs[] = $db;
        }

        return $dbs;
    }
}
