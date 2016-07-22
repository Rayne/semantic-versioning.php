<?php

$finder = Symfony\CS\Finder\DefaultFinder::create()
	->in([
		__DIR__ . DIRECTORY_SEPARATOR . 'src',
		__DIR__ . DIRECTORY_SEPARATOR . 'tests',
	]);

return Symfony\CS\Config\Config::create()
	->level(\Symfony\CS\FixerInterface::PSR2_LEVEL)
	->fixers(['short_array_syntax'])
	->finder($finder);