<?php

namespace AndreaPeverelli\PhxTools;

final class App
{
	use Utils;

	use Help;
	use Init;
	use GenerateIconset;
	use GeneratePalette;
	use GenerateMetadataFiles;

	private function __construct() {}

	final public static function run(array $argv): int
	{
		$command = $argv[1] ?? null;

		if($command === "--help") {
			static::help();

			return 0;
		}

		if($command === "--version") {
			echo "PHX-TOOLS v2.4.0\n";

			return 0;
		}

		if($command === "init") {
			return static::init(argv: $argv);
		}

		if($command === "generate:iconset") {
			return static::generateIconset(argv: $argv);
		}

		if($command === "generate:palette") {
			return static::generatePalette(argv: $argv);
		}

		if($command === "generate:metadata-files") {
			return static::generateMetadataFiles(argv: $argv);
		}

		return static::badArguments();
	}
}
