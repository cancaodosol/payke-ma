<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaykeUser extends Model
{
    use HasFactory;

    const STATUS__ACTIVE = 1;
    const STATUS__BEFORE_SETTING = -1;
    const STATUS__DISABLE_ADMIN = 2;
    const STATUS__DISABLE_ADMIN_AND_SALES = 3;
    const STATUS__DELETE = 4;
    const STATUS__HAS_ERROR = 9;

    const STATUS_NAMES = [
        1 => "正常稼働"
        ,-1 => "初回登録"
        ,2 => "管理停止"
        ,3 => "管理・販売停止"
        ,4 => "削除済"
        ,9 => "エラーあり"
    ];

    public function PaykeHost()
    {
        return $this->belongsTo('App\Models\PaykeHost');
    }

    public function PaykeDb()
    {
        return $this->belongsTo('App\Models\PaykeDb');
    }

    public function PaykeResource()
    {
        return $this->belongsTo('App\Models\PaykeResource');
    }

    public function is_active() { return $this->status == PaykeUser::STATUS__ACTIVE; }
    public function is_before_setting() { return $this->status == PaykeUser::STATUS__BEFORE_SETTING; }
    public function is_disable_admin() { return $this->status == PaykeUser::STATUS__DISABLE_ADMIN; }
    public function is_disable_admin_and_sales() { return $this->status == PaykeUser::STATUS__DISABLE_ADMIN_AND_SALES; }
    public function is_delete() { return $this->status == PaykeUser::STATUS__DELETE; }
    public function has_error() { return $this->status == PaykeUser::STATUS__HAS_ERROR; }

    protected $fillable = [
        'status'
        ,'payke_host_id'
        ,'payke_db_id'
        ,'payke_resource_id'
        ,'user_folder_id'
        ,'user_app_name'
        ,'app_url'
        ,'enable_affiliate'
        ,'user_name'
        ,'email_address'
        ,'memo'
    ];

    const validation_rules = [
        'payke_host_db_id' => 'required'
        ,'payke_resource_id' => 'required'
        ,'user_app_name' => 'required'
        ,'user_name' => 'required'
        ,'email_address' => 'required'
    ];

    public function status_name(): string
    {
        return $this->status ? $this::STATUS_NAMES[$this->status] : '';
    }

    public function host_db_id(): string
    {
        return "{$this->payke_host_id}_{$this->payke_db_id}";
    }

    public function set_user_folder_id(int $host_id, int $db_id): void
    {
        $this->user_folder_id = "user_{$host_id}_{$db_id}";
    }

    public function set_app_url(string $hostname, string $user_app_name): void
    {
        $this->app_url = "https://{$hostname}/{$user_app_name}/admin/users";
    }
}
