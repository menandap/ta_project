<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterJobs extends Model
{
    use HasFactory;
    protected $table = "tb_master_jobs";
    protected $fillable = [
        'jobs_name',
        'jobs_token',
        'desc',
        'created_at',
        'updated_at'
    ];

    public function jobs()
    {
        return $this->hasMany(Jobs::class, 'id_jobs', 'id');
    }
}