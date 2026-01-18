<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SystemSetting extends Model
{
    protected $fillable = [
        'key',
        'value',
        'type',
        'description',
    ];

    /**
     * Get a setting value by key
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        $setting = static::where('key', $key)->first();
        
        if (!$setting) {
            return $default;
        }

        return static::castValue($setting->value, $setting->type);
    }

    /**
     * Set a setting value by key
     */
    public static function set(string $key, mixed $value): bool
    {
        $setting = static::firstOrCreate(['key' => $key]);
        
        // Determine type if not set
        if (!$setting->type) {
            $setting->type = static::determineType($value);
        }

        $setting->value = static::prepareValue($value, $setting->type);
        
        return $setting->save();
    }

    /**
     * Cast value based on type
     */
    protected static function castValue(string $value, string $type): mixed
    {
        return match($type) {
            'boolean' => filter_var($value, FILTER_VALIDATE_BOOLEAN),
            'integer' => (int) $value,
            'json' => json_decode($value, true),
            default => $value,
        };
    }

    /**
     * Prepare value for storage
     */
    protected static function prepareValue(mixed $value, string $type): string
    {
        if ($type === 'json') {
            return json_encode($value);
        }
        
        if ($type === 'boolean') {
            return $value ? 'true' : 'false';
        }

        return (string) $value;
    }

    /**
     * Determine type from value
     */
    protected static function determineType(mixed $value): string
    {
        return match(true) {
            is_bool($value) => 'boolean',
            is_int($value) => 'integer',
            is_array($value) => 'json',
            default => 'string',
        };
    }

    /**
     * Check if maintenance mode is enabled
     */
    public static function isMaintenanceMode(): bool
    {
        return static::get('maintenance_mode', false);
    }

    /**
     * Get maintenance message
     */
    public static function getMaintenanceMessage(): string
    {
        return static::get('maintenance_message', 'We are currently performing scheduled maintenance. The system will be back online shortly.');
    }

    /**
     * Check if super admin can access during maintenance
     */
    public static function allowSuperAdminDuringMaintenance(): bool
    {
        return static::get('maintenance_allow_super_admin', true);
    }
}
