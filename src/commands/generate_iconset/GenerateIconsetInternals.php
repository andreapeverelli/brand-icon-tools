<?php

namespace AndreaPeverelli\PhxCli;

final class GenerateIconsetInternals
{
	use Utils;
	use ImageProcessing;

	final public static function checkDependencies(bool &$verbose): void
	{
		static::runCommand(
			command: "magick -version",
			verbose: $verbose,
			error_message: <<<OUTPUT
			PHX-TOOLS Generate Iconset requires 'imagemagick' for raster manipulation.
			
			Please run 'sudo pacman -S imagemagick' and retry
			OUTPUT,
		);

		static::runCommand(
			command: "rsvg-convert --version",
			verbose: $verbose,
			error_message: <<<OUTPUT
			PHX-TOOLS Generate Iconset requires 'librsvg' for SVG manipulation.
			
			Please run 'sudo pacman -S librsvg' and retry
			OUTPUT,
		);

		static::runCommand(
			command: "inkscape --version",
			verbose: $verbose,
			error_message: <<<OUTPUT
			PHX-TOOLS Generate Iconset requires 'inkscape' for generating monochrome SVG.
			
			Please run 'sudo pacman -S inkscape' and retry
			OUTPUT,
		);

		static::runCommand(
			command: "svgo --version",
			verbose: $verbose,
			error_message: <<<OUTPUT
			PHX-TOOLS Generate Iconset requires 'svgo' for SVG optimization.
			
			Please run 'sudo pacman -S svgo' and retry
			OUTPUT,
		);
	}

	final public static function normalizeInputSvg(string &$tmp, string &$input, bool &$verbose): void
	{
		static::normalizeSvg(
			description: [
				"Normalizing plain SVG:",
				"bold" => true,
				"new_line" => true,
			],
			input: $input,
			output: "$tmp/normalized-icon.svg",
			verbose: $verbose,
		);
	}

	final public static function generateMonochromeSvg(string &$tmp, bool &$verbose): void
	{
		static::svgToMonochromeSvg(
			description: [
				"Generating monochrome SVG:",
				"bold" => true,
				"new_line" => true,
			],
			input: "$tmp/normalized-icon.svg",
			output: "$tmp/monochrome-icon.svg",
			verbose: $verbose,
		);
	}

	final public static function optimizeIcons(string &$tmp, bool &$verbose): void
	{
		echo BOLD . "Optimizing SVGs:\n" . RESET;

		static::optimizeSvgs(
			descriptions: [
				" | standard SVG:",
				[" | monochrome SVG:", "new_line" => true],
			],
			inputs: [
				"$tmp/normalized-icon.svg",
				"$tmp/monochrome-icon.svg",
			],
			outputs: [
				"$tmp/optimized-icon.svg",
				"$tmp/optimized-monochrome-icon.svg",
			],
			verbose: $verbose,
		);
	}

	final public static function generateFavicons(string &$output, string &$tmp, bool &$verbose): void
	{
		echo BOLD . "Generating favicons:\n" . RESET;
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
			input: "$tmp/optimized-icon.svg",
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
			verbose: $verbose,
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
			verbose: $verbose,
		);

		echo " | favicon.svg: ";
		copy("$tmp/optimized-icon.svg", "$output/favicon.svg");
		echo BOLD . GREEN . "SUCCESS\n\n" . RESET;
	}

	final public static function generateAppleIcons(string &$output, string &$tmp, bool &$verbose): void
	{
		echo BOLD . "Generating Apple Icons:\n" . RESET;
		static::svgToCustomPngs(
			descriptions: [
				" | apple-touch-icon-152x152.png",
				" | apple-touch-icon-167x167.png",
				" | apple-touch-icon-180x180.png",
			],
			input: "$tmp/optimized-icon.svg",
			outputs: [
				"$output/apple-touch-icon-152x152.png",
				"$output/apple-touch-icon-167x167.png",
				"$output/apple-touch-icon-180x180.png",
			],
			sizes: [152, 167, 180],
			scale: 90,
			verbose: $verbose,
		);

		echo " | apple-touch-icon.png: ";
		copy("$output/apple-touch-icon-180x180.png", "$output/apple-touch-icon.png");
		echo BOLD . GREEN . "SUCCESS\n" . RESET;

		echo " | safari-pinned-tab.svg: ";
		copy("$tmp/optimized-monochrome-icon.svg", "$output/safari-pinned-tab.svg");
		echo BOLD . GREEN . "SUCCESS\n\n" . RESET;
	}

	final public static function generateAndroidIcons(string &$output, string &$tmp, bool &$verbose): void
	{
		echo BOLD . "Generating Android Icons: \n" . RESET;
		static::svgToCustomPngs(
			descriptions: [
				" | android-chrome-192x192.png",
				" | android-chrome-512x512.png",
			],
			input: "$tmp/optimized-icon.svg",
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
			input: "$tmp/optimized-icon.svg",
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
			input: "$tmp/optimized-monochrome-icon.svg",
			output: "$output/monochrome-icon-512x512.png",
			size: 512,
			scale: 90,
			verbose: $verbose,
		);
	}

	final public static function generateMicrosoftIcons(string &$output, string &$tmp, bool &$verbose): void
	{
		echo BOLD . "Generating Microsoft Icons:\n" . RESET;
		static::svgToCustomPngs(
			descriptions: [
				" | mstile-70x70.png",
				" | mstile-150x150.png",
				" | mstile-310x310.png",
			],
			input: "$tmp/optimized-icon.svg",
			outputs: [
				"$output/mstile-70x70.png",
				"$output/mstile-150x150.png",
				"$output/mstile-310x310.png",
			],
			sizes: [70, 150, 310],
			scale: 90,
			verbose: $verbose,
		);
		static::svgToCustomPng(
			description: [" | mstile-310x150.png", "new_line" => true],
			input: "$tmp/optimized-icon.svg",
			output: "$output/mstile-310x150.png",
			size: [310, 150],
			scale: 65,
			verbose: $verbose,
		);
	}

	final public static function generateOpenGraphIcon(string &$output, string &$tmp, bool &$verbose): void
	{
		static::svgToCustomPng(
			description: [
				"Generating OpenGraph:",
				"bold" => true,
				"new_line" => true,
			],
			input: "$tmp/optimized-icon.svg",
			output: "$output/og-image.png",
			size: [1200, 630],
			scale: 65,
			verbose: $verbose,
		);
	}

	final public static function generateTwitterIcon(string &$output, string &$tmp, bool &$verbose): void
	{
		static::svgToCustomPng(
			description: [
				"Generating Twitter Image:",
				"bold" => true,
				"new_line" => true,
			],
			input: "$tmp/optimized-icon.svg",
			output: "$output/twitter-image.png",
			size: [1200, 600],
			scale: 65,
			verbose: $verbose,
		);
	}
}
