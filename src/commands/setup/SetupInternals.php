<?php

namespace AndreaPeverelli\PhxCli;

final class SetupInternals
{
	use Utils;

	final public static function initComposer(array &$phx_config, bool &$verbose): void
	{
		$vendor = $phx_config["vendor"];
		$app_name = $phx_config["app_name"];
		$description = $phx_config["description"];
		$name = $phx_config["name_surname"];
		$email = $phx_config["email"];
		$homepage = $phx_config["homepage"] ? "--homepage=\"{$phx_config["homepage"]}\"" : "";
		$license = $phx_config["license"];

		static::runCommand(
			description: ["Initializing Composer project:", "bold" => true, "new_line" => true],
			command: <<<BASH
			composer init \
				--no-interaction \
				--name="$vendor/$app_name" \
				--description="$description" \
				--author="$name <$email>" \
				--type="project"$homepage \
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

		$composer = json_decode(file_get_contents("./composer.json"), true);

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

		unlink("./composer.json");
		file_put_contents("./composer.json", json_encode($composer, JSON_PRETTY_PRINT));

		echo BOLD . GREEN . "SUCCESS\n\n" . RESET;
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
