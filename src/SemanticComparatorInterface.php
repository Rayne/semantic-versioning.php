<?php

/**
 * (c) Dennis Meckel
 *
 * For the full copyright and license information,
 * please view the LICENSE file that was distributed with this source code.
 */

namespace Rayne\SemanticVersioning;

/**
 * Semantic Versioning Comparator.
 *
 * @since 1.0.0-rc.3
 * @see http://semver.org
 */
interface SemanticComparatorInterface
{
    /**
     * @param SemanticVersionInterface $left
     * @param SemanticVersionInterface $right
     * @return int
     * @see compare()
     */
    public function __invoke(SemanticVersionInterface $left, SemanticVersionInterface $right);

    /**
     * Build metadata is ignored when determining version precedence.
     *
     * @param SemanticVersionInterface $left
     * @param SemanticVersionInterface $right
     * @return int `0` if both versions are equal, `< 0` if `$left` is smaller and `> 0` if `$left` is greater.
     */
    public function compare(SemanticVersionInterface $left, SemanticVersionInterface $right);
}
