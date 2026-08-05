<?php

namespace AndreaPeverelli\PhxCli;

final class App
{
	use Utils;

	use Help;
	use Init;
	use RegisterProject;
	use Setup;
	use GenerateConfig;
	use GenerateIconset;
	use GeneratePalette;
	use GenerateMetadataFiles;
	use GenerateTypescale;

	private function __construct() {}

	final public static function run(array $argv): int
	{
		$command = $argv[1] ?? null;

		if($command === "--help") {
			static::help();

			return 0;
		}

		if($command === "--version") {
			echo "PHX-CLI v3.0.0\n";

			return 0;
		}

		if($command === "init") {
			return static::init(argv: $argv);
		}

		if($command === "register:project") {
			return static::registerProject(argv: $argv);
		}

		if($command === "setup") {
			return static::setup($argv);
		}

		if($command === "generate:config") {
			return static::generateConfig(argv: $argv);
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

		if($command === "generate:typescale") {
			return static::generateTypescale(argv: $argv);
		}

		return static::badArguments();
	}
}
