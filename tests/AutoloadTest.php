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
     * This test is only verifying if all named files in `autoload.php` exist.
     * It's not sufficient for testing if the inclusion order is correct when
     * there is a registered class loader (e.g. the one provided by `Composer`).
     *
     * @see testInclusionOrder()
     */
    public function testNamedFiles()
    {
        require dirname(__DIR__) . '/src/autoload.php';
    }

    /**
     * This test spawns a new PHP process which is only responsible for testing
     * the file inclusion order without a registered class loader.
     *
     * The spawned PHP process uses the same binary as its parent's process.
     */
    public function testInclusionOrder() {
        $insecureCommandStack = [PHP_BINARY];

        // Prevent process blocking when working with `phpdbg`
        if (preg_match('#phpdbg$#', PHP_BINARY))
        {
            $insecureCommandStack[] = '-qrr';
        }

        $insecureCommandStack[] = dirname(__DIR__) . '/src/autoload.php';

        $this->escapeAndExecute($insecureCommandStack, $output, $exit);

        $this->assertSame([], $output);
        $this->assertSame(0, $exit);
    }

    /**
     * Escapes the insecure command stack and executes the resulting secure
     * command.
     *
     * @param string[] $insecureCommandStack Unescaped command parts.
     * @param string[] $output Will be filled with every line of output of the executed command.
     * @param int $exit Will be filled with the return status of the executed command.
     */
    private function escapeAndExecute(array $insecureCommandStack, &$output, &$exit)
    {
        $command = $this->escapeInsecureCommandStack($insecureCommandStack);
        exec($command, $output, $exit);
    }

    /**
     * @param string[] $insecureCommandStack Unescaped command parts.
     * @return string The escaped command.
     */
    private function escapeInsecureCommandStack(array $insecureCommandStack)
    {
        $escapedCommandStack = [];

        foreach ($insecureCommandStack as $part)
        {
            $escapedCommandStack[] = escapeshellarg($part);
        }

        return implode(' ', $escapedCommandStack);
    }
}