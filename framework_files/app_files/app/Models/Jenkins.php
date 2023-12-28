<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jenkins extends Model
{
    use HasFactory;
    protected $table = "tb_user_jenkins";
    protected $fillable = [
        'username',
        'token',
        'jenkins_url',
        'created_at',
        'updated_at'
    ];
}