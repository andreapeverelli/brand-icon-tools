<?php

/*
 *
 * RegisterProjectService.php
 * -----------------------------------
 * Copyright (c) 2026 Andrea Peverelli
 * License: GPL-3.0
 * -----------------------------------
 *
 * PHX-CLI Register Project command functionalities.
 *
 */

namespace AndreaPeverelli\PhxCli;

final class RegisterProjectService
{
	use Utils;

	private function __construct() {}

	final public static function readProjectsFile(): array
	{	
		$home = getenv("HOME");
		$projects_file_path = "$home/.config/phx/projects.config.json";

		if(file_exists($projects_file_path)) {
			return json_decode(file_get_contents($projects_file_path), true);
		}

		return [];
	}

	final public static function addProjectDirectory(array &$projects): array
	{
		$project = getcwd();

		if(!in_array($project, $projects)) {
			array_push($projects, $project);
		}

		return $projects;
	}

	final public static function writeProjectsFile(array &$projects, bool &$verbose): void
	{
		$home = getenv("HOME");
		$phx_user_level_directory = "$home/.config/phx";
		$projects_file_path = "$phx_user_level_directory/projects.config.json";

		static::deleteFile(file_name: $projects_file_path);

		static::ensureDirectoryExists(directory: $phx_user_level_directory, verbose: $verbose);
		file_put_contents($projects_file_path, json_encode($projects));
	}
}
