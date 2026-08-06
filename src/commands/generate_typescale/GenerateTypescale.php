<?php

/*
 *
 * GenerateTypescale.php
 * -----------------------------------
 * Copyright (c) 2026 Andrea Peverelli
 * License: GPL-3.0
 * -----------------------------------
 *
 * PHX-CLI Generate Typescale command handler.
 *
 */

namespace AndreaPeverelli\PhxCli;

trait GenerateTypescale
{
	use Utils;
	use Help;

	private const GENERATE_TYPESCALE_COMMAND = "generate:typescale";
	private const GENERATE_TYPESCALE_DESCRIPTION =
		"Generate Material You based typescale.";

	private static function getGenerateTypescaleArguments(): array|int
	{
		if(is_int($project_root = static::getProjectRoot())) return $project_root;

		return [
			"--input" => ["help" => "FONT_FILE"],
			"--weight-axis" => [
				"wght",
				"help" => "WEIGHT_AXIS",
				"optional" => true,
			],
			"--input-support" => ["help" => "SUPPORT_FONT_FILE", "optional" => true],
			"--support-weight-axis" => [
				"wght",
				"help" => "WEIGHT_AXIS",
				"optional" => true,
			],
			"--output" => [
				"$project_root/typescale.json",
				"help" => "OUTPUT_FILE",
				"optional" => true,
			],
			"--verbose" => [false, "optional" => true],
		];
	}

	private static function generateTypescale(array &$argv): int
	{
		if(is_int($arguments = static::getGenerateTypescaleArguments())) return $arguments;
		$arguments = static::getCommandArguments(argv: $argv, arguments: $arguments);

		if(isset($arguments["help"])) return static::help(command: self::GENERATE_TYPESCALE_COMMAND);

		GenerateTypescaleService::checkDependencies(verbose: $arguments["verbose"]);

		$font_metrics = GenerateTypescaleService::getFontMetrics(
			input: $arguments["input"],
			weight_axis: $arguments["weight-axis"],
			description: "Generating main font metrics:",
			verbose: $arguments["verbose"],
		);
		if($arguments["input-support"])
			$support_font_metrics = GenerateTypescaleService::getFontMetrics(
				input: $arguments["input-support"],
				weight_axis: $arguments["support-weight-axis"],
				description: "Generating support font metrics:",
				verbose: $arguments["verbose"],
			);

		echo BOLD . "Generating typescales:\n" . RESET;

		$typescale = GenerateTypescaleService::generateHeadingTypescale(font_metrics: $font_metrics);

		$font_metrics = $arguments["input-support"] ? $support_font_metrics : $font_metrics;
		$typescale = array_merge($typescale, GenerateTypescaleService::generateSupportTypescale(font_metrics: $font_metrics));
 
		GenerateTypescaleService::writeTypescale(typescale: $typescale, output: $arguments["output"]);
		GenerateTypescaleService::copyFonts(arguments: $arguments);

		static::successMessage(message: "Typescale generated in", output: $arguments["output"]);

		return 0;
	}
}
