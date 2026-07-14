<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditTrail extends Model
{
    protected $fillable = ['operation_name', 'action', 'performed_by', 'environment', 'result', 'notes'];

    public static function log(string $operation, string $action, string $result = 'success', string $notes = ''): void
    {
        static::create([
            'operation_name' => $operation,
            'action'         => $action,
            'performed_by'   => 'system',
            'environment'    => config('app.env', 'local'),
            'result'         => $result,
            'notes'          => $notes,
        ]);
    }
}
