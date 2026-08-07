<?php

/*
 *
 * GenerateConfig.php
 * -----------------------------------
 * Copyright (c) 2026 Andrea Peverelli
 * License: GPL-3.0
 * -----------------------------------
 *
 * PHX-CLI Generate Config command handler.
 *
 */

namespace AndreaPeverelli\PhxCli;

trait GenerateConfig
{
	use Utils;
	use Help;

	private const GENERATE_CONFIG_COMMAND = "generate:config";
	private const GENERATE_CONFIG_DESCRIPTION =
		"Generates a project configuration interactivelly.";

	private static function getGenerateConfigArguments(): array|int
	{
		if(is_int($project_root = static::getProjectRoot())) return $project_root;

		return [
			"--app-name" => ["help" => "APP_NAME"],
			"--app-short-name" => ["help" => "APP_SHORT_NAME"],
			"--vendor" => ["help" => "VENDOR"],
			"--description" => ["help" => "DESCRIPTION"],
			"--license" => ["help" => "LICENSE"],
			"--domain" => ["help" => "DOMAIN"],
			"--homepage" => ["help" => "HOMEPAGE", "optional" => true],
			"--languages" => ["help" => "LANGUAGES"],
			"--categories" => ["help" => "CATEGORIES"],
			"--name-surname" => ["help" => "NAME_SURNAME"],
			"--email" => ["help" => "EMAIL"],
			"--personal-website" => ["help" => "PERSONAL_WEBSITE", "optional" => true],
			"--x" => ["help" => "X", "optional" => true],
			"--github" => ["help" => "GITHUB", "optional" => true],
			"--output" => [
				"$project_root/phx.config.json",
				"help" => "OUTPUT_FILE",
				"optional" => true,
				"sanitizer" => "path",
			],
			"--verbose" => [false, "optional" => true],
		];
	}

	private const GENERATE_CONFIG_CLI_CONFIGS = [
		["argument" => "output", "config" => "phx-config-path"],
	];

	private static function generateConfig(array &$argv): int
	{
		if($exit = static::loadCliConfigs(argv: $argv, configs: self::GENERATE_CONFIG_CLI_CONFIGS)) return $exit;

		$arguments = static::getCommandArguments(argv: $argv, arguments: static::getGenerateConfigArguments());

		if(isset($arguments["help"])) return static::help(command: self::GENERATE_CONFIG_COMMAND);

		$config = GenerateConfigService::makeConfig(arguments: $arguments);
		GenerateConfigService::writeConfig(arguments: $arguments, config: $config);

		static::writeCliConfigs(arguments: $arguments, configs: self::GENERATE_CONFIG_CLI_CONFIGS);

		static::successMessage(message: "PHX configuration generated in", output: $arguments["output"]);

		return 0;
	}
}
