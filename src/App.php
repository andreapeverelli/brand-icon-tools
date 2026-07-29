<?php

namespace AndreaPeverelli\PhxTools;

final class App
{
	use Utils;

	use Help;
	use GenerateIconset;
	use GenerateManifest;

	private function __construct() {}

	final public static function run(array $argv): int
	{
		$command = $argv[1] ?? null;

		if($command === "--help") {
			static::help();

			return 0;
		}

		if($command === "--version") {
			echo "PHX-TOOLS v2.1.0\n";

			return 0;
		}

		if($command === "generate:iconset") {
			return static::generateIconset(argv: $argv);
		}

		if($comman === "generate:manifest") {
			return static::generateManifest(argv: $argv);
		}

		return static::badArguments();
	}
}
