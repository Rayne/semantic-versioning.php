<?php

/**
 * (c) Dennis Meckel.
 *
 * For the full copyright and license information,
 * please view the LICENSE file that was distributed with this source code.
 */

namespace Rayne\SemanticVersioning;

/**
 * Interprets the semantic versioning format `MAJOR.MINOR.PATCH-PRE+META`.
 *
 * @since 1.0.0-rc.3
 * @see http://semver.org
 */
interface SemanticVersionInterface
{
    /**
     * @return string
     */
    public function __toString();

    /**
     * @return int Non-negative integer without leading zeroes.
     */
    public function getMajor();

    /**
     * @return int Non-negative integer without leading zeroes.
     */
    public function getMinor();

    /**
     * @return int Non-negative integer without leading zeroes.
     */
    public function getPatch();

    /**
     * @return string Optional pre-release information.
     */
    public function getPre();

    /**
     * @return string[]
     *
     * @since 1.0.0-rc.2
     */
    public function getPreStack();

    /**
     * @return string Optional metadata.
     */
    public function getMeta();

    /**
     * @return string[]
     *
     * @since 1.0.0-rc.2
     */
    public function getMetaStack();

    /**
     * @return string
     */
    public function getVersion();
}
