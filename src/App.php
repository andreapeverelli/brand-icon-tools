<?php

/*
 *
 * App.php
 * -----------------------------------
 * Copyright (c) 2026 Andrea Peverelli
 * License: GPL-3.0
 * -----------------------------------
 *
 * Main application command dispatcher.
 *
 */

namespace AndreaPeverelli\PhxCli;

final class App
{
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

	private const COMMANDS = [
		self::INIT_COMMAND,
		self::REGISTER_PROJECT_COMMAND,
		self::SETUP_COMMAND,
		self::GENERATE_CONFIG_COMMAND,
		self::GENERATE_ICONSET_COMMAND,
		self::GENERATE_PALETTE_COMMAND,
		self::GENERATE_METADATA_FILES_COMMAND,
		self::GENERATE_TYPESCALE_COMMAND,
	];

	final public static function run(array $argv): int
	{
		$command = $argv[1] ?? null;

		if($command === "--version") {
			return static::printVersion();
		}

		if($command === "--help") {
			return static::help();
		}

		if($command === self::INIT_COMMAND) {
			return static::init(argv: $argv);
		}

		if($command === self::REGISTER_PROJECT_COMMAND) {
			return static::registerProject(argv: $argv);
		}

		if($command === self::SETUP_COMMAND) {
			return static::setup(argv: $argv);
		}

		if($command === self::GENERATE_CONFIG_COMMAND) {
			return static::generateConfig(argv: $argv);
		}

		if($command === self::GENERATE_ICONSET_COMMAND) {
			return static::generateIconset(argv: $argv);
		}

		if($command === self::GENERATE_PALETTE_COMMAND) {
			return static::generatePalette(argv: $argv);
		}

		if($command === self::GENERATE_METADATA_FILES_COMMAND) {
			return static::generateMetadataFiles(argv: $argv);
		}

		if($command === self::GENERATE_TYPESCALE_COMMAND) {
			return static::generateTypescale(argv: $argv);
		}

		return static::badArguments();
	}

	private static function printVersion(): int
	{
		$composer_json = json_decode(file_get_contents("/usr/share/phx-cli/composer.json"), true);

		echo "PHX-CLI v{$composer_json["version"]}\n";

		return 0;
	}
}
