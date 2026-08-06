<?php

/*
 *
 * GenerateConfigService.php
 * -----------------------------------
 * Copyright (c) 2026 Andrea Peverelli
 * License: GPL-3.0
 * -----------------------------------
 *
 * PHX-CLI Generate Config command functionalities.
 *
 */

namespace AndreaPeverelli\PhxCli;

final class GenerateConfigService
{
	use Utils;

	private function __construct() {}

	final public static function makeConfig(array &$arguments): array
	{
		$config = $arguments;
		unset($config["output"],$config["verbose"], $config["help"]);

		return $config;
	}

	final public static function writeConfig(array &$arguments, array &$config): void
	{
		$output = $arguments["output"];

		file_put_contents($output, json_encode($config, JSON_PRETTY_PRINT));
	}
}

