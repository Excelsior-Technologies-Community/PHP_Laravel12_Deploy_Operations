<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApprovalGate extends Model
{
    protected $fillable = ['operation_name', 'status', 'requested_by', 'approved_by', 'reason', 'approved_at'];

    protected $casts = ['approved_at' => 'datetime'];

    public static function isApproved(string $name): bool
    {
        return static::where('operation_name', $name)->where('status', 'approved')->exists();
    }

    public static function isPending(string $name): bool
    {
        return static::where('operation_name', $name)->where('status', 'pending')->exists();
    }
}
