<?php

/**
 * (c) Dennis Meckel
 *
 * For the full copyright and license information,
 * please view the LICENSE file that was distributed with this source code.
 */

namespace Rayne\SemanticVersioning;

use PHPUnit_Framework_TestCase;

/**
 * @see http://semver.org/
 */
class SemanticVersionTest extends PHPUnit_Framework_TestCase
{
    /**
     * @return array[]
     */
    public function provideValidVersions()
    {
        return [
            ['1.2.3',     1, 2, 3, '',  '',  [],    []],
            ['1.2.3-4',   1, 2, 3, '4', '',  ['4'], []],
            ['1.2.3+5',   1, 2, 3, '',  '5', [],    ['5']],
            ['1.2.3-4+5', 1, 2, 3, '4', '5', ['4'], ['5']],

            // Pre-release
            ['1.2.3-pre',     1, 2, 3, 'pre',     '', ['pre'],        []],
            ['1.2.3-pre.pre', 1, 2, 3, 'pre.pre', '', ['pre', 'pre'], []],
            ['1.2.3-pre-pre', 1, 2, 3, 'pre-pre', '', ['pre-pre'],    []],

            // Leading zeroes aren't forbidden for non-numeric pre-releases.
            // This fact isn't quite clear in Semantic Versioning 2.0.0,
            // but the Backus–Naur Form Grammar of Semantic Versioning 2.1.0 (Draft)
            // allows leading zeroes for non-numeric pre-releases.
            ['0.0.0-0FF',   0, 0, 0, '0FF', '',  ['0FF'], []],
            ['0.0.0-0FF+0', 0, 0, 0, '0FF', '0', ['0FF'], ['0']],

            // Metadata
            ['1.2.3+meta',      1, 2, 3, '', 'meta',      [], ['meta']],
            ['1.2.3+meta.meta', 1, 2, 3, '', 'meta.meta', [], ['meta', 'meta']],
            ['1.2.3+meta-meta', 1, 2, 3, '', 'meta-meta', [], ['meta-meta']],

            // Leading zeroes aren't forbidden for metadata
            ['0.0.0+00',   0, 0, 0, '',  '00', [],    ['00']],
            ['0.0.0-0+00', 0, 0, 0, '0', '00', ['0'], ['00']],

            // Pre-release and metadata
            ['1.2.3-pre+meta',          1, 2, 3, 'pre',     'meta',      ['pre'],        ['meta']],
            ['1.2.3-pre.pre+meta.meta', 1, 2, 3, 'pre.pre', 'meta.meta', ['pre', 'pre'], ['meta', 'meta']],
            ['1.2.3-pre-pre+meta-meta', 1, 2, 3, 'pre-pre', 'meta-meta', ['pre-pre'],    ['meta-meta']],

            ['1.0.0-alpha',    1, 0, 0, 'alpha',    '', ['alpha'],             []],
            ['1.0.0-alpha.1',  1, 0, 0, 'alpha.1',  '', ['alpha', '1'],        []],
            ['1.0.0-0.3.7',    1, 0, 0, '0.3.7',    '', ['0', '3', '7'],       []],
            ['1.0.0-x.7.z.92', 1, 0, 0, 'x.7.z.92', '', ['x', '7', 'z', '92'], []],

            ['1.0.0-alpha+001',            1, 0, 0, 'alpha', '001',             ['alpha'], ['001']],
            ['1.0.0+20130313144700',       1, 0, 0, '',      '20130313144700',  [],        ['20130313144700']],
            ['1.0.0-beta+exp.sha.5114f85', 1, 0, 0, 'beta',  'exp.sha.5114f85', ['beta'],  ['exp', 'sha', '5114f85']],
        ];
    }

