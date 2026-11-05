# Changelog

All notable changes to this project will be documented in this file.

## Unreleased

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
