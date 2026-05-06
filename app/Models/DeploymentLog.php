<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeploymentLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'deployment_name',
        'environment',
        'status',
        'description',
    ];
}
