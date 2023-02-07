# Release Notes

## [Unreleased](https://github.com/laravel/vonage-notification-channel/compare/v3.2.0...3.x)

## [v3.2.0](https://github.com/laravel/vonage-notification-channel/compare/v3.1.2...v3.2.0) - 2023-02-06

### Changed

- Switch to `sms()` Client, improved GSM-7 Handling by @SecondeJK in https://github.com/laravel/vonage-notification-channel/pull/72

## [v3.1.2](https://github.com/laravel/vonage-notification-channel/compare/v3.1.1...v3.1.2) - 2023-01-26

### Fixed

- Revert "Fix the type of the message to save a lot of money 💸" by @driesvints in https://github.com/laravel/vonage-notification-channel/pull/70
- Revert "Swap Vonage Client to SMS instead of legacy message" by @driesvints in https://github.com/laravel/vonage-notification-channel/pull/71

## [v3.1.1](https://github.com/laravel/vonage-notification-channel/compare/v3.1.0...v3.1.1) - 2023-01-24

### Fixed

- Fix the type of the message to save a lot of money 💸 by @potsky in https://github.com/laravel/vonage-notification-channel/pull/69

## [v3.1.0](https://github.com/laravel/vonage-notification-channel/compare/v3.0.0...v3.1.0) - 2023-01-13

### Added

- Laravel v10 Support by @driesvints in https://github.com/laravel/vonage-notification-channel/pull/68

### Changed

- Bind VonageSmsChannel to container by @ankurk91 in https://github.com/laravel/vonage-notification-channel/pull/63
- Swap Vonage Client to SMS instead of legacy message by @SecondeJK in https://github.com/laravel/vonage-notification-channel/pull/65

## [v3.0.0 (2022-02-08)](https://github.com/laravel/vonage-notification-channel/compare/v2.5.1...v3.0.0)

### Changed

- Dropped support for Laravel 5.8 ([78bc3f9](https://github.com/laravel/vonage-notification-channel/commit/78bc3f92091f7cd38cdb27de1df845d12f263f24))
- Dropped support for PHP 7.1 ([858f0cb](https://github.com/laravel/vonage-notification-channel/commit/858f0cb55c5a3bea671c10f7737926c8c8ffee2c))
- Drop even more Laravel and PHP versions ([#51](https://github.com/laravel/nexmo-notification-channel/pull/51))
- Rewrote package to Vonage ([#52](https://github.com/laravel/nexmo-notification-channel/pull/52), [#53](https://github.com/laravel/nexmo-notification-channel/pull/53))

## [v2.5.1 (2020-12-22)](https://github.com/laravel/vonage-notification-channel/compare/v2.5.0...v2.5.1)

### Fixed

- Fix callback not being applied ([#44](https://github.com/laravel/vonage-notification-channel/pull/44))
- Fix `client-ref` key ([#45](https://github.com/laravel/vonage-notification-channel/pull/45))

## [v2.5.0 (2020-12-03)](https://github.com/laravel/vonage-notification-channel/compare/v2.4.0...v2.5.0)

### Added

- PHP 8 Support ([#39](https://github.com/laravel/vonage-notification-channel/pull/39))
- Add functionality to process callbacks ([#41](https://github.com/laravel/vonage-notification-channel/pull/41), [f124a4d](https://github.com/laravel/vonage-notification-channel/commit/f124a4db6a7824251aa065d83389995745805bc0))

## [v2.4.0 (2020-09-08)](https://github.com/laravel/vonage-notification-channel/compare/v2.3.1...v2.4.0)

### Added

- Laravel 8 support ([#38](https://github.com/laravel/vonage-notification-channel/pull/38))

## [v2.3.1 (2020-08-11)](https://github.com/laravel/vonage-notification-channel/compare/v2.3.0...v2.3.1)

### Fixed

- Fix shortcode messaging with the latest version of nexmo-client ([#37](https://github.com/laravel/vonage-notification-channel/pull/37))

## [v2.3.0 (2019-12-10)](https://github.com/laravel/vonage-notification-channel/compare/v2.2.1...v2.3.0)

### Added

- Allow to override Nexmo client via NexmoMessage ([#30](https://github.com/laravel/vonage-notification-channel/pull/30), [174323b](https://github.com/laravel/vonage-notification-channel/commit/174323b32e0c2e8881e8dc96702be782e3e49637))

## [v2.2.1 (2019-10-29)](https://github.com/laravel/vonage-notification-channel/compare/v2.2.0...v2.2.1)

### Fixed

- Fix shortcode implementation and test coverage ([#26](https://github.com/laravel/vonage-notification-channel/pull/26))

## [v2.2.0 (2019-10-01)](https://github.com/laravel/vonage-notification-channel/compare/v2.1.0...v2.2.0)

### Added

- Implement shortcode support ([#24](https://github.com/laravel/vonage-notification-channel/pull/24))

### Changed

- Upgrade to nexmo/laravel 2.0 ([#22](https://github.com/laravel/vonage-notification-channel/pull/22))

## [v2.1.0 (2019-07-30)](https://github.com/laravel/vonage-notification-channel/compare/v2.0.0...v2.1.0)

### Added

- Support for Client Ref on the Nexmo API ([#17](https://github.com/laravel/vonage-notification-channel/pull/17), [59436e9](https://github.com/laravel/vonage-notification-channel/commit/59436e9260a91669a4cde12aeb2ea7026e76181c))

### Changed

- Updated version constraints for Laravel 6 ([eeb50d9](https://github.com/laravel/vonage-notification-channel/commit/eeb50d991aa0442578c1c6f3c66920d32853692c))

## [v2.0.0 (2019-02-26)](https://github.com/laravel/vonage-notification-channel/compare/v1.0.1...v2.0.0)

### Added

- Added support for Laravel 5.8 ([9b9d340](https://github.com/laravel/vonage-notification-channel/commit/9b9d34093654501faaf975565ab290527fbdd925))

### Changed

- Use `Facade::resolved()` before extending channel ([#4](https://github.com/laravel/vonage-notification-channel/pull/4))

### Removed

- Dropped support for Laravel 5.7 ([9b9d340](https://github.com/laravel/vonage-notification-channel/commit/9b9d34093654501faaf975565ab290527fbdd925))
