<?php

/**
 * (c) Dennis Meckel
 *
 * For the full copyright and license information,
 * please view the LICENSE file that was distributed with this source code.
 */

namespace Rayne\SemanticVersioning\Examples;

use PHPUnit_Framework_TestCase;
use Rayne\SemanticVersioning\SemanticComparator;
use Rayne\SemanticVersioning\SemanticVersion;

/**
 * The following examples are part of the `README.md` documentation.
 * The method bodies have to be portable, so we are using `assert()`.
 *
 * @see http://semver.org/
 */
class ExamplesTest extends PHPUnit_Framework_TestCase
{
    public function testAttributes()
    {
        $version = new SemanticVersion('1.0.0-beta+exp.sha.5114f85');

        assert('1.0.0-beta+exp.sha.5114f85' === (string) $version);
        assert(1 === $version->getMajor());
        assert(0 === $version->getMinor());
        assert(0 === $version->getPatch());
        assert('beta' === $version->getPre());
        assert('exp.sha.5114f85' === $version->getMeta());
        assert('1.0.0-beta+exp.sha.5114f85' === $version->getVersion());

        assert(true  === $version->isMajorRelease());
        assert(false === $version->isMinorRelease());
        assert(false === $version->isPatchRelease());
        assert(true  === $version->isPreRelease());
    }

    public function testCompare()
    {
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
    }

    public function testSort()
    {
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
    }
}
