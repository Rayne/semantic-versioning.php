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
class SemanticComparatorTest extends PHPUnit_Framework_TestCase
{
    /**
     * @param array[] $sets Replaces strings with `SemanticVersion` objects.
     */
    private function replaceStringsWithSemanticVersionObjects(&$sets)
    {
        array_walk($sets, function (&$sets) {
            array_walk($sets, function (&$version) {
                $version = new SemanticVersion($version);
            });
        });
    }

    /**
     * @return array[]
     */
    public function provideSmallerGreaterVersions()
    {
        $sets = [
            [
                '1.0.0-alpha',
                '1.0.0-Alpha.1',
                '1.0.0-alpha.beta',
                '1.0.0-beta',
                '1.0.0-BETA.2',
                '1.0.0-beta.11',
                '1.0.0-rc.1',
                '1.0.0',
            ],
            [
                '1.0.0',
                '2.0.0',
                '2.1.0',
                '2.1.1',
            ],
        ];

        $this->replaceStringsWithSemanticVersionObjects($sets);

        $result = [];

        foreach ($sets as $set) {
            for ($i = 0; $i < count($set); $i++) {
                for ($j = $i + 1; $j < count($set); $j++) {
                    $result[] = [$set[$i], $set[$j]];
                }
            }
        }

        return $result;
    }

    /**
     * @return array[]
     */
    public function provideEqualVersions()
    {
        $result = [
            ['0.6.0', '0.6.0'],
            ['1.0.0-alpha+001', '1.0.0-alpha+002'],
            ['1.0.0+20130313144700', '1.0.0+20151219222700'],
            ['1.0.0-beta+exp.sha.5114f85', '1.0.0-beta+exp.md5.e07910a'],
        ];

        $this->replaceStringsWithSemanticVersionObjects($result);

        return $result;
    }

    /**
     * @dataProvider provideEqualVersions
     * @param SemanticVersionInterface $left
     * @param SemanticVersionInterface $right
     */
    public function testEqualComparison(SemanticVersionInterface $left, SemanticVersionInterface $right)
    {
        $comparator = new SemanticComparator;

        $this->assertSame(0, $comparator->compare($left, $right), "$left == $right");
        $this->assertSame(0, $comparator->compare($right, $left), "$right == $left");

        $this->assertSame(0, $comparator($left, $right), "$left == $right");
        $this->assertSame(0, $comparator($right, $left), "$right == $left");
    }

    /**
     * @dataProvider provideSmallerGreaterVersions
     * @param SemanticVersionInterface $smaller
     * @param SemanticVersionInterface $greater
     */
    public function testNotEqualComparison(SemanticVersionInterface $smaller, SemanticVersionInterface $greater)
    {
        $comparator = new SemanticComparator;

        $this->assertTrue($comparator->compare($smaller, $greater) < 0, "$smaller < $greater");
        $this->assertTrue($comparator->compare($greater, $smaller) > 0, "$greater > $smaller");

        $this->assertTrue($comparator($smaller, $greater) < 0, "$smaller < $greater");
        $this->assertTrue($comparator($greater, $smaller) > 0, "$greater > $smaller");
    }
}
