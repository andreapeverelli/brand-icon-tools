<?php

/*
 *
 * GeneratePalette.php
 * -----------------------------------
 * Copyright (c) 2026 Andrea Peverelli
 * License: GPL-3.0
 * -----------------------------------
 *
 * PHX-CLI Generate Palette command handler.
 *
 */

namespace AndreaPeverelli\PhxCli;

trait GeneratePalette
{
	use Utils;
	use Help;

	private const GENERATE_PALETTE_COMMAND = "generate:palette";
	private const GENERATE_PALETTE_DESCRIPTION =
		"Generates sRGB/Display P3/Rec. 2020 tonal palettes using HCT Material You core palette based on an Hex source color.";

	private static function getGeneratePaletteArguments(): array|int
	{
		if(is_int($project_root = static::getProjectRoot())) return $project_root;

		return [
			"--source-color" => [
				"help" => "HEX_SOURCE_COLOR",
				"format" => "#hhhhhh",
			],
			"--output" => [
				"$project_root/palette.json",
				"help" => "OUTPUT_FILE",
				"optional" => true,
				"sanitizer" => "file-path",
			],
			"--verbose" => [false, "optional" => true],
		];
	}

	private const GENERATE_PALETTE_CLI_CONFIGS = [
		["argument" => "output", "config" => "palette-path"],
	];

	private static function generatePalette(array &$argv): int
	{
		if($exit = static::loadCliConfigs(argv: $argv, configs: self::GENERATE_PALETTE_CLI_CONFIGS)) return $exit;

		$arguments = static::getCommandArguments(argv: $argv, arguments: static::getGeneratePaletteArguments());

		if(isset($arguments["help"])) return static::help(command: self::GENERATE_PALETTE_COMMAND);

		GeneratePaletteService::checkDependencies(verbose: $arguments["verbose"]);

		$core_palette = GeneratePaletteService::generateCorePalette(arguments: $arguments);
		$tonal_palettes = GeneratePaletteService::generateTonalPalettes(core_palette: $core_palette, verbose: $arguments["verbose"]);
		GeneratePaletteService::writePalette(arguments: $arguments, tonal_palettes: $tonal_palettes);

		static::writeCliConfigs(arguments: $arguments, configs: self::GENERATE_PALETTE_CLI_CONFIGS);

		static::successMessage(message: "Palette generated in", output: $arguments["output"]);

		return 0;
	}
}
