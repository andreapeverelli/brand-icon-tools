<?php

namespace AndreaPeverelli\PhxCli;

trait RegisterProject
{
	private static function registerProject(array &$argv): int
	{
		if(isset($argv[2]) && $argv[2] === "--help") {
			echo <<<OUTPUT
			PHX-CLI Register Project
			Adds an already existing project to the user's projects list.

			Command structure:
				phx register:project [--help]\n
			OUTPUT;
		}

		$arguments_kv = static::getKeyValue(arguments: $argv);

		$verbose = $arguments_kv["--verbose"] ?? false;

		$project = getcwd();
		$home = getenv("HOME");
		$user_file = "$home/.config/phx/projects.config.json";

		if(!file_exists("$home/.config/phx/")) {
			if(!file_exists("$home/.config/")) {
				mkdir("$home/.config/");
			}

			mkdir("$home/.config/phx/");
		}

		$projects = [];
		if(file_exists($user_file)) {
			$projects = json_decode(file_get_contents($user_file), true);
			unlink($user_file);

			if(!in_array($project, $projects)) {
				array_push($projects, $project);
			}
		} else {
			$projects = [$project];
		}

		file_put_contents($user_file, json_encode($projects));

		return 0;
	}
}
