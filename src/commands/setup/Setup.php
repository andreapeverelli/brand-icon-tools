<?php

namespace AndreaPeverelli\PhxCli;

trait Setup
{
	private static function setup(array &$argv): int
	{
		if(isset($argv[2]) && $argv[2] === "--help") {
			echo <<<OUTPUT
			PHX-CLI Setup
			Installs PHX and its dependencies.

			Command structure:
				phx setup [--help]
			OUTPUT;
		}

		$arguments_kv = static::getKeyValue(arguments: $argv);

		$verbose = $arguments_kv["--verbose"] ?? false;

		$project_root = static::getProjectRoot();
		$phx_config = json_decode(file_get_contents("$project_root/phx.config.json"), true);

		SetupInternals::initComposer(phx_config: $phx_config,verbose: $verbose);
		SetupInternals::addVcsPhxRepos();
		SetupInternals::installPhx(verbose: $verbose);

		return 0;
	}
}
