<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OperationLock extends Model
{
    protected $fillable = ['operation_name', 'is_locked', 'locked_at'];

    protected $casts = ['locked_at' => 'datetime'];

    public static function isLocked(string $name): bool
    {
        return static::where('operation_name', $name)->where('is_locked', true)->exists();
    }

    public static function lock(string $name): void
    {
        static::updateOrCreate(
            ['operation_name' => $name],
            ['is_locked' => true, 'locked_at' => now()]
        );
    }

    public static function unlock(string $name): void
    {
        static::where('operation_name', $name)->update(['is_locked' => false, 'locked_at' => null]);
    }
}
