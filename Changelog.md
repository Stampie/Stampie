# Changelog

The change log describes what been "Added", "Removed", "Changed" or "Fixed" between versions

### Unreleased

- The `setHttpClient`, `setServerToken` and `getServerToken` methods have been removed from `Stampie\MailerInterface`.
- The `Stampie\MailerInterface::send` method no longer return a boolean. Errors are now reported using exceptions that implements `Stampie\ExceptionInterface`. As core mailers were already throwing exceptions when sending a message, the BC break impact is limited.

### 1.0.0-alpha2

- Allow more special characters in password

### 1.0.0-alpha1

- Replaced our HTTP Adapters with HTTPlug.