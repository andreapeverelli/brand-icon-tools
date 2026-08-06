<?php

/*
 *
 * GenerateIconset.php
 * -----------------------------------
 * Copyright (c) 2026 Andrea Peverelli
 * License: GPL-3.0
 * -----------------------------------
 *
 * PHX-CLI Generate Iconset command handler.
 *
 */

namespace AndreaPeverelli\PhxCli;

trait GenerateIconset
{
	use Utils;
	use Help;
	use ImageProcessing;

	private const GENERATE_ICONSET_COMMAND = "generate:iconset";
	private const GENERATE_ICONSET_DESCRIPTION =
		"Generates favicon/Apple/Android/Microsoft/OpenGraph/Twitter icons from an SVG.";

	private static function getGenerateIconsetArguments(): array|int
	{
		if(is_int($project_root = static::getProjectRoot())) return $project_root;

		return [
			"--input" => ["help" => "SVG_FILE"],
			"--output" => [
				"$project_root/public/icons",
				"help" => "OUTPUT_DIRECTORY",
				"optional" => true,
				"sanitizer" => "path",
			],
			"--verbose" => [false, "optional" => true],
		];
	}

	private static function generateIconset(array &$argv): int
	{
		if(is_int($arguments = static::getGenerateIconsetArguments())) return $arguments;
		$arguments = static::getCommandArguments(argv: $argv, arguments: $arguments);

		if(isset($arguments["help"])) return static::help(command: self::GENERATE_ICONSET_COMMAND);

		GenerateIconsetService::checkDependencies(verbose: $arguments["verbose"]);

		$normalized_svg_path = GenerateIconsetService::normalizeInputSvg(arguments: $arguments);
		$monochrome_svg_path = GenerateIconsetService::generateMonochromeSvg(
			arguments: $arguments,
			normalized_svg_path: $normalized_svg_path,
		);
		[
			"optimized_svg_path" => $optimized_svg_path,
			"optimized_monochrome_svg_path" => $optimized_monochrome_svg_path,
		] = GenerateIconsetService::optimizeIcons(
			arguments: $arguments,
			normalized_svg_path: $normalized_svg_path,
			monochrome_svg_path: $monochrome_svg_path,
		);

		static::ensureDirectoryExists(directory: $arguments["output"], verbose: $arguments["verbose"]);

		GenerateIconsetService::generateFavicons(arguments: $arguments, optimized_svg_path: $optimized_svg_path);
		GenerateIconsetService::generateAppleIcons(
			arguments: $arguments,
			optimized_svg_path: $optimized_svg_path,
			optimized_monochrome_svg_path: $optimized_monochrome_svg_path,
		);
		GenerateIconsetService::generateAndroidIcons(
			arguments: $arguments,
			optimized_svg_path: $optimized_svg_path,
			optimized_monochrome_svg_path: $optimized_monochrome_svg_path,
		);
		GenerateIconsetService::generateMicrosoftIcons(arguments: $arguments, optimized_svg_path: $optimized_svg_path);
		GenerateIconsetService::generateOpenGraphIcon(arguments: $arguments, optimized_svg_path: $optimized_svg_path);
		GenerateIconsetService::generateTwitterIcon(arguments: $arguments, optimized_svg_path: $optimized_svg_path);

		static::successMessage(message: "Iconset generated in", output: $arguments["output"]);

		return 0;
	}

}
