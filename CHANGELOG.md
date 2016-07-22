# CHANGELOG

All notable changes to this project will be documented in this file.
This project *surprisingly* adheres to [Semantic Versioning](http://semver.org).

## [1.0.0-rc.3] - 2016-07-22

### Added

* `CHANGELOG.md` (inspired by [Keep a CHANGELOG](http://keepachangelog.com))
* `SemanticComparatorInterface`
* `SemanticVersionInterface`

### Changed

* `SemanticComparator` implements `SemanticComparatorInterface`
* `SemanticVersion` implements `SemanticVersionInterface`
* Updated to `PHPUnit ~5.0`
* Switched to `PSR2`

### Removed

* Dropped official support for `PHP < 5.6`
* Renamed `NoSemanticVersionException` to `InvalidVersionException`

[1.0.0-rc.3]: https://github.com/Rayne/semantic-versioning.php/compare/1.0.0-rc.2...1.0.0-rc.3