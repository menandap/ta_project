<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Template extends Model
{
    use HasFactory;
    protected $table = "tb_template_project";
    protected $fillable = [
        'template_type',
        'template_repo',
        'created_at',
        'updated_at'
    ];
}