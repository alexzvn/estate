<?php

namespace App\Repository;

use App\Models\Setting as ModelsSetting;
use Illuminate\Support\Facades\Cache;

class Setting extends BaseRepository
{
    protected $setting = [];

    protected $pendingSave = [];
    protected $pendingSavePreload = [];

    protected static $cacheTags = ['models.setting'];

    public function __construct(ModelsSetting $setting) {
        $this->setModel($setting);
        $this->loadSetting();
    }

    public function config(string $key, $default = null)
    {
        return $this->setting[$key] ??
            $this->pendingSave[$key] ??
            $this->getConfig($key) ??
            $default;
    }

    public function setConfigs(array $keyValues, bool $preload = true)
    {
        foreach ($keyValues as $key => $value) {
            $this->setConfig($key, $value, $preload);
        }
    }

    public function setConfig(string $key, $value, bool $preload = true)
    {
        $this->pendingSave[$key] = $value;

        if ($preload) {
            $this->pendingSavePreload[$key] = $preload;
        }
    }

    public function saveConfig()
    {
        if (! $this->isDirty()) {
            return;
        }

        foreach ($this->pendingSave as $key => $value) {
            $model = $this->model()->where('key', $key)->firstOrNew();
            $model->fill(compact('key', 'value'));
            $model->preload = $this->pendingSavePreload[$key] ?? false;
            $model->save();
        }

        $this->pendingSave = [];
        $this->flushConfig();
    }

    public static function flushConfig()
    {
        return Cache::tags(self::$cacheTags)->flush();
    }

    /**
     * Get config from db/cache
     *
     * @param string $key
     * @return mixed
     */
    protected function getConfig(string $key)
    {
        return Cache::tags($this->cacheTags)
            ->rememberForever($key, function () use ($key) {
                return $this->model()->where('key', $key)->first()->value ?? null;
            });
    }

    protected function loadSetting()
    {
        Cache::tags($this->cacheTags)
            ->rememberForever('preload', function () {
                return $this->model()->where('preload', true)->get();
            })
            ->map(function (ModelsSetting $setting) {
                $this->setting[$setting->key] = $setting->value;
            });
    }

    public function __get(string $key)
    {
        return $this->config($key);
    }

    public function __set(string $key, $value)
    {
        $this->setConfig($key, $value);
    }

    private function isDirty()
    {
        return (boolean) $this->pendingSave;
    }

    public function __destruct()
    {
        if ($this->isDirty()) {
            $this->saveConfig();
        }
    }
}
