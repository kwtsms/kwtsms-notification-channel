<?php

namespace NotificationChannels\KwtSms;

use Composer\InstalledVersions;
use Illuminate\Support\ServiceProvider;
use KwtSMS\KwtSMS;

class KwtSmsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/kwtsms.php', 'kwtsms');

        // 2026-11-05 14:57 +03: positional arguments so the 6th (app_name, User-Agent token) is ignored
        // by kwtSMS PHP library versions without it; a named app_name would throw there, #939.
        $this->app->singleton(KwtSMS::class, function () {
            return new KwtSMS(
                config('kwtsms.username', ''),            // username
                config('kwtsms.password', ''),            // password
                config('kwtsms.sender', 'KWT-SMS'),       // sender_id
                (bool) config('kwtsms.test_mode', false), // test_mode
                'kwtsms.log',                             // log_file (library default, unchanged)
                self::userAgentToken(),                   // app_name
            );
        });

        $this->app->singleton(KwtSmsChannel::class, function ($app) {
            return new KwtSmsChannel($app->make(KwtSMS::class));
        });
    }

    /**
     * "kwtsms-laravel-notification/<version>", the version from Composer's installed metadata ("v" dropped);
     * a checkout without a tag reports "0.0.0-dev".
     * 2026-11-05 14:57 +03: added so kwtSMS can count accounts per integration (name and version only,
     * no personal data), #939.
     */
    public static function userAgentToken(): string
    {
        $package = 'kwtsms/kwtsms-notification-channel';
        $version = '';
        if (class_exists(InstalledVersions::class) && InstalledVersions::isInstalled($package)) {
            $version = ltrim((string) InstalledVersions::getPrettyVersion($package), 'vV');
        }
        if (preg_match('/^[0-9][0-9A-Za-z.+-]*$/', $version) !== 1) {
            $version = '0.0.0-dev';
        }

        return 'kwtsms-laravel-notification/'.$version;
    }

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/kwtsms.php' => config_path('kwtsms.php'),
            ], 'config');
        }
    }
}
