<?php

/*
 *
 * RegisterProject.php
 * -----------------------------------
 * Copyright (c) 2026 Andrea Peverelli
 * License: GPL-3.0
 * -----------------------------------
 *
 * PHX-CLI Register Project command handler.
 *
 */

namespace AndreaPeverelli\PhxCli;

trait RegisterProject
{
	use Utils;
	use Help;

	private const REGISTER_PROJECT_COMMAND = "register:project";
	private const REGISTER_PROJECT_DESCRIPTION =
		"Registers a directory to the user's projects list.";

	private static function getRegisterProjectArguments(): array
	{
		return ["--verbose" => [false, "optional" => true]];
	}

	private static function registerProject(array &$argv): int
	{
		$arguments = self::getCommandArguments(argv: $argv, arguments: static::getRegisterProjectArguments());

		if(isset($arguments["help"])) return static::help(command: self::REGISTER_PROJECT_COMMAND);

		$projects = RegisterProjectService::readProjectsFile();
		$projects = RegisterProjectService::addProjectDirectory(projects: $projects);
		RegisterProjectService::writeProjectsFile(projects: $projects, verbose: $arguments["verbose"]);

		static::successMessage(message: "Project registered.");

		return 0;
	}
}
