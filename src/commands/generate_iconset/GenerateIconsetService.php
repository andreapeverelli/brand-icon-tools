<?php

/*
 *
 * GenerateIconsetService.php
 * -----------------------------------
 * Copyright (c) 2026 Andrea Peverelli
 * License: GPL-3.0
 * -----------------------------------
 *
 * PHX-CLI Generate Iconset command functionalities.
 *
 */

namespace AndreaPeverelli\PhxCli;

final class GenerateIconsetService
{
	use Utils;
	use ImageProcessing;

	private function __construct() {}

	final public static function checkDependencies(bool &$verbose): void
	{
		static::runCommand(
			command: "magick -version",
			verbose: $verbose,
			error_message: <<<OUTPUT
			PHX-CLI Generate Iconset requires 'imagemagick' for raster manipulation.
			
			Please run 'sudo pacman -S imagemagick' and retry
			OUTPUT,
		);

		static::runCommand(
			command: "rsvg-convert --version",
			verbose: $verbose,
			error_message: <<<OUTPUT
			PHX-CLI Generate Iconset requires 'librsvg' for SVG manipulation.
			
			Please run 'sudo pacman -S librsvg' and retry
			OUTPUT,
		);

		static::runCommand(
			command: "inkscape --version",
			verbose: $verbose,
			error_message: <<<OUTPUT
			PHX-CLI Generate Iconset requires 'inkscape' for generating monochrome SVG.
			
			Please run 'sudo pacman -S inkscape' and retry
			OUTPUT,
		);

		static::runCommand(
			command: "svgo --version",
			verbose: $verbose,
			error_message: <<<OUTPUT
			PHX-CLI Generate Iconset requires 'svgo' for SVG optimization.
			
			Please run 'sudo pacman -S svgo' and retry
			OUTPUT,
		);
	}

	final public static function normalizeInputSvg(array &$arguments): string
	{
		$tmp = sys_get_temp_dir();
		$file_id = uniqid();
		$file_path = "$tmp/$file_id.svg";

		static::normalizeSvg(
			description: [
				"Normalizing plain SVG:",
				"bold" => true,
				"new_line" => true,
			],
			input: $arguments["input"],
			output: $file_path,
			verbose: $arguments["verbose"],
		);

		return $file_path;
	}

	final public static function generateMonochromeSvg(array &$arguments, string &$normalized_svg_path): string
	{
		$tmp = sys_get_temp_dir();
		$file_id = uniqid();
		$file_path = "$tmp/$file_id.svg";

		static::svgToMonochromeSvg(
			description: [
				"Generating monochrome SVG:",
				"bold" => true,
				"new_line" => true,
			],
			input: $normalized_svg_path,
			output: $file_path,
			verbose: $arguments["verbose"],
		);

		return $file_path;
	}

	final public static function optimizeIcons(
		array &$arguments,
		string &$normalized_svg_path,
		string &$monochrome_svg_path,
	): array
	{
		echo BOLD . "Optimizing SVGs:\n" . RESET;

		$tmp = sys_get_temp_dir();

		$optimized_svg_id = uniqid();
		$optimized_monochrome_svg_id = uniqid();

		$optimized_svg_path = "$tmp/$optimized_svg_id.svg";
		$optimized_monochrome_svg_path = "$tmp/$optimized_monochrome_svg_id.svg";

		static::optimizeSvgs(
			descriptions: [
				" | standard SVG:",
				[" | monochrome SVG:", "new_line" => true],
			],
			inputs: [
				$normalized_svg_path,
				$monochrome_svg_path,
			],
			outputs: [
				$optimized_svg_path,
				$optimized_monochrome_svg_path,
			],
			verbose: $arguments["verbose"],
		);

		return [
			"optimized_svg_path" => $optimized_svg_path,
			"optimized_monochrome_svg_path" => $optimized_monochrome_svg_path,
		];
	}

	final public static function generateFavicons(array &$arguments, string &$optimized_svg_path): void
	{
		echo BOLD . "Generating favicons:\n" . RESET;

		$output = $arguments["output"];

		$favicon_sizes = [16, 32, 48, 64, 128, 256];

		static::svgToCustomPngs(
			descriptions: [
				" | favicon-16x16.png",
				" | favicon-32x32.png",
				" | favicon-48x48.png",
				" | favicon-64x64.png",
				" | favicon-128x128.png",
				" | favicon-256x256.png",
			],
			input: $optimized_svg_path,
			outputs: [
				"$output/favicon-16x16.png",
				"$output/favicon-32x32.png",
				"$output/favicon-48x48.png",
				"$output/favicon-64x64.png",
				"$output/favicon-128x128.png",
				"$output/favicon-256x256.png",
			],
			sizes: [16, 32, 48, 64, 128, 256],
			scale: 90,
			verbose: $arguments["verbose"],
		);

		static::pngsToIco(
			description: " | favicon.ico:",
			inputs: [
				"$output/favicon-16x16.png",
				"$output/favicon-32x32.png",
				"$output/favicon-48x48.png",
				"$output/favicon-64x64.png",
				"$output/favicon-128x128.png",
				"$output/favicon-256x256.png",
			],
			output: "$output/favicon.ico",
			verbose: $arguments["verbose"],
		);

		echo " | favicon.svg: ";
		copy($optimized_svg_path, "$output/favicon.svg");
		echo BOLD . GREEN . "SUCCESS\n\n" . RESET;
	}

