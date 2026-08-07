<?php

/*
 *
 * SetupService.php
 * -----------------------------------
 * Copyright (c) 2026 Andrea Peverelli
 * License: GPL-3.0
 * -----------------------------------
 *
 * PHX-CLI Setup command functionalities.
 *
 */

namespace AndreaPeverelli\PhxCli;

final class SetupService
{
	use Utils;

	private function __construct() {}

	final public static function readConfigFile(string &$phx_config): array|int
	{
		if(file_exists($phx_config)) {
			return json_decode(file_get_contents($phx_config), true);
		} else {
			echo <<<OUTPUT
			PHX-CLI Setup needs a valid phx-config file.
			Try 'phx generate:config'.
			OUTPUT;

			return 1;
		}
	}

	final public static function initComposer(array &$phx_config, bool &$verbose): void
	{
		$vendor = $phx_config["vendor"];
		$app_name = $phx_config["app-name"];
		$description = $phx_config["description"];
		$name = $phx_config["name-surname"];
		$email = $phx_config["email"];
		$license = $phx_config["license"];

		static::runCommand(
			description: ["Initializing Composer project:", "bold" => true, "new_line" => true],
			command: <<<BASH
			composer init \
				--no-interaction \
				--name="$vendor/$app_name" \
				--description="$description" \
				--author="$name <$email>" \
				--type="project" \
				--license="$license" \
				--autoload="src/" \
				--stability="stable"	
			BASH,
			verbose: $verbose,
		);
	}

	final public static function addVcsPhxRepos(): void
	{
		echo BOLD . "Adding PHX VCS repos to Composer: " . RESET;

		$project_root = static::getProjectRoot();
		$composer_file_path = "$project_root/composer.json";

		$composer = json_decode(file_get_contents($composer_file_path), true);

		$composer["repositories"] = [
			[
				"type" => "vcs",
				"url" => "https://github.com/andreapeverelli/phx-core.git",
			],
			[
				"type" => "vcs",
				"url" => "https://github.com/andreapeverelli/phx-ui.git",
			]
		];
		$composer["require"] = (object)[];

		static::deleteFile(file_name: $composer_file_path);
		file_put_contents($composer_file_path, json_encode($composer, JSON_PRETTY_PRINT));

		echo BOLD . GREEN . "SUCCESS\n" . RESET;
	}

	final public static function installPhx(bool &$verbose): void
	{
		/*
		static::runCommand(
			description: ["Installing PHX-CORE:", "bold" => "true"],
			command: "composer require andreapeverelli/phx-core",
			verbose: $verbose,
		);
		static::runCommand(
			description: ["Installing PHX-UI:", "bold" => "true", "new_line" => true],
			command: "composer require andreapeverelli/phx-ui",
			verbose: $verbose,
		);
		 */
	}
}
