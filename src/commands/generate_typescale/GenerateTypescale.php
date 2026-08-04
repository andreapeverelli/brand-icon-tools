<?php

namespace AndreaPeverelli\PhxTools;

trait GenerateTypescale
{
	private static function generateTypescale(array &$argv): int
	{
		if(!isset($argv[2])) {
			return static::badArguments(tool: "generate:typescale");
		}

		if($argv[2] === "--help") {
			echo <<<OUTPUT
			PHX-TOOLS Generate Typescale
			Generate Material You based typescale

			Command structure:
				phx-tools generate:typescale --input font.ttf [--weight-axis axis_name] [--input-support support_font.ttf] [--support-weight-axis axis_name] [--output custom_path] [--verbose]
				phx-tools generate:typescale --help\n
			OUTPUT;

			return 0;
		}

		$arguments_kv = static::getKeyValue(arguments: array_slice($argv, 2));
		
		$input = $arguments_kv["--input"] ?? null;
		if(!$input) {
			return static::badArguments(tool: "generate:typescale");
		}
		
		$weight_axis = $arguments_kv["--weight-axis"] ?? "wght";
		$input_support = $arguments_kv["--input-support"] ?? null;
		$support_weight_axis = $arguments_kv["--support-weight-axis"] ?? "wght";
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

		echo BOLD . GREEN . "\n######################\n" . RESET;
		echo BOLD . "Typescale generated in " . GREEN . $output . RESET . BOLD . ".\n" . RESET;
		echo BOLD . GREEN . "######################\n" . RESET;

		return 0;
	}
}
