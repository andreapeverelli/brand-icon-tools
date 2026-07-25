<?php

namespace AndreaPeverelli\PhxTools;

final class App
{
	use Utils;

	use Help;
	use GenerateIconset;

	private function __construct() {}

	final public static function run(array $argv): int
	{
		$command = $argv[1] ?? null;

		if($command === "--help") {
			static::help();

			return 0;
		}

		if($command === "--version") {
			echo "PHX-TOOLS v2.0.0\n";

			return 0;
		}

		if ($command === "generate:iconset") {
			return static::generateIconset(argv: $argv);
		}

		return static::badArguments();
	}
}
