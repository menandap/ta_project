<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jobs extends Model
{
    use HasFactory;
    protected $table = "tb_jobs_build";
    protected $fillable = [
        'id_jobs',
        'id_project',
        'id_jenkins',
        'id_user',
        'build_number',
        'build_time',
        'status',
        'created_at',
        'updated_at'
    ];

    public function user(){
        return $this->belongsTo(User::class, 'id_user', 'id');
    }

    public function jenkins(){
        return $this->belongsTo(Jenkins::class, 'id_jenkins', 'id');
    }

    public function project(){
        return $this->belongsTo(Project::class, 'id_project', 'id');
    }

    public function masterjobs(){
        return $this->belongsTo(MasterJobs::class, 'id_jobs', 'id');
    }

}