	final public static function generateAppleIcons(
		array &$arguments,
		string &$optimized_svg_path,
		string &$optimized_monochrome_svg_path,
	): void
	{
		echo BOLD . "Generating Apple Icons:\n" . RESET;

		$output = $arguments["output"];

		static::svgToCustomPngs(
			descriptions: [
				" | apple-touch-icon-152x152.png",
				" | apple-touch-icon-167x167.png",
				" | apple-touch-icon-180x180.png",
			],
			input: $optimized_svg_path,
			outputs: [
				"$output/apple-touch-icon-152x152.png",
				"$output/apple-touch-icon-167x167.png",
				"$output/apple-touch-icon-180x180.png",
			],
			sizes: [152, 167, 180],
			scale: 90,
			verbose: $arguments["verbose"],
		);

		echo " | apple-touch-icon.png: ";
		copy("$output/apple-touch-icon-180x180.png", "$output/apple-touch-icon.png");
		echo BOLD . GREEN . "SUCCESS\n" . RESET;

		echo " | safari-pinned-tab.svg: ";
		copy($optimized_monochrome_svg_path, "$output/safari-pinned-tab.svg");
		echo BOLD . GREEN . "SUCCESS\n\n" . RESET;
	}

	final public static function generateAndroidIcons(
		array &$arguments,
		string &$optimized_svg_path,
		string &$optimized_monochrome_svg_path,
	): void
	{
		echo BOLD . "Generating Android Icons: \n" . RESET;

		$output = $arguments["output"];
		$verbose = $arguments["verbose"];

		static::svgToCustomPngs(
			descriptions: [
				" | android-chrome-192x192.png",
				" | android-chrome-512x512.png",
			],
			input: $optimized_svg_path,
			outputs: [
				"$output/android-chrome-192x192.png",
				"$output/android-chrome-512x512.png",
			],
			sizes: [192, 512],
			scale: 90,
			verbose: $verbose,
		);
		static::svgToCustomPngs(
			descriptions: [
				" | maskable-icon-192x192.png",
				" | maskable-icon-512x512.png",
			],
			input: $optimized_svg_path,
			outputs: [
				"$output/maskable-icon-192x192.png",
				"$output/maskable-icon-512x512.png",
			],
			sizes: [192, 512],
			scale: 65,
			verbose: $verbose,
		);
		static::svgToCustomPng(
			description: [" | monochrome-icon-512x512.png", "new_line" => true],
			input: $optimized_monochrome_svg_path,
			output: "$output/monochrome-icon-512x512.png",
			size: 512,
			scale: 90,
			verbose: $verbose,
		);
	}

	final public static function generateMicrosoftIcons(array &$arguments, string &$optimized_svg_path): void
	{
		echo BOLD . "Generating Microsoft Icons:\n" . RESET;

		$output = $arguments["output"];

		static::svgToCustomPngs(
			descriptions: [
				" | mstile-70x70.png",
				" | mstile-150x150.png",
				" | mstile-310x310.png",
			],
			input: $optimized_svg_path,
			outputs: [
				"$output/mstile-70x70.png",
				"$output/mstile-150x150.png",
				"$output/mstile-310x310.png",
			],
			sizes: [70, 150, 310],
			scale: 90,
			verbose: $arguments["verbose"],
		);
		static::svgToCustomPng(
			description: [" | mstile-310x150.png", "new_line" => true],
			input: $optimized_svg_path,
			output: "$output/mstile-310x150.png",
			size: [310, 150],
			scale: 65,
			verbose: $arguments["verbose"],
		);
	}

	final public static function generateOpenGraphIcon(array &$arguments, string &$optimized_svg_path): void
	{
		$output = $arguments["output"];

		static::svgToCustomPng(
			description: [
				"Generating OpenGraph:",
				"bold" => true,
				"new_line" => true,
			],
			input: $optimized_svg_path,
			output: "$output/og-image.png",
			size: [1200, 630],
			scale: 65,
			verbose: $arguments["verbose"],
		);
	}

	final public static function generateTwitterIcon(array &$arguments, string &$optimized_svg_path): void
	{
		$output = $arguments["output"];

		static::svgToCustomPng(
			description: [
				"Generating Twitter Image:",
				"bold" => true,
				"new_line" => true,
			],
			input: $optimized_svg_path,
			output: "$output/twitter-image.png",
			size: [1200, 600],
			scale: 65,
			verbose: $arguments["verbose"],
		);
	}
}
