<?php

namespace App\Observers;

use App\Models\ActivityLog;
use Illuminate\Database\Eloquent\Model;

class ActivityObserver
{
    /**
     * Only log when an authenticated user is performing the action,
     * so seeders / migrations don't pollute the log.
     */
    private function shouldLog(): bool
    {
        return auth()->check();
    }

    public function created(Model $model): void
    {
        if (!$this->shouldLog()) return;

        ActivityLog::record(
            action: 'create',
            description: class_basename($model) . ' #' . $model->getKey() . ' created',
            model: $model,
            newValues: $this->safeAttributes($model)
        );
    }

    public function updated(Model $model): void
    {
        if (!$this->shouldLog()) return;

        $changed = $model->getChanges();
        unset($changed['updated_at']);

        if (empty($changed)) return;

        $old = array_intersect_key($model->getOriginal(), $changed);

        ActivityLog::record(
            action: 'update',
            description: class_basename($model) . ' #' . $model->getKey() . ' updated',
            model: $model,
            oldValues: $old,
            newValues: $changed
        );
    }

    public function deleted(Model $model): void
    {
        if (!$this->shouldLog()) return;

        ActivityLog::record(
            action: 'delete',
            description: class_basename($model) . ' #' . $model->getKey() . ' deleted',
            model: $model,
            oldValues: $this->safeAttributes($model)
        );
    }

    private function safeAttributes(Model $model): array
    {
        // Strip sensitive fields before storing in log
        $attrs = $model->getAttributes();
        unset($attrs['password'], $attrs['remember_token'], $attrs['two_factor_secret']);
        return $attrs;
    }
}
