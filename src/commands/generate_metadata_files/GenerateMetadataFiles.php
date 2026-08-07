<?php

/*
 *
 * GenerateMetadataFiles.php
 * -----------------------------------
 * Copyright (c) 2026 Andrea Peverelli
 * License: GPL-3.0
 * -----------------------------------
 *
 * PHX-CLI Generate Metadata Files command handler.
 *
 */

namespace AndreaPeverelli\PhxCli;

trait GenerateMetadataFiles
{
	use Utils;
	use Help;

	private const GENERATE_METADATA_FILES_COMMAND = "generate:metadata-files";
	private const GENERATE_METADATA_FILES_DESCRIPTION =
		"Generates manifest/browserconfig/robots/security/humans metadata files based on configurations.";

	private static function getGenerateMetadataFilesArguments(): array|int
	{
		if(is_int($project_root = static::getProjectRoot())) return $project_root;

		return [
			"--phx-config" => [
				"$project_root/phx.config.json",
				"help" => "PHX_CONFIG_FILE",
				"optional" => true,
				"sanitizer" => "file-path",
			],
			"--palette" => [
				"$project_root/palette.json",
				"help" => "PALETTE_FILE",
				"optional" => true,
				"sanitizer" => "file-path",
			],
			"--icons-uri" => [
				"/icons",
				"help" => "ICONS_URI",
				"optional" => true,
				"sanitizer" => "uri"
			],
			"--output" => [
				"$project_root/public",
				"help" => "OUTPUT_DIRECTORY",
				"optional" => true,
				"sanitizer" => "directory-path"
			],
			"--verbose" => [false, "optional" => true],
		];
	}

	private const GENERATE_METADATA_FILES_CLI_CONFIGS = [
		["argument" => "phx-config", "config" => "phx-config-path"],
		["argument" => "palette", "config" => "palette-path"],
	];

	private static function generateMetadataFiles(array &$argv): int
	{
		if($exit = static::loadCliConfigs(argv: $argv, configs: self::GENERATE_METADATA_FILES_CLI_CONFIGS)) return $exit;

		$arguments = static::getCommandArguments(argv: $argv, arguments: static::getGenerateMetadataFilesArguments());

		if(isset($arguments["help"])) return static::help(command: self::GENERATE_METADATA_FILES_COMMAND);

		if(is_int($files = GenerateMetadataFilesService::importFiles(arguments: $arguments))) return $files;
		[
			"phx_config" => $phx_config,
			"palette" => $palette,
		] = $files;

		echo BOLD . "Generating metadata files:\n" . RESET;

		GenerateMetadataFilesService::generateManifest(
			arguments: $arguments,
			phx_config: $phx_config,
			palette: $palette,
		);
		GenerateMetadataFilesService::generateBrowserconfig(
			arguments: $arguments,
			phx_config: $phx_config,
			palette: $palette,
		);
		GenerateMetadataFilesService::generateRobots(arguments: $arguments, phx_config: $phx_config);
		GenerateMetadataFilesService::generateSecurity(arguments: $arguments, phx_config: $phx_config);
		GenerateMetadataFilesService::generateHumans(arguments: $arguments, phx_config: $phx_config);

		static::successMessage(message: "Files metadata created in", output: $arguments["output"]);

		return 0;
	}
}
