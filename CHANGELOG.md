# Changelog

All notable changes to this project will be documented in this file.

## Unreleased

## 1.1.0 - 2026-11-06

### Added

- Requests to kwtSMS carry the integration name and version (no personal data) so kwtSMS can see which integrations are in use.
- Laravel 13 support: illuminate/support and illuminate/notifications ^13.0 now allowed (tested on Laravel 12 and 13).
- Depends on kwtsms/kwtsms-php (the old package name kwtsms/kwtsms is abandoned on Packagist and printed a warning on install).

### Changed

- Docs and examples use the placeholder 965XXXXXXXX instead of a sample mobile number.

### Docs

- Test mode: test messages are queued but not delivered. Their credits are held until you delete them from the Sending Queue in your kwtsms.com account.

## 1.0.0 - 2026-03-17

### Added

- KwtSmsChannel for sending SMS via kwtSMS gateway
- KwtSmsMessage fluent builder with content and sender methods
- KwtSmsServiceProvider with config publishing
- CouldNotSendNotification exception for API errors
- Full test suite
