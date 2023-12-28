<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;
    protected $table = "tb_project";
    protected $fillable = [
        'id_project_template',
        'id_server',
        'id_docker',
        'id_user',
        'project_name',
        'project_repo',
        'created_at',
        'updated_at',
        'post_repo',
        'id_user_jenkins'
    ];

    public function user(){
        return $this->belongsTo(User::class, 'id_user', 'id');
    }

    public function server(){
        return $this->belongsTo(Server::class, 'id_server', 'id');
    }

    public function template(){
        return $this->belongsTo(Template::class, 'id_project_template', 'id');
    }

    public function docker(){
        return $this->belongsTo(Docker::class, 'id_docker', 'id');
    }

    public function jenkins(){
        return $this->belongsTo(Jenkins::class, 'id_user_jenkins', 'id');
    }
}