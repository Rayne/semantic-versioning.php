<?php

/**
 * (c) Dennis Meckel
 *
 * For the full copyright and license information,
 * please view the LICENSE file that was distributed with this source code.
 */

namespace Rayne\SemanticVersioning;

/**
 * Interprets the semantic versioning format `MAJOR.MINOR.PATCH-PRE+META`
 * and is compatible with Semantic Versioning `2.0`.
 *
 * @since 1.0.0-rc.1
 * @see http://semver.org
 */
class SemanticVersion implements SemanticVersionInterface
{
    /**
     * @var int
     */
    private $major;

    /**
     * @var int
     */
    private $minor;

    /**
     * @var int
     */
    private $patch;

    /**
     * @var string
     */
    private $pre;

    /**
     * @var array
     */
    private $preStack;

    /**
     * @var string
     */
    private $meta;

    /**
     * @var array
     */
    private $metaStack;

    /**
     * @var string
     */
    private $version;

    /**
     * @param string $version
     * @throws NoSemanticVersionException On incompatible `$version`.
     */
    public function __construct($version)
    {
        $this->version = (string) $version;

        $matches = [];

        if (!preg_match('#^(\d|[1-9]\d+)\.(\d|[1-9]\d+)\.(\d|[1-9]\d+)(|-[\.0-9A-Za-z-]+)(|\+[\.0-9A-Za-z-]+)$#', $version, $matches)) {
            throw $this->buildException();
        }

        $this->major = (int) $matches[1];
        $this->minor = (int) $matches[2];
        $this->patch = (int) $matches[3];

        $this->setPre($matches[4] ? substr($matches[4], 1) : '');
        $this->setMeta($matches[5] ? substr($matches[5], 1) : '');
    }

    /**
     * @inheritdoc
     */
    public function __toString()
    {
        return $this->getVersion();
    }

    /**
     * @param string $pre
     * @throws NoSemanticVersionException
     */
    private function setPre($pre)
    {
        $this->pre = $pre;
        $this->preStack = $pre === '' ? [] : explode('.', $pre);

        foreach ($this->preStack as $value) {
            if ($value === '' || preg_match('#^0\d+$#', $value)) {
                throw $this->buildException();
            }
        }
    }

    /**
     * @param string $meta
     * @throws NoSemanticVersionException
     */
    private function setMeta($meta)
    {
        $this->meta = $meta;
        $this->metaStack = $meta === '' ? [] : explode('.', $meta);

        foreach ($this->metaStack as $value) {
            if ($value === '') {
                throw $this->buildException();
            }
        }
    }

    /**
     * @return NoSemanticVersionException
     */
    private function buildException()
    {
        return new NoSemanticVersionException(sprintf('Invalid semantic version `%s`.', $this->getVersion()));
    }

    /**
     * @inheritdoc
     */
    public function getMajor()
    {
        return $this->major;
    }

    /**
     * @inheritdoc
     */
    public function getMinor()
    {
        return $this->minor;
    }

    /**
     * @inheritdoc
     */
    public function getPatch()
    {
        return $this->patch;
    }

    /**
     * @inheritdoc
     */
    public function getPre()
    {
        return $this->pre;
    }

    /**
     * @inheritdoc
     */
    public function getPreStack()
    {
        return $this->preStack;
    }

    /**
     * @inheritdoc
     */
    public function getMeta()
    {
        return $this->meta;
    }

    /**
     * @inheritdoc
     */
    public function getMetaStack()
    {
        return $this->metaStack;
    }

    /**
     * @inheritdoc
     */
    public function getVersion()
    {
        return $this->version;
    }
}
