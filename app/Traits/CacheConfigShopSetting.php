<?php

namespace App\Traits;


use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;

trait CacheConfigShopSetting
{
    static $CACHE_TAG = 'setting::';
    static $CONFIG_TAG = 'setting.';

    /**
     * Return a key with an attached cache tag
     *
     * @param string $key
     * @param string $shopId
     *
     * @return string
     */
    private function attachCacheTag(string $key, string $shopId): string
    {
        return self::$CACHE_TAG."{$shopId}::{$key}";
    }

    /**
     * Return a key with an attached config tag
     *
     * @param string $key
     *
     * @return string
     */
    private function attachConfigTag(string $key): string
    {
        return self::$CONFIG_TAG.$key;
    }

    /**
     * Check a setting exists in cache
     *
     * @param string $key
     * @param string $shopId
     *
     * @return boolean
     */
    public function cacheHas(string $key, string $shopId): bool
    {
        return Cache::has($this->attachCacheTag($key, $shopId));
    }

    /**
     * Check a setting exists in cache
     *
     * @param string $key
     * @param mixed  $value
     * @param string $shopId
     *
     */
    public function cacheSet(string $key, $value, string $shopId)
    {
        $expiresAt = Carbon::now()->addMinutes(Config::get('setting.cache_time', 10));
        Cache::put($this->attachCacheTag($key, $shopId), $value, $expiresAt);
    }

    /**
     * remove a setting from cache
     *
     * @param string $key
     * @param string $shopId
     *
     */
    public function cacheForget(string $key, string $shopId)
    {
        Cache::forget($this->attachCacheTag($key, $shopId));
    }

    /**
     * Check a setting exists in config
     *
     * @param string $key
     *
     * @return boolean
     */
    public function configHas(string $key): bool
    {
        return (bool)Config::get($this->attachConfigTag($key));
    }

    /**
     * Return cache values
     *
     * @param string $key
     * @param string $shopId
     *
     * @return string array
     */
    protected function cacheGet(string $key, string $shopId): string
    {
        return Cache::get($this->attachCacheTag($key, $shopId));
    }

    /**
     * Return config values
     *
     * @param string $key
     *
     * @return string array
     */
    protected function configGet(string $key): string
    {
        return Config::get($this->attachConfigTag($key));
    }
}
