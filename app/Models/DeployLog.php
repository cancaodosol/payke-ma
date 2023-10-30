<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeployLog extends Model
{
    use HasFactory;

    const STATUS__OK = 0;
    const STATUS__WARM = 1;
    const STATUS__ERROR = 2;
}
