<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PipelineStep extends Model
{
    protected $fillable = ['deployment_name', 'step_order', 'step_name', 'status', 'output', 'started_at', 'completed_at'];

    protected $casts = ['started_at' => 'datetime', 'completed_at' => 'datetime'];
}