    /**
     * @return array[]
     */
    public function provideInvalidVersions()
    {
        return [
            [null],
            [''],
            ['0'],
            ['T.X.T'],

            // Negative integers
            ['-0.0.0'],
            ['0.-0.0'],
            ['0.0.-0'],

            // Leading zeroes
            ['01.0.0'],
            ['0.01.1'],
            ['0.0.01'],

            // Empty pre-release
            ['0.6.0-'],
            ['0.6.0-.'],
            ['0.6.0-a.'],
            ['0.6.0-.b'],
            ['0.6.0-a..b'],

            // Pre-release numbers with leading zeroes
            ['0.0.0-00'],
            ['0.0.0-00+0'],

            // Empty metadata
            ['0.6.0+'],
            ['0.6.0+.'],
            ['0.6.0+a.'],
            ['0.6.0+.b'],
            ['0.6.0+a..b'],

            // Invalid pre-release and metadata
            ['0.6.0+meta+meta'],
            ['0.6.0-pre+meta+meta'],

            // Invalid characters
            ['0.0.0-❤'],
            ['0.0.0+❤'],
            ['0.0.0-Präzisionsmessgerät'],
        ];
    }

    /**
     * @dataProvider provideValidVersions
     * @param string $version
     * @param int $major
     * @param int $minor
     * @param int $patch
     * @param string $pre
     * @param string $meta
     * @param string[] $preStack
     * @param string[] $metaStack
     */
    public function testValidVersion($version, $major, $minor, $patch, $pre, $meta, array $preStack, array $metaStack)
    {
        $object = new SemanticVersion($version);

        $this->assertSame($version,   (string) $object);
        $this->assertSame($version,   $object->getVersion());
        $this->assertSame($major,     $object->getMajor());
        $this->assertSame($minor,     $object->getMinor());
        $this->assertSame($patch,     $object->getPatch());
        $this->assertSame($pre,       $object->getPre());
        $this->assertSame($preStack,  $object->getPreStack());
        $this->assertSame($meta,      $object->getMeta());
        $this->assertSame($metaStack, $object->getMetaStack());
    }

    /**
     * @dataProvider provideInvalidVersions
     * @expectedException \Rayne\SemanticVersioning\InvalidVersionException
     * @param mixed $version
     */
    public function testInvalidVersion($version)
    {
        new SemanticVersion($version);
    }

    public function provideReleaseTypes()
    {
        return [
            // Major, Minor, Patch
            ['0.0.0', true, false, false, false],
            ['0.1.0', false, true, false, false],
            ['0.1.1', false, false, true, false],

            ['1.0.0', true, false, false, false],
            ['1.1.0', false, true, false, false],
            ['1.1.1', false, false, true, false],

            // Major, Minor, Patch + Pre-Release
            ['0.0.0-pre', true, false, false, true],
            ['0.1.0-pre', false, true, false, true],
            ['0.1.1-pre', false, false, true, true],

            ['1.0.0-pre', true, false, false, true],
            ['1.1.0-pre', false, true, false, true],
            ['1.1.1-pre', false, false, true, true],

            // Major, Minor, Patch + Meta-Release
            ['0.0.0+meta', true, false, false, false],
            ['0.1.0+meta', false, true, false, false],
            ['0.1.1+meta', false, false, true, false],

            ['1.0.0+meta', true, false, false, false],
            ['1.1.0+meta', false, true, false, false],
            ['1.1.1+meta', false, false, true, false],
        ];
    }

    /**
     * @dataProvider provideReleaseTypes
     * @param string $version
     * @param bool $isMajorRelease
     * @param bool $isMinorRelease
     * @param bool $isPatchRelease
     * @param bool $isPreRelease
     */
    public function testReleaseTypes($version, $isMajorRelease, $isMinorRelease, $isPatchRelease, $isPreRelease)
    {
        $object = new SemanticVersion($version);

        $this->assertSame($isMajorRelease, $object->isMajorRelease());
        $this->assertSame($isMinorRelease, $object->isMinorRelease());
        $this->assertSame($isPatchRelease, $object->isPatchRelease());
        $this->assertSame($isPreRelease,   $object->isPreRelease());
    }
}
