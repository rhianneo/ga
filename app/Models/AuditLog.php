<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class AuditLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_name',
        'action',
        'module',
        'record_id',
        'description',
    ];

    // Add readable_description to the model's appended attributes
    protected $appends = ['readable_description'];

    /**
     * Return a human-friendly description for the audit record.
     */
    public function getReadableDescriptionAttribute()
    {
        $desc = $this->description;
        $decoded = @json_decode($desc, true);

        // If it's valid JSON
        if (json_last_error() === JSON_ERROR_NONE && $decoded !== null) {

            // ---- Case A: structured update with 'Old' and 'New' arrays ----
            if (isset($decoded['Old']) && isset($decoded['New']) && is_array($decoded['Old']) && is_array($decoded['New'])) {
                $skipKeys = ['updated_at', 'created_at']; // noisy fields to skip by default
                $changes = [];

                foreach ($decoded['Old'] as $key => $oldVal) {
                    if (in_array($key, $skipKeys)) continue; // skip automatic timestamps
                    $newVal = $decoded['New'][$key] ?? null;

                    // Only show true changes
                    if ($this->valuesAreDifferent($oldVal, $newVal)) {
                        $label = ucfirst(str_replace('_', ' ', $key));
                        $changes[] = "{$label}: \"{$this->formatValue($key, $oldVal)}\" → \"{$this->formatValue($key, $newVal)}\"";
                    }
                }

                if (!empty($changes)) {
                    return implode('; ', $changes);
                }

                // If no visible changes after filtering (e.g. only updated_at changed)
                return 'Updated ' . $this->module;
            }

            // ---- Case B: array of per-process updates (Actual Progress Entry style) ----
            // e.g. [ ['Process ID'=>31,'Action'=>'updated','Start'=>'2025-09-09', ...], ... ]
            if (is_array($decoded) && $this->isSequentialArray($decoded)) {
                $parts = [];
                foreach ($decoded as $item) {
                    if (!is_array($item)) {
                        $parts[] = is_string($item) ? $item : json_encode($item);
                        continue;
                    }

                    // Prefer numeric process id keys or 'Process ID'
                    $pid = $item['Process ID'] ?? $item['process_id'] ?? null;
                    $action = $item['Action'] ?? $item['action'] ?? 'updated';

                    if ($pid) {
                        // Try to resolve human-readable process name where available (best-effort)
                        $procName = null;
                        try {
                            $proc = \App\Models\Process::find($pid);
                            $procName = $proc?->sub_process;
                        } catch (\Throwable $e) {
                            $procName = null;
                        }
                        $label = $procName ? "{$procName} (#{ $pid })" : "Process #{$pid}";
                    } else {
                        $label = $item['label'] ?? $item['name'] ?? 'Process';
                    }

                    $start = $item['Start'] ?? $item['start_date'] ?? $item['start'] ?? null;
                    $end   = $item['End'] ?? $item['end_date'] ?? $item['end'] ?? null;
                    $dur   = $item['actual_duration'] ?? $item['duration'] ?? null;

                    $s = $this->formatValue('date', $start);
                    $e = $this->formatValue('date', $end);

                    $part = "{$label} {$action} (start: {$s}, end: {$e}";
                    if ($dur) $part .= ", duration: {$dur} day" . ((int)$dur === 1 ? '' : 's');
                    $part .= ')';

                    $parts[] = $part;
                }
                return implode('; ', $parts);
            }

            // ---- Case C: creation with 'name' field ----
            if (isset($decoded['name'])) {
                $name = $decoded['name'] ?? '-';
                return 'Created ' . $this->module . ': "' . $name . '"';
            }

            // ---- Case D: associative JSON (simple summary) ----
            if ($this->isAssociativeArray($decoded)) {
                $pieces = [];
                foreach ($decoded as $k => $v) {
                    if (is_array($v)) {
                        // try to pick a human label if present
                        $label = $v['sub_process'] ?? $v['name'] ?? json_encode($v);
                        $pieces[] = ucfirst(str_replace('_', ' ', $k)) . ': ' . $label;
                    } else {
                        $pieces[] = ucfirst(str_replace('_', ' ', $k)) . ': ' . $this->formatValue($k, $v);
                    }
                }
                return implode('; ', $pieces);
            }

            // Fallback: return compact JSON as single-line fallback
            return is_string($desc) ? $desc : json_encode($decoded);
        }

        // Not JSON -> return raw (string) description
        return $desc ?? "{$this->action}";
    }

    /**
     * Helper: detect sequential (numeric-keyed) arrays.
     */
    private function isSequentialArray(array $arr): bool
    {
        return array_keys($arr) === range(0, count($arr) - 1);
    }

    /**
     * Helper: detect associative array.
     */
    private function isAssociativeArray(array $arr): bool
    {
        return !$this->isSequentialArray($arr);
    }

    /**
     * Format values for display: dates -> Y-m-d H:i:s Asia/Manila, null -> '-', otherwise as string.
     *
     * @param string|null $key
     * @param mixed $value
     * @return string
     */
    private function formatValue(?string $key, $value): string
    {
        if (is_null($value) || $value === '') return '-';

        // If it's already a Carbon instance or ISO datetime, parse and format
        $dateKeys = ['expiry_date', 'follow_up_date', 'start_date', 'end_date', 'updated_at', 'created_at'];
        if ($key === 'date' || $key === 'time' || in_array(strtolower($key), $dateKeys) || $this->looksLikeDate($value)) {
            try {
                $dt = Carbon::parse($value)->setTimezone('Asia/Manila');
                return $dt->format('Y-m-d H:i:s');
            } catch (\Throwable $e) {
                // not a date after all
            }
        }

        // Default: return casted string
        return (string) $value;
    }

    /**
     * Quick heuristic to see if a value looks like an ISO date/time.
     */
    private function looksLikeDate($value): bool
    {
        if (!is_string($value)) return false;
        // ISO-ish pattern: 4-digit-year-...
        return preg_match('/^\d{4}-\d{2}-\d{2}/', $value) === 1;
    }

    /**
     * Compare two values for inequality in a loose but sensible manner.
     */
    private function valuesAreDifferent($a, $b): bool
    {
        // normalize null/empty string
        if (($a === null || $a === '') && ($b === null || $b === '')) return false;
        return (string)$a !== (string)$b;
    }
}
