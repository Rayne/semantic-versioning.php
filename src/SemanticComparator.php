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
	 * @return int `0` if both versions are equal, `-1` if `$left` is smaller and `1` if `$left` is greater.
	 */
	public function compare(SemanticVersion $left, SemanticVersion $right) {
		if ($left->getMajor() < $right->getMajor()) return -1;
		if ($left->getMajor() > $right->getMajor()) return  1;

		if ($left->getMinor() < $right->getMinor()) return -1;
		if ($left->getMinor() > $right->getMinor()) return  1;

		if ($left->getPatch() < $right->getPatch()) return -1;
		if ($left->getPatch() > $right->getPatch()) return  1;

		return $this->comparePre($left, $right);
	}

	/**
	 * @param SemanticVersion $left
	 * @param SemanticVersion $right
	 * @return int
	 */
	private function comparePre(SemanticVersion $left, SemanticVersion $right) {
		if ($left->getPre() !== '' && $right->getPre() === '') return -1;
		if ($left->getPre() === '' && $right->getPre() !== '') return  1;

		$leftStack = $left->getPreStack();
		$rightStack = $right->getPreStack();

		$leftCount = count($leftStack);
		$rightCount = count($rightStack);
		$minCount = min($leftCount, $rightCount);

		for ($i = 0; $i < $minCount; $i++) {
			$result = strnatcasecmp($leftStack[$i], $rightStack[$i]);

			if ($result !== 0) {
				return $this->sign($result);
			}
		}

		return $this->sign($leftCount - $rightCount);
	}

	/**
	 * @param int $number
	 * @return int
	 */
	private function sign($number) {
		return $number < 0 ? - 1 : ($number === 0 ? 0 : 1);
	}
}