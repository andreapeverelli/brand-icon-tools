<?php

namespace AndreaPeverelli\PhxTools;

trait GeneratePalette
{
	private static function generatePalette(array $argv): int
	{
		if(!isset($argv[2])) {
			static::badArguments(tool: "generate:palette");
		}

		if(isset($argv[2]) && $argv[2] === "--help") {
			echo <<<OUTPUT
			PHX-TOOLS Generate Palette
			Generates sRGB/Display P3/Rec. 2020 tonal palettes using HCT Material You core palette based on an Hex source color.

			Command structure:
				phx-tools generate:palette --source-color "#hex_color" [--output output_file] [--verbose]
				phx-tools generate:palette --help\n
			OUTPUT;

			return 0;
		}

		$arguments_kv = static::getKeyValue(arguments: array_slice($argv, 2));

		$source_color = $arguments_kv["--source-color"] ?? null;
		if(!$source_color) {
			static::badArguments(tool: "generate:palette");
		}

		$output = $arguments_kv["--output"] ?? "palette.json";
		$verbose = $arguments_kv["--verbose"] ?? false;

		GeneratePaletteInternals::checkDependencies(verbose: $verbose);
		$core_palette = GeneratePaletteInternals::generateCorePalette(source_color: $source_color, verbose: $verbose);
		$tonal_palettes = GeneratePaletteInternals::generateTonalPalettes(core_palette: $core_palette, verbose: $verbose);
		GeneratePaletteInternals::writePalette(tonal_palettes: $tonal_palettes, output: $output, verbose: $verbose);

		return 0;
	}

}
