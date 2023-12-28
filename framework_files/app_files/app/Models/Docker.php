<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Docker extends Model
{
    use HasFactory;
    protected $table = "tb_user_docker";
    protected $fillable = [
        'username',
        'password',
        'created_at',
        'updated_at'
    ];
}