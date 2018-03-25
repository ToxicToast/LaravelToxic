<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SystemLogger extends Model
{
    protected $table = 'system_loggers';
    protected $fillable = ['user_id', 'text', 'ip_address', 'log_level'];
    protected $guarded = ['id'];
}
