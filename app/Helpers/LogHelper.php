<?php

namespace App\Helpers;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;

class LogHelper
{
    /**
     * Record a user action in the audit log.
     *
     * @param string      $module   The module where the action occurred (e.g., Application)
     * @param string      $action   The action performed (create, update, delete)
     * @param int|null    $recordId The ID of the record affected
     * @param array|string|null $data Changes or description of the action
     */
    public static function logAction(string $module, string $action, ?int $recordId = null, $data = null)
    {
        $description = '';

        if ($action === 'update' && is_array($data) && isset($data['Before'], $data['After'])) {
            $before = $data['Before'];
            $after  = $data['After'];
            $changes = [];

            foreach ($before as $field => $oldValue) {
                // Skip system fields
                if (in_array($field, ['updated_at', 'created_at', 'deleted_at', 'id', 'user_id'])) {
                    continue;
                }

                $newValue = $after[$field] ?? null;

                // Only log if value actually changed
                if ($oldValue != $newValue) {
                    $label = ucwords(str_replace('_', ' ', $field));
                    $oldValue = $oldValue ?? 'N/A';
                    $newValue = $newValue ?? 'N/A';
                    $changes[] = "{$label} changed from \"{$oldValue}\" to \"{$newValue}\"";
                }
            }

            $description = empty($changes) ? 'No significant changes' : implode('; ', $changes);
        }
        elseif ($action === 'create' && is_array($data)) {
            $summary = $data['Created application']['name'] ?? 'New record';
            $description = "Created application: {$summary}";
        }
        elseif ($action === 'delete' && is_array($data)) {
            $summary = $data['Deleted application']['name'] ?? 'Record deleted';
            $description = "Deleted application: {$summary}";
        }
        elseif (is_array($data)) {
            // Fallback for array data
            $description = json_encode($data, JSON_UNESCAPED_UNICODE);
        }
        elseif (is_string($data)) {
            $description = $data;
        }

        AuditLog::create([
            'user_name'   => Auth::user()?->name ?? 'System',
            'module'      => $module,
            'action'      => $action,
            'record_id'   => $recordId,
            'description' => $description,
        ]);
    }
}
