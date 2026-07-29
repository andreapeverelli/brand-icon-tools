<?php

namespace AndreaPeverelli\PhxTools;

use AndreaPeverelli\PhxTools\ImageProcessing;

trait GenerateIconset
{
	use ImageProcessing;

	private static function generateIconset(array $argv): int
	{
		if(!isset($argv[2])) {
			return static::badArguments(tool: "generate:iconset");
		}

		if($argv[2] === "--help") {
			echo <<<OUTPUT
			PHX-TOOLS Generate Iconset
			Generates favicon/Apple/Android/Microsoft/OpenGraph/Twitter icons from an SVG.

			Command structure:
				phx-tools generate:iconset --input icon.svg [--out custom_path] [--verbose]

			Notes:
				The initial SVG icon should be borderless; all icons will be generated with a 90% scale factor and the PWA maskable icon variant with a 65% scale factor.\n
			OUTPUT;

			return 0;
		}

		$arguments_kv = static::getKeyValue(arguments: array_slice($argv, 2));

		$out = $arguments_kv["--out"] ?? "out/";
		$input = $arguments_kv["--input"] ?? null;

		if(!$input) {
			return static::badArguments(tool: "generate:iconset");
		}

		if(str_ends_with($out, "/")) {
			$out = substr($out, 0, strlen($out) - 1);
		}

		if(!is_dir($out)) {
			mkdir($out);
		}

		$verbose = in_array("--verbose", $arguments_kv) ? true : false;

		$tmp = sys_get_temp_dir();

		static::checkDependencies(verbose: $verbose);

		static::normalizeInputSvg(tmp: $tmp, input: $input, verbose: $verbose);
		static::generateMonochromeSvg(tmp: $tmp, verbose: $verbose);
		static::optimizeIcons(tmp: $tmp, verbose: $verbose);

		static::generateFavicons(out: $out, tmp: $tmp, verbose: $verbose);
		static::generateAppleIcons(out: $out, tmp: $tmp, verbose: $verbose);
		static::generateAndroidIcons(out: $out, tmp: $tmp, verbose: $verbose);
		static::generateMicrosoftIcons(out: $out, tmp: $tmp, verbose: $verbose);
		static::generateOpenGraphIcon(out: $out, tmp: $tmp, verbose: $verbose);
		static::generateTwitterIcon(out: $out, tmp: $tmp, verbose: $verbose);

		echo BOLD . GREEN . "####################\n" . RESET ;
		echo BOLD . "Iconset generated in " . RESET . BOLD . GREEN .  "$out/" . RESET . BOLD . ".\n" . RESET;
		echo BOLD . GREEN . "####################\n" . RESET;

		return 0;
	}

	private static function checkDependencies(bool &$verbose): void
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

	private static function normalizeInputSvg(string &$tmp, string &$input, bool &$verbose): void
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

	private static function generateMonochromeSvg(string &$tmp, bool &$verbose): void
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

	private static function optimizeIcons(string &$tmp, bool &$verbose): void
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


	private static function generateFavicons(string &$out, string &$tmp, bool &$verbose): void
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
				"$out/favicon-16x16.png",
				"$out/favicon-32x32.png",
				"$out/favicon-48x48.png",
				"$out/favicon-64x64.png",
				"$out/favicon-128x128.png",
				"$out/favicon-256x256.png",
			],
			sizes: [16, 32, 48, 64, 128, 256],
			scale: 90,
			verbose: $verbose,
		);

		static::pngsToIco(
			description: " | favicon.ico:",
			inputs: [
				"$out/favicon-16x16.png",
				"$out/favicon-32x32.png",
				"$out/favicon-48x48.png",
				"$out/favicon-64x64.png",
				"$out/favicon-128x128.png",
				"$out/favicon-256x256.png",
			],
			output: "$out/favicon.ico",
			verbose: $verbose,
		);

		echo " | favicon.svg: ";
		copy("$tmp/optimized-icon.svg", "$out/favicon.svg");
		echo BOLD . GREEN . "SUCCESS\n\n" . RESET;
	}

	private static function generateAppleIcons(string &$out, string &$tmp, bool &$verbose): void
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
				"$out/apple-touch-icon-152x152.png",
				"$out/apple-touch-icon-167x167.png",
				"$out/apple-touch-icon-180x180.png",
			],
			sizes: [152, 167, 180],
			scale: 90,
			verbose: $verbose,
		);

		echo " | apple-touch-icon.png: ";
		copy("$out/apple-touch-icon-180x180.png", "$out/apple-touch-icon.png");
		echo BOLD . GREEN . "SUCCESS\n" . RESET;

		echo " | safari-pinned-tab.svg: ";
		copy("$tmp/optimized-monochrome-icon.svg", "$out/safari-pinned-tab.svg");
		echo BOLD . GREEN . "SUCCESS\n\n" . RESET;
	}

	private static function generateAndroidIcons(string &$out, string &$tmp, bool &$verbose): void
	{
		echo BOLD . "Generating Android Icons: \n" . RESET;
		static::svgToCustomPngs(
			descriptions: [
				" | android-chrome-192x192.png",
				" | android-chrome-512x512.png",
			],
			input: "$tmp/optimized-icon.svg",
			outputs: [
				"$out/android-chrome-192x192.png",
				"$out/android-chrome-512x512.png",
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
				"$out/maskable-icon-192x192.png",
				"$out/maskable-icon-512x512.png",
			],
			sizes: [192, 512],
			scale: 65,
			verbose: $verbose,
		);
		static::svgToCustomPng(
			description: [" | monochrome-icon-512x512.png", "new_line" => true],
			input: "$tmp/optimized-monochrome-icon.svg",
			output: "$out/monochrome-icon-512x512.png",
			size: 512,
			scale: 90,
			verbose: $verbose,
		);
	}

	private static function generateMicrosoftIcons(string &$out, string &$tmp, bool &$verbose): void
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
				"$out/mstile-70x70.png",
				"$out/mstile-150x150.png",
				"$out/mstile-310x310.png",
			],
			sizes: [70, 150, 310],
			scale: 90,
			verbose: $verbose,
		);
		static::svgToCustomPng(
			description: [" | mstile-310x150.png", "new_line" => true],
			input: "$tmp/optimized-icon.svg",
			output: "$out/mstile-310x150.png",
			size: [310, 150],
			scale: 65,
			verbose: $verbose,
		);
	}

	private static function generateOpenGraphIcon(string &$out, string &$tmp, bool &$verbose): void
	{
		static::svgToCustomPng(
			description: [
				"Generating OpenGraph:",
				"bold" => true,
				"new_line" => true,
			],
			input: "$tmp/optimized-icon.svg",
			output: "$out/og-image.png",
			size: [1200, 630],
			scale: 65,
			verbose: $verbose,
		);
	}

	private static function generateTwitterIcon(string &$out, string &$tmp, bool &$verbose): void
	{
		static::svgToCustomPng(
			description: [
				"Generating Twitter Image:",
				"bold" => true,
				"new_line" => true,
			],
			input: "$tmp/optimized-icon.svg",
			output: "$out/twitter-image.png",
			size: [1200, 600],
			scale: 65,
			verbose: $verbose,
		);
	}
}
