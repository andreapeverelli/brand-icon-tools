<?php

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

	private static function getKeyValue(array $arguments): array
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
}
