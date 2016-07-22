#	Rayne\SemanticVersioning

A tiny independent library for parsing and comparing semantic versions which is compatible with [Semantic Versioning 2.0](http://semver.org).

[![Latest Stable Version](https://poser.pugx.org/rayne/semantic-versioning/v/stable)](https://packagist.org/packages/rayne/semantic-versioning)
[![Latest Unstable Version](https://poser.pugx.org/rayne/semantic-versioning/v/unstable)](https://packagist.org/packages/rayne/semantic-versioning)
[![Build Status](https://travis-ci.org/Rayne/semantic-versioning.php.svg?branch=master)](https://travis-ci.org/Rayne/semantic-versioning.php)
[![Code Coverage](https://scrutinizer-ci.com/g/Rayne/semantic-versioning.php/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/Rayne/semantic-versioning.php/?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Rayne/semantic-versioning.php/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/Rayne/semantic-versioning.php/?branch=master)
[![Dependency Status](https://www.versioneye.com/user/projects/569508ceaf789b002e0002fe/badge.svg?style=flat)](https://www.versioneye.com/user/projects/569508ceaf789b002e0002fe)
[![License](https://poser.pugx.org/rayne/semantic-versioning/license)](https://packagist.org/packages/rayne/semantic-versioning)

#	Dependencies

##	Production

*	PHP 5.6 or better

##	Development

*	Composer
*	Git
*	PHPUnit

#	Setup

It's recommended to install and update the library with Composer.

##	Composer

[Download Composer](https://getcomposer.org/download) and install `rayne/semantic-versioning`.

	composer require rayne/semantic-versioning ~1.0

Set the `@dev` stability flag to install the latest development version.

	composer require rayne/semantic-versioning @dev

##	Download

Download and extract the library. Then include `src/autoload.php`.

	require 'src/autoload.php';

#	Tests

1.	Clone the repository

		git clone https://github.com/rayne/semantic-versioning.php.git

2.	Install the development dependencies

		composer install --dev

3.	Run the tests
 
		./vendor/bin/phpunit

#	Examples

The library contains the following classes:

*	`NoSemanticVersionException`: Thrown by `SemanticVersion` on invalid input

*	`SemanticComparator`: The semantic versioning comparator for comparing `SemanticVersion` objects

*	`SemanticVersion`: The semantic versioning parser which throws a `RuntimeException` on invalid versions

The examples are part of the test suite.
Have a look at the `tests` directory for more information.

##	Interpret semantic versions

```php
use Rayne\SemanticVersioning\SemanticVersion;

$version = new SemanticVersion('1.0.0-beta+exp.sha.5114f85');

assert('1.0.0-beta+exp.sha.5114f85' === (string) $version);
assert( 1                           === $version->getMajor());
assert(   0                         === $version->getMinor());
assert(     0                       === $version->getPatch());
assert(      'beta'                 === $version->getPre());
assert(           'exp.sha.5114f85' === $version->getMeta());
assert('1.0.0-beta+exp.sha.5114f85' === $version->getVersion());
```

##	Compare semantic versions

```php
use Rayne\SemanticVersioning\SemanticComparator;
use Rayne\SemanticVersioning\SemanticVersion;

$comparator     = new SemanticComparator();

$alpha          = new SemanticVersion('1.0.0-alpha');
$candidate      = new SemanticVersion('1.0.0-rc.1');
$candidate_meta = new SemanticVersion('1.0.0-rc.1+ci');
$release        = new SemanticVersion('1.0.0');

// $alpha < $candidate
assert($comparator($alpha, $candidate) < 0);
assert($comparator->compare($alpha, $candidate) < 0);

// $candidate == $candidate_meta
assert($comparator($candidate, $candidate_meta) == 0);
assert($comparator->compare($candidate, $candidate_meta) == 0);

// $release > $candidate
assert($comparator($release, $candidate) > 0);
assert($comparator->compare($release, $candidate) > 0);
```

##	Sort semantic versions

```php
use Rayne\SemanticVersioning\SemanticComparator;
use Rayne\SemanticVersioning\SemanticVersion;

$versions = [
	$candidate = new SemanticVersion('1.0.0-rc.1'),
	$release   = new SemanticVersion('1.0.0'),
	$alpha     = new SemanticVersion('1.0.0-alpha'),
];

// Sort by semantic precedence.
usort($versions, new SemanticComparator());

assert($versions[0] === $alpha);
assert($versions[1] === $candidate);
assert($versions[2] === $release);
```