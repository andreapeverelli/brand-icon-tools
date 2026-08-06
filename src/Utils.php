<?php

/*
 *
 * Utils.php
 * -----------------------------------
 * Copyright (c) 2026 Andrea Peverelli
 * License: GPL-3.0
 * -----------------------------------
 *
 * Shared utilities used by PHX-CLI commands.
 *
 */

namespace AndreaPeverelli\PhxCli;

trait Utils
{
	private static function getProjectRoot(): string|int
	{
		$home = getenv("HOME");
		if(file_exists("$home/.config/phx/projects.config.json")) {
			$projects = json_decode(file_get_contents("$home/.config/phx/projects.config.json"), true);
			$cwd = getcwd();

			foreach($projects as $project) {
				if(str_contains($cwd, $project)) {
					return $project;
				}
			}
		}

		echo <<<OUTPUT
		Project root not found.
		Run 'phx init' to create a project or 'phx register:project' for an already existing one.
		OUTPUT;

		return 1;
	}

	private static function badArguments(?string $tool = null): int
	{
		if(!$tool) {
			echo <<<OUTPUT
			Bad arguments.
			Try 'phx --help' for command list or 'phx {command} --help' for specific tool help.\n
			OUTPUT;

			return 2;
		}

		echo <<<OUTPUT
		Bad arguments.
		Try 'phx $tool --help' for tool help.\n
		OUTPUT;

		return 2;
	}

	private static function parseArguments(array $arguments): array
	{
		$arguments_kv = [];

		for($i = 0; $i<count($arguments); $i++) {
			if(str_starts_with($arguments[$i], "--")) {
				if(!isset($arguments[$i+1]) || str_starts_with($arguments[$i+1], "--")) {
					$arguments_kv[$arguments[$i]] = true;
				} else {
					$arguments_kv[$arguments[$i]] = $arguments[$i+1];
					$i++;
				}
			} else {
				array_push($arguments_kv, $arguments[$i]);
			}
		}

		return $arguments_kv;
	}

	private static function getCommandArguments(array &$argv, array $arguments): array
	{
		$command_arguments = [];
		if(in_array("--help", $argv)) $command_arguments["help"] = true;

		if(!isset($command_arguments["help"])) {
			$parsed_arguments = static::parseArguments(arguments: array_slice($argv, 2));

			foreach($arguments as $key => $value) {
				$new_key = ltrim($key, "-");
				$command_arguments[$new_key] = $value[0] ?? null;

				if(!isset($parsed_arguments[$key]) && $new_key !== "verbose") {
					$format = isset($value["format"]) ? " ({$value["format"]})" : "";
					$user_input = static::askUserInput(
						prompt: implode(" ", array_map("ucfirst", explode("_", strtolower($value["help"])))) . "$format",
						optional: isset($value["optional"]) ? true : false,
					);
					if($user_input !== "" || !isset($value[0])) $command_arguments[$new_key] = $user_input;
				}

				if(isset($value["sanitizer"])) {
					if($value["sanitizer"] === "path")
						$command_arguments[$new_key] = static::pathSanitizer(value: $command_arguments[$new_key]);
				}
			}
		}

		return $command_arguments;
	}

	private static function pathSanitizer(string $value): string
	{
		if(str_ends_with($value, "/")) return substr($value, 0, strlen($value) - 1);

		return $value;
	}

	private static function runCommand(
		string $command,
		bool $verbose,
		bool $get_output = false,
		null|string|array $description = null,
		?string $verbose_argument = null,
		?string $error_message = null,
	): int|string
	{
		if($description) {
			if(gettype($description) === "array") {
				$bold = $description["bold"] ?? false;
				$new_line = $description["new_line"] ?? false;
				$description = $description[0];
			} else {
				$bold = false;
				$new_line = false;
			}

			if($bold) {
				echo BOLD . "$description " . RESET;
			} else {
				echo "$description ";
			}
		}

		if($verbose && $verbose_argument) {
			$command = str_replace("@verbose_argument()", "$verbose_argument ", $command);
		} else {
			$command = str_replace("@verbose_argument()", "", $command);
		}

		exec("$command 2>&1", $output, $exit_code);
		$output = implode("\n", $output) . "\n";

		if($exit_code) {
			if($error_message) {
				throw new \RuntimeException("$error_message\n\n$output");
			} else {
				throw new \RuntimeException(<<<ERROR
				PHX-TOOLS failed to run '$command':
				
				$output
				ERROR);
			}
		}

		if($description) {
			$suffix = $new_line ? "\n\n" : "\n";
			echo BOLD . GREEN . "SUCCESS$suffix" . RESET;
		}

		if($verbose) {
			echo $output;
		}

		if($get_output) {
			return $output;
		} else {
			return 0;
		}
	}

	private static function ensureDirectoryExists(string $directory, bool $verbose): void
	{
		if(!file_exists($directory)) {
			static::runCommand(
				command: "mkdir -p $directory",
				verbose: $verbose,
			);
		}
	}

	private static function deleteFile(string $file_name): void
	{
		if(file_exists($file_name)) unlink($file_name);
	}

	private static function askUserInput(string $prompt, bool $optional = false): string
	{
		if($optional) {
			return trim(readline("$prompt (optional): "));
		} else {
			$input = "";
			do {
				$input = trim(readline("$prompt: "));
			} while($input === "");

			return $input;
		}
	}

	private static function successMessage(string $message, ?string $output = null): void
	{
		$line = $output ? "$message " . GREEN . $output . RESET . BOLD . ".\n" : "$message\n";

		echo BOLD . GREEN . "\n" . str_repeat("#", strlen($message)) . "\n" . RESET;
		echo BOLD . $line . RESET;
		echo BOLD . GREEN . str_repeat("#", strlen($message)) . "\n" . RESET;
	}
}
