<?php

namespace AndreaPeverelli\PhxCli;

trait GeneratePalette
{
	private static function generatePalette(array &$argv): int
	{
		if(isset($argv[2]) && $argv[2] === "--help") {
			echo <<<OUTPUT
			PHX-CLI Generate Palette
			Generates sRGB/Display P3/Rec. 2020 tonal palettes using HCT Material You core palette based on an Hex source color.

			Command structure:
				phx generate:palette --source-color "#hex_color" [--output output_file] [--verbose]
				phx generate:palette --help\n
			OUTPUT;

			return 0;
		}

		$arguments_kv = static::getKeyValue(arguments: array_slice($argv, 2));

		$source_color = $arguments_kv["--source-color"] ?? "";
		if($source_color === "") {
			do {
				$source_color = trim(readline("Source color (#hhhhhh): "));
			} while($source_color === "");
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
