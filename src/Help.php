<?php

/*
 *
 * Help.php
 * -----------------------------------
 * Copyright (c) 2026 Andrea Peverelli
 * License: GPL-3.0
 * -----------------------------------
 *
 * Generate --help output for PHX-CLI and its commands.
 *
 */

namespace AndreaPeverelli\PhxCli;

trait Help
{
	private static function help(?string $command = null): int
	{
		$getDescription = function(string $s) {
			return constant(self::class . "::" . strtoupper(implode("_", explode(":", str_replace("-", ":", $s)))) . "_DESCRIPTION");	
		};

		if(!$command) {
			$commands = [];
			foreach(self::COMMANDS as $_command) {
				$description = $getDescription(s: $_command);
				array_push($commands, <<<OUTPUT
					| $_command
						$description
				OUTPUT);
			}
			$commands = implode("\n", $commands);

			echo <<<OUTPUT
			PHX-CLI
			CLI companion for PHX framework.
			
			Available commands:
			$commands

			Available flags:
				| --version
					Shows PHX-CLI version
				| --help
					Shows this help message\n
			OUTPUT;

			return 0;
		} else {
			$command_name = implode(" ", array_map("ucfirst", explode(":", $command)));
			$description = $getDescription(s: $command);
			$arguments_function_name = "get" . implode("", array_map("ucfirst", explode(":", str_replace("-", ":", $command)))) . "Arguments";
			if(is_int($arguments = static::$arguments_function_name())) return $arguments;

			$command_structure = ["phx", $command];
			foreach($arguments as $key => $value) {
				if(in_array("optional", $value)) {
					$help =  isset($value["help"]) ? " {$value["help"]}" : "";

					array_push($command_structure, "[$key$help]");
				} else {
					$help = $value["help"] ? " {$value["help"]}" : "";

					array_push($command_structure, "$key$help");
				}
			}
			$command_structure = implode(" ", $command_structure);

			echo <<<OUTPUT
			PHX-CLI $command_name
			$description

			Command structure:
				$command_structure
				phx $command --help\n
			OUTPUT;

			return 0;
		}

		return 1;
	}
}
