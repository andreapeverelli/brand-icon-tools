<?php

namespace AndreaPeverelli\PhxCli;

trait Init
{
	private static function init(array &$argv): int
	{
		if(isset($argv[2]) && $argv[2] === "--help") {
			echo <<<OUTPUT
			PHX-CLI Init
			Initialize a new PHX project.

			Command structure:
				phx init [--help]
			OUTPUT;
		}

		if(static::registerProject($argv)) return 1;
		if(static::generateConfig($argv)) return 1;
		if(static::setup($argv)) return 1;
		if(static::generateIconset($argv)) return 1;
		if(static::generatePalette($argv)) return 1;
		if(static::generateTypescale($argv)) return 1;

		return 0;
	}
}
