<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Server extends Model
{
    use HasFactory;
    protected $table = "tb_server";
    protected $fillable = [
        'server_ip',
        'username',
        'password',
        'created_at',
        'updated_at'
    ];
}