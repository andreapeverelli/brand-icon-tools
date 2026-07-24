<?php

namespace AndreaPeverelli\PhxTools;

final class App
{
	use Help;
	use GenerateIconset;

	private function __construct() {}

	final public static function run(array $argv): int
	{
		if ($argv === [] || $argv[1] === "--help") {
			static::help();

			return 0;
		}

		if ($argv[1] === "generate:iconset") {
			return static::generateIconset(argv: $argv);
		}

		echo <<<OUTPUT
		Bad arguments.
		Try 'phx-tools --help' for command list or 'phx-tools {command} --help' for specific tool help.\n"
		OUTPUT;

		return 2;
	}
}
