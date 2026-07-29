<?php

namespace AndreaPeverelli\PhxTools;

trait GenerateManifest
{
	private static function generateManifest(array $argv): void
	{
		if(!isset($argv[2])) {
			static::badArguments(tool: "generate:manifest");
		}

		if($argv[2] === "--help") {
			echo <<<OUTPUT
			PHX-TOOLS Generate Manifest

			Command structure:
				phx-tools generate:manifest
OUTPUT;

			return 0;
		}

		$arguments_kv = static::getKeyValue(arguments: array_slice($argv, 2));

		$verbose = in_array("--verbose", $arguments_kv) ? true : false;
	}
}
