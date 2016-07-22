<?php

/**
 * (c) Dennis Meckel
 *
 * For the full copyright and license information,
 * please view the LICENSE file that was distributed with this source code.
 */

namespace Rayne\SemanticVersioning;

/**
 * Semantic Versioning Comparator compatible with Semantic Versioning `2.0`.
 *
 * @since 1.0.0-rc.1
 * @see http://semver.org
 */
class SemanticComparator implements SemanticComparatorInterface
{
    /**
     * @inheritdoc
     */
    public function __invoke(SemanticVersionInterface $left, SemanticVersionInterface $right)
    {
        return $this->compare($left, $right);
    }

    /**
     * @inheritdoc
     */
    public function compare(SemanticVersionInterface $left, SemanticVersionInterface $right)
    {
        $result = $this->compareMajorMinorPatch($left, $right);

        if ($result === 0) {
            $result = $this->comparePre($left, $right);
        }

        return $result;
    }

    /**
     * @param SemanticVersionInterface $left
     * @param SemanticVersionInterface $right
     * @return int
     */
    private function compareMajorMinorPatch(SemanticVersionInterface $left, SemanticVersionInterface $right)
    {
        $result = $left->getMajor() - $right->getMajor();

        if ($result === 0) {
            $result = $left->getMinor() - $right->getMinor();

            if ($result === 0) {
                $result = $left->getPatch() - $right->getPatch();
            }
        }

        return $result;
    }

    /**
     * @param SemanticVersionInterface $left
     * @param SemanticVersionInterface $right
     * @return int
     */
    private function comparePre(SemanticVersionInterface $left, SemanticVersionInterface $right)
    {
        if ($left->getPre() === '') {
            return $right->getPre() === '' ? 0 : 1;
        }

        if ($right->getPre() === '') {
            return -1;
        }

        $leftStack = $left->getPreStack();
        $rightStack = $right->getPreStack();

        $leftCount = count($leftStack);
        $rightCount = count($rightStack);
        $minCount = min($leftCount, $rightCount);

        for ($i = 0; $i < $minCount; $i++) {
            $result = strnatcasecmp($leftStack[$i], $rightStack[$i]);

            if ($result !== 0) {
                return $result;
            }
        }

        return $leftCount - $rightCount;
    }
}
