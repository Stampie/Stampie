# Changelog

The change log describes what been "Added", "Removed", "Changed" or "Fixed" between versions

## Unreleased

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
