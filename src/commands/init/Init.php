<?php

/*
 *
 * Init.php
 * -----------------------------------
 * Copyright (c) 2026 Andrea Peverelli
 * License: GPL-3.0
 * -----------------------------------
 *
 * PHX-CLI Init command orchestrator.
 *
 */

namespace AndreaPeverelli\PhxCli;

trait Init
{
	use RegisterProject;
	use GenerateConfig;
	use Setup;
	use GenerateIconset;
	use GeneratePalette;
	use GenerateTypescale;

	private const INIT_COMMAND = "init";
	private const INIT_DESCRIPTION =
		"Initialize a new PHX project.";

	private static function getInitArguments(): array|int
	{
		if(is_int($register_project_arguments = static::getRegisterProjectArguments())) return $register_project_arguments;
		if(is_int($generate_config_arguments = static::getGenerateConfigArguments())) return $generate_config_arguments;
		if(is_int($setup_arguments = static::getSetupArguments())) return $setup_arguments;
		if(is_int($generate_iconset_arguments = static::getGenerateIconsetArguments())) return $generate_iconset_arguments;
		if(is_int($generate_palette_arguments = static::getGeneratePaletteArguments())) return $generate_palette_arguments;
		if(is_int($generate_metadata_files_arguments = static::getGenerateMetadataFilesArguments())) return $generate_metadata_files_arguments;
		if(is_int($generate_typescale_arguments = static::getGenerateTypescaleArguments())) return $generate_typescale_arguments;

		$arguments = [
			...$register_project_arguments,
			...$generate_config_arguments,
			...$setup_arguments,
			...$generate_iconset_arguments,
			...$generate_palette_arguments,
			...$generate_metadata_files_arguments,
			...$generate_typescale_arguments,
		];
		unset($arguments["--output"]);

		ksort($arguments);
		return $arguments;
	}

	private static function init(array &$argv): int
	{
		if(in_array("--help", $argv)) return static::help(command: self::INIT_COMMAND);

		static::successMessage(message: "Welcome to PHX.\nInitialization process is starting.");

		if($exit = static::registerProject(argv: $argv)) return $exit;
		echo "\n";

		if($exit = static::generateConfig(argv: $argv)) return $exit;
		static::removeArgument(argv: $argv, argument: "output");
		echo "\n";

		if($exit = static::setup(argv: $argv)) return $exit;
		echo "\n";

		if($exit = static::generateIconset(argv: $argv)) return $exit;
		static::removeArgument(argv: $argv, argument: "output");
		echo "\n";

		if($exit = static::generatePalette(argv: $argv)) return $exit;
		static::removeArgument(argv: $argv, argument: "output");
		echo "\n";

		if($exit = static::generateMetadataFiles(argv: $argv)) return $exit;
		static::removeArgument(argv: $argv, argument: "output");
		echo "\n";

		if($exit = static::generateTypescale(argv: $argv)) return $exit;

		return 0;
	}
}
