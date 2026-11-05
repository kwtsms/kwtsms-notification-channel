<?php

/*
 * FILE:      tests/UserAgentTest.php
 * CREATED:   2026-11-05 14:57 +03
 * TASK:      #939
 * AUTHOR:    M Alhassoun
 * WHAT:      Checks the kwtsms-laravel-notification/<version> token and that the bound client carries it.
 * WHY:       kwtSMS counts accounts per integration from the User-Agent.
 * USAGE:     vendor/bin/phpunit --filter UserAgentTest (offline: no API call is made).
 * DEPENDS:   Orchestra Testbench, Composer\InstalledVersions.
 */

namespace NotificationChannels\KwtSms\Tests;

use Composer\InstalledVersions;
use KwtSMS\KwtSMS;
use NotificationChannels\KwtSms\KwtSmsServiceProvider;
use Orchestra\Testbench\TestCase;

class UserAgentTest extends TestCase
{
    protected function getPackageProviders($app): array
    {
        return [KwtSmsServiceProvider::class];
    }

    public function test_token_uses_installed_package_version(): void
    {
        $version = ltrim((string) InstalledVersions::getPrettyVersion('kwtsms/kwtsms-notification-channel'), 'vV');
        $expected = preg_match('/^[0-9][0-9A-Za-z.+-]*$/', $version) === 1 ? $version : '0.0.0-dev';

        $this->assertSame('kwtsms-laravel-notification/'.$expected, KwtSmsServiceProvider::userAgentToken());
    }

    public function test_bound_client_sends_app_token(): void
    {
        $client = $this->app->make(KwtSMS::class);
        if (! method_exists($client, 'getUserAgent')) {
            $this->markTestSkipped('Installed kwtSMS PHP library has no app_name support yet');
        }

        $this->assertStringStartsWith(KwtSmsServiceProvider::userAgentToken().' kwtsms-php/', $client->getUserAgent());
    }
}
