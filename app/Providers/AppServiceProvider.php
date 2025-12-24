<?php

namespace App\Providers;

use App\Models\Setting;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Load Settings
        $this->loadSettings();
    }

    /**
     * Load settings from database and share with views
     */
    protected function loadSettings(): void
    {
        try {
            // Check if settings table exists
            if (Schema::hasTable('settings')) {
                $settings = Setting::getAllCached();

                // Share settings with all views as $globalSettings
                View::share('globalSettings', $settings);

                // Override config values from settings
                $this->overrideConfig($settings);
            }
        } catch (\Exception $e) {
            // Database might not be ready yet (during migration)
            logger()->warning('Settings could not be loaded: ' . $e->getMessage());
        }
    }

    /**
     * Override Laravel config with settings from database
     * 
     * @param array $settings
     * @return void
     */
    protected function overrideConfig(array $settings): void
    {
        // Override app config
        if (isset($settings['app_name'])) {
            Config::set('app.name', $settings['app_name']);
        }

        if (isset($settings['site_name'])) {
            Config::set('app.name', $settings['site_name']);
        }

        // Override mail config
        if (isset($settings['mail_from_address'])) {
            Config::set('mail.from.address', $settings['mail_from_address']);
        }

        if (isset($settings['mail_from_name'])) {
            Config::set('mail.from.name', $settings['mail_from_name']);
        }

        // Override timezone if exists
        if (isset($settings['app_timezone'])) {
            Config::set('app.timezone', $settings['app_timezone']);
        }

        // Add more config overrides as needed
        // Example:
        // if (isset($settings['mail_host'])) {
        //     Config::set('mail.mailers.smtp.host', $settings['mail_host']);
        // }
    }
}
