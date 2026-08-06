<?php

/*
 *
 * Setup.php
 * -----------------------------------
 * Copyright (c) 2026 Andrea Peverelli
 * License: GPL-3.0
 * -----------------------------------
 *
 * PHX-CLI Setup command handler.
 *
 */

namespace AndreaPeverelli\PhxCli;

trait Setup
{
	use Utils;
	use Help;

	private const SETUP_COMMAND = "setup";
	private const SETUP_DESCRIPTION =
			"Installs PHX and its dependencies.";

	private static function getSetupArguments(): array|int
	{
		if(is_int($project_root = static::getProjectRoot())) return $project_root;

		return [
			"--phx-config" => [
				"$project_root/phx.config.json",
				"help" => "PHX_CONFIG_FILE",
				"optional" => true,
			],
			"--verbose" => [false, "optional" => true],
		];
	}

	private static function setup(array &$argv): int
	{
		if(is_int($arguments = static::getSetupArguments())) return $arguments;
		$arguments = static::getCommandArguments(argv: $argv, arguments: $arguments);

		if(isset($arguments["help"])) return static::help(command: self::SETUP_COMMAND);

		if(is_int($phx_config = SetupService::readConfigFile(phx_config: $arguments["phx-config"]))) return $phx_config;
		SetupService::initComposer(phx_config: $phx_config, verbose: $arguments["verbose"]);
		SetupService::addVcsPhxRepos();
		SetupService::installPhx(verbose: $arguments["verbose"]);

		static::successMessage(message: "PHX project setup done.");

		return 0;
	}
}
