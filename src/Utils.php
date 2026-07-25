<?php

namespace AndreaPeverelli\PhxTools;

trait Utils
{
	private static function badArguments(?string $tool = null): int
	{
		if(!$tool) {
			echo <<<OUTPUT
			Bad arguments.
			Try 'phx-tools --help' for command list or 'phx-tools {command} --help' for specific tool help.\n
			OUTPUT;

			return 2;
		}

		echo <<<OUTPUT
		Bad arguments.
		Try 'phx-tools $tool --help' for tool help.\n
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
		?string $verbose_argument = null,
		?string $error_message = null,
	): int
	{
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

		if($verbose) {
			echo $output;
		}

		return 0;
	}
}
