<?php

namespace App\Traits;

trait HasUserTracking
{
    public static function bootHasUserTracking(): void
    {
        static::creating(function (self $model): void {
            $userId = self::resolveAuthUserId();
            $model->created_by ??= $userId;
            $model->updated_by ??= $userId;
        });

        static::updating(function (self $model): void {
            $model->updated_by = self::resolveAuthUserId();
        });

        static::deleting(function (self $model): void {
            if (in_array('Illuminate\Database\Eloquent\SoftDeletes', class_uses_recursive($model))) {
                $model->deleted_by = self::resolveAuthUserId();
                $model->saveQuietly();
            }
        });
    }

    private static function resolveAuthUserId(): ?int
    {
        return auth('api')->id() ?? auth()->id();
    }
}
