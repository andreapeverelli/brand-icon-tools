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
				$arguments_kv[$arguments[$i]] = $arguments[$i+1];
				$i++;
			} else {
				array_push($arguments_kv, $arguments[$i]);
			}
		}

		return $arguments_kv;
	}
}
