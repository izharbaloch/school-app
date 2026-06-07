<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $fillable = [
        'user_id', 'action', 'model_type', 'model_id',
        'description', 'old_values', 'new_values', 'ip_address', 'user_agent',
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
    ];

    public function user() { return $this->belongsTo(User::class); }

    public static function record(
        string $action,
        string $description,
        ?object $model = null,
        array $oldValues = [],
        array $newValues = [],
        ?int $userId = null,
    ): void {
        try {
            static::create([
                'user_id'     => $userId ?? auth()->id(),
                'action'      => $action,
                'model_type'  => $model ? get_class($model) : null,
                'model_id'    => $model ? $model->getKey() : null,
                'description' => $description,
                'old_values'  => $oldValues ?: null,
                'new_values'  => $newValues ?: null,
                'ip_address'  => request()->ip(),
                'user_agent'  => request()->userAgent(),
            ]);
        } catch (\Throwable) {
            // Never let logging failures break the application
        }
    }
}
