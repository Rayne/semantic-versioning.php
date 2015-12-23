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
class SemanticComparator {
	/**
	 * @param SemanticVersion $left
	 * @param SemanticVersion $right
	 * @return int
	 * @see compare()
	 */
	public function __invoke(SemanticVersion $left, SemanticVersion $right) {
		return $this->compare($left, $right);
	}

	/**
	 * Build metadata is ignored when determining version precedence.
	 *
	 * @param SemanticVersion $left
	 * @param SemanticVersion $right
	 * @return int `0` if both versions are equal, `< 0` if `$left` is smaller and `> 0` if `$left` is greater.
	 */
	public function compare(SemanticVersion $left, SemanticVersion $right) {
		$result = $this->compareMajorMinorPatch($left, $right);

		if ($result === 0) {
			$result = $this->comparePre($left, $right);
		}

		return $result;
	}

	/**
	 * @param SemanticVersion $left
	 * @param SemanticVersion $right
	 * @return int
	 */
	private function compareMajorMinorPatch(SemanticVersion $left, SemanticVersion $right) {
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
	 * @param SemanticVersion $left
	 * @param SemanticVersion $right
	 * @return int
	 */
	private function comparePre(SemanticVersion $left, SemanticVersion $right) {
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