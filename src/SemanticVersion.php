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
class SemanticVersion {
	/**
	 * @var int
	 */
	private $major, $minor, $patch;

	/**
	 * @var string
	 */
	private $pre, $meta, $version;

	/**
	 * @param string $version
	 * @throws NoSemanticVersionException On incompatible `$version`.
	 */
	public function __construct($version) {
		$this->version = (string) $version;

		$matches = [];

		if (!preg_match('#^(\d|[1-9]\d+)\.(\d|[1-9]\d+)\.(\d|[1-9]\d+)(|-[\.0-9A-Za-z-]+)(|\+[\.0-9A-Za-z-]+)$#', $version, $matches)) {
			throw $this->buildException();
		}

		$this->major = (int) $matches[1];
		$this->minor = (int) $matches[2];
		$this->patch = (int) $matches[3];

		$this->pre = $matches[4] ? substr($matches[4], 1) : '';
		$this->meta = $matches[5] ? substr($matches[5], 1) : '';

		$preStack = $this->pre === '' ? [] : explode('.', $this->pre);
		$metaStack = $this->meta === '' ? [] : explode('.', $this->meta);

		foreach($preStack as $value) {
			if ($value === '' || !($value === '0' || ltrim($value, '0') === $value)) {
				throw $this->buildException();
			}
		}

		foreach($metaStack as $value) {
			if ($value === '') {
				throw $this->buildException();
			}
		}
	}

	/**
	 * @return string
	 */
	public function __toString() {
		return $this->getVersion();
	}

	/**
	 * @return NoSemanticVersionException
	 */
	private function buildException() {
		return new NoSemanticVersionException(sprintf('Invalid semantic version `%s`.', $this->getVersion()));
	}

	/**
	 * @return int Non-negative integer without leading zeroes.
	 */
	public function getMajor() {
		return $this->major;
	}

	/**
	 * @return int Non-negative integer without leading zeroes.
	 */
	public function getMinor() {
		return $this->minor;
	}

	/**
	 * @return int Non-negative integer without leading zeroes.
	 */
	public function getPatch() {
		return $this->patch;
	}

	/**
	 * @return string Optional pre-release information.
	 */
	public function getPre() {
		return $this->pre;
	}

	/**
	 * @return string Optional metadata.
	 */
	public function getMeta() {
		return $this->meta;
	}

	/**
	 * @return string
	 */
	public function getVersion() {
		return $this->version;
	}
}