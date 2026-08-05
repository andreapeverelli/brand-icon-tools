<?php

namespace AndreaPeverelli\PhxCli;

trait GenerateTypescale
{
	private static function generateTypescale(array &$argv): int
	{
		if(isset($argv[2]) && $argv[2] === "--help") {
			echo <<<OUTPUT
			PHX-CLI Generate Typescale
			Generate Material You based typescale.

			Command structure:
				phx generate:typescale --input font.ttf [--weight-axis axis_name] [--input-support support_font.ttf] [--support-weight-axis axis_name] [--output custom_path] [--verbose]
				phx generate:typescale --help\n
			OUTPUT;

			return 0;
		}

		$arguments_kv = static::getKeyValue(arguments: array_slice($argv, 2));

		$input = $arguments_kv["--input"] ?? "";
		if($input === "") {
			do {
				$input = trim(readline("Main Font: "));
			} while($input === "");

			$weight_axis = trim(readline("Weight Axis (default 'wght'): "));
			$weight_axis = $weight_axis === "" ? "wght" : $weight_axis;

			$input_support = trim(readline("Support Font (optional): "));
			$input_support = $input_support === "" ? null : $input_support;

			if($input_support) {
				$support_weight_axis = trim(readline("Support Font Weight Axis (default 'wght'): "));
				$support_weight_axis = $support_weight_axis === "" ? "wght" : $support_weight_axis;
			}
		} else {
			$weight_axis = $arguments_kv["--weight-axis"] ?? "wght";
			$input_support = $arguments_kv["--input-support"] ?? null;
			$support_weight_axis = $arguments_kv["--support-weight-axis"] ?? "wght";
		}
		$output = $arguments_kv["--output"] ?? "typescale.json";

		$verbose = $arguments_kv["--verbose"] ?? false;

		GenerateTypescaleInternals::checkDependencies(verbose: $verbose);

		$font_metrics = GenerateTypescaleInternals::getFontMetrics(
			description: "Generating main font metrics:",
			input: $input,
			weight_axis: $weight_axis,
			verbose: $verbose,
		);
		if($input_support) {
			$support_font_metrics = GenerateTypescaleInternals::getFontMetrics(
				description: "Generating support font metrics:",
				input: $input_support,
				weight_axis: $support_weight_axis,
				verbose: $verbose,
			);
		}

		echo BOLD . "Generating typescales:\n" . RESET;

		$typescale = GenerateTypescaleInternals::generateHeadingTypescale(font_metrics: $font_metrics);

		$font_metrics = $input_support ? $support_font_metrics : $font_metrics;
		$typescale = array_merge($typescale, GenerateTypescaleInternals::generateSupportTypescale(
			font_metrics: $font_metrics,
		));
 
		GenerateTypescaleInternals::writeTypescale(typescale: $typescale, output: $output);
		GenerateTypescaleInternals::importFonts(input: $input, input_support: $input_support, verbose: $verbose);

		echo BOLD . GREEN . "\n######################\n" . RESET;
		echo BOLD . "Typescale generated in " . GREEN . $output . RESET . BOLD . ".\n" . RESET;
		echo BOLD . GREEN . "######################\n" . RESET;

		return 0;
	}
}
