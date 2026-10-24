<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = ['key', 'value', 'type', 'description'];

    protected static array $runtimeCache = [];

    public static function get($key, $default = null)
    {
        if (array_key_exists($key, self::$runtimeCache)) {
            return self::$runtimeCache[$key];
        }

        $setting = self::where('key', $key)->first();
        $val = $setting ? $setting->value : $default;
        self::$runtimeCache[$key] = $val;

        return $val;
    }

    public static function set($key, $value, $type = 'string', $description = null)
    {
        self::$runtimeCache[$key] = $value;

        return self::updateOrCreate(
            ['key' => $key],
            ['value' => $value, 'type' => $type, 'description' => $description]
        );
    }

    public static function clearCache(): void
    {
        self::$runtimeCache = [];
    }
}
