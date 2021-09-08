# Changelog

The change log describes what been "Added", "Removed", "Changed" or "Fixed" between versions

## Unreleased

### Added

- Added support for using any PSR-18 client
- Added support for providing PSR-17 factories in the mailer

### Removed

- Removed support for HTTPlug 1 (HTTPlug 2 is still supported as it extends PSR-18).

### Deprecated

- Deprecated `Stampie\Mailer::setMessageFactory` in favor of setting a PSR-17 RequestFactory

## 1.1.0

### Added

- Added support for PHP 8

### Removed

- Removed support for PHP <7.2

### Fixed

- Fixed the Postmark endpoint to use https
- Fixed the handling of taggable messages without any tags in the Sendgrid mailer

## 1.0.1

### Added

- Marked HTTPlug 2 as supported.

## 1.0.0-beta2

### Added

- Support for Mailjet
- Support for Sparkpost
- Support for Mandrill subaccounts

## 1.0.0-beta1

### Added

- All exception implements `Stampie\ExceptionInterface`.

### Changed

- The `Stampie\MailerInterface::send` method no longer return a boolean. Errors are now reported using exceptions that implements `Stampie\ExceptionInterface`. As core mailers were already throwing exceptions when sending a message, the BC break impact is limited.
- The `SendGrid` mailer is updated to version 3. Please update your token.
- We use PSR-4 instead of PSR-0 for auto loading.

### Removed

- The `setHttpClient`, `setServerToken` and `getServerToken` methods have been removed from `Stampie\MailerInterface`.
- `Response` and `ResponseInterface` has been removed in favor of PSR-7.
- `SendGrid::setServerToken()` was removed. Use constructor instead.

## 1.0.0-alpha2

- Allow more special characters in password.

## 1.0.0-alpha1

- Replaced our HTTP Adapters with HTTPlug.
