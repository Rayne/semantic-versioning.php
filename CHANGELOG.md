# CHANGELOG

All notable changes to this project will be documented in this file.
This project *surprisingly* adheres to [Semantic Versioning](http://semver.org).

## [Unreleased]

No notable changes.

## [1.1.0] - 2017-02-13

### Added

* `SemanticVersionInterface->isMajorRelease()`
* `SemanticVersionInterface->isMinorRelease()`
* `SemanticVersionInterface->isPatchRelease()`
* `SemanticVersionInterface->isPreRelease()`

## [1.0.0] - 2016-07-24

### Removed

* `autoload.php` in favor of using a [`PSR-4`](http://php-fig.org/psr/psr-4) compatible class loader
* Section about manual setup in `README.md`

## [1.0.0-rc.3] - 2016-07-22

### Added

* `CHANGELOG.md` (inspired by [Keep a CHANGELOG](http://keepachangelog.com))
* `SemanticComparatorInterface`
* `SemanticVersionInterface`

### Changed

* `SemanticComparator` implements `SemanticComparatorInterface`
* `SemanticVersion` implements `SemanticVersionInterface`
* Updated to `PHPUnit ~5.0`
* Switched to [`PSR-2`](http://php-fig.org/psr/psr-2)

### Removed

* Dropped official support for `PHP < 5.6`
* Renamed `NoSemanticVersionException` to `InvalidVersionException`

[Unreleased]: https://github.com/Rayne/semantic-versioning.php/compare/1.0.0...HEAD
[1.1.0]: https://github.com/Rayne/semantic-versioning.php/compare/1.0.0...1.1.0
[1.0.0]: https://github.com/Rayne/semantic-versioning.php/compare/1.0.0-rc.3...1.0.0
[1.0.0-rc.3]: https://github.com/Rayne/semantic-versioning.php/compare/1.0.0-rc.2...1.0.0-rc.3