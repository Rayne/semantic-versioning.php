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
 * Tests for the `autoload.php` file.
 */
class AutoloadTest extends PHPUnit_Framework_TestCase
{
    /**
     *
     */
    public function test()
    {
        require dirname(__DIR__) . '/src/autoload.php';
    }
}
