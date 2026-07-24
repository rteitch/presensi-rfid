<?php

namespace App\Traits;

use App\Models\ActivityLog;

trait LogsActivity
{
    protected static function bootLogsActivity(): void
    {
        static::created(function ($model) {
            static::recordActivity($model, 'created', 'Menambahkan data baru', null, $model->getAttributes());
        });

        static::updated(function ($model) {
            $changes = [
                'old' => array_intersect_key($model->getOriginal(), $model->getChanges()),
                'new' => $model->getChanges(),
            ];
            // Remove updated_at from recorded changes to avoid noise
            unset($changes['old']['updated_at'], $changes['new']['updated_at']);

            if (!empty($changes['new'])) {
                static::recordActivity($model, 'updated', 'Mengubah data', $changes);
            }
        });

        static::deleted(function ($model) {
            static::recordActivity($model, 'deleted', 'Menghapus data', $model->getAttributes(), null);
        });
    }

    protected static function recordActivity($model, string $action, string $description, ?array $oldData = null, ?array $newData = null): void
    {
        $user = auth()->user();

        $modelName = class_basename($model);
        $nameField = $model->nama ?? $model->nama_kelas ?? $model->nama_device ?? $model->name ?? "ID #{$model->id}";

        ActivityLog::create([
            'user_id' => $user?->id,
            'action' => $action,
            'model_type' => $modelName,
            'model_id' => $model->id,
            'description' => "{$description} {$modelName}: {$nameField}",
            'changes' => is_array($oldData) && isset($oldData['old']) ? $oldData : ['old' => $oldData, 'new' => $newData],
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }
}
