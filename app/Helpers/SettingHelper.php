<?php

if (!function_exists('setting')) {
    /**
     * Get setting value by key
     */
    function setting(string $key, $default = null)
    {
        return \App\Models\Setting::get($key, $default);
    }
}

if (!function_exists('site_name')) {
    function site_name()
    {
        return setting('site_name', 'Ramadhan 1447 H');
    }
}

if (!function_exists('site_logo')) {
    function site_logo()
    {
        return setting('site_logo', asset('images/logo.png'));
    }
}