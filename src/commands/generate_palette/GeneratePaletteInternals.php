<?php

namespace AndreaPeverelli\PhxTools;

final class GeneratePaletteInternals
{
	use Utils;

	final public static function checkDependencies(bool &$verbose): void
	{
		static::runCommand(
			command: "phx-core-palette --version",
			verbose: $verbose,
			error_message: <<<OUTPUT
			PHX-TOOLS Generate Palette requires 'phx-core-palette' for HCT color space manipulation.

			For install instructions follow https://github.com/andreapeverelli/phx-core-palette/blob/main/README.md
			OUTPUT,
		);

		static::runCommand(
			command: "phx-tonal-palette --version",
			verbose: $verbose,
			error_message: <<<OUTPUT
			PHX-TOOLS Generate Palette requires 'phx-tonal-palette' for HCT color space manipulation.

			For install instructions follow https://github.com/andreapeverelli/phx-tonal-palette/blob/main/README.md
			OUTPUT,
		);
	}

	final public static function generateCorePalette(string &$source_color, bool &$verbose): array
	{
		return json_decode(static::runCommand(
			description: ["Generating core palette:", "bold" => true, "new_line" => true],
			get_output: true,
			command: <<<BASH
			phx-core-palette "$source_color"
			BASH,
			verbose: $verbose,
		), true);
	}

	final public static function generateTonalPalettes(array $core_palette, bool &$verbose): array
	{
		echo BOLD . "Generating sRGB/Display P3/Rec. 2020 tonal palettes:\n" . RESET;

		$tonal_palettes["primary"] = static::associateTonalPalette(static::generateTonalPalette(
			name: "primary",
			hue: $core_palette["a1"]["hue"],
			chroma: $core_palette["a1"]["chroma"],
			verbose: $verbose,
		));
		$tonal_palettes["secondary"] = static::associateTonalPalette(static::generateTonalPalette(
			name: "secondary",
			hue: $core_palette["a2"]["hue"],
			chroma: $core_palette["a2"]["chroma"],
			verbose: $verbose,
		));
		$tonal_palettes["tertiary"] = static::associateTonalPalette(static::generateTonalPalette(
			name: "tertiary",
			hue: $core_palette["a3"]["hue"],
			chroma: $core_palette["a3"]["chroma"],
			verbose: $verbose,
		));
		$tonal_palettes["neutral"] = static::associateTonalPalette(static::generateTonalPalette(
			name: "neutral",
			hue: $core_palette["n1"]["hue"],
			chroma: $core_palette["n1"]["chroma"],
			verbose: $verbose,
		));
		$tonal_palettes["neutral_variant"] = static::associateTonalPalette(static::generateTonalPalette(
			name: "neutral_variant",
			hue: $core_palette["n2"]["hue"],
			chroma: $core_palette["n2"]["chroma"],
			verbose: $verbose,
		));
		$tonal_palettes["error"] = static::associateTonalPalette(static::generateTonalPalette(
			name: "error",
			hue: $core_palette["error"]["hue"],
			chroma: $core_palette["error"]["chroma"],
			verbose: $verbose,
		));

		// rainbow colors
		// #ff0000
		$tonal_palettes["red"] = static::associateTonalPalette(static::generateTonalPalette(
			name: "red",
			hue: 27.41,
			chroma: 113.36,
			verbose: $verbose,
		));
		// #ff7f00
		$tonal_palettes["orange"] = static::associateTonalPalette(static::generateTonalPalette(
			name: "orange",
			hue: 52.033,
			chroma: 69.146,
			verbose: $verbose,
		));
		// #ffff00
		$tonal_palettes["yellow"] = static::associateTonalPalette(static::generateTonalPalette(
			name: "yellow",
			hue: 111.05,
			chroma: 75.504,
			verbose: $verbose,
		));
		// #00ff00
		$tonal_palettes["green"] = static::associateTonalPalette(static::generateTonalPalette(
			name: "green",
			hue: 142.14,
			chroma: 108.41,
			verbose: $verbose,
		));
		// #00ffff
		$tonal_palettes["cyan"] = static::associateTonalPalette(static::generateTonalPalette(
			name: "cyan",
			hue: 196.55,
			chroma: 58.964,
			verbose: $verbose,
		));
		// #0000ff
		$tonal_palettes["blue"] = static::associateTonalPalette(static::generateTonalPalette(
			name: "blue",
			hue: 282.76,
			chroma: 87.228,
			verbose: $verbose,
		));
		// #4b0082
		$tonal_palettes["indigo"] = static::associateTonalPalette(static::generateTonalPalette(
			name: "indigo",
			hue: 310.96,
			chroma: 60.765,
			verbose: $verbose,
		));
		// #8f00ff
		$tonal_palettes["violet"] = static::associateTonalPalette(static::generateTonalPalette(
			name: "violet",
			hue: 308.56,
			chroma: 91.356,
			verbose: $verbose,
		));

		// other common colors
		// #808080
		$tonal_palettes["grey"] = static::associateTonalPalette(static::generateTonalPalette(
			name: "grey",
			hue: 209.54,
			chroma: 1.8977,
			verbose: $verbose,
		));
		// #a52a2a
		$tonal_palettes["brown"] = static::associateTonalPalette(static::generateTonalPalette(
			name: "brown",
			hue: 22.698,
			chroma: 66.983,
			verbose: $verbose,
		));
		// #ffc0cb
		$tonal_palettes["pink"] = static::associateTonalPalette(static::generateTonalPalette(
			name: "pink",
			hue: 6.3747,
			chroma: 25.466,
			verbose: $verbose,
		));
		// #ff00ff
		$tonal_palettes["magenta"] = static::associateTonalPalette(static::generateTonalPalette(
			name: "magenta",
			hue: 334.63,
			chroma: 107.39,
			verbose: $verbose,
		));
		// #b300ff
		$tonal_palettes["fuchsia"] = static::associateTonalPalette(static::generateTonalPalette(
			name: "fuchsia",
			hue: 317.98,
			chroma: 95.403,
			verbose: $verbose,
		));
		// #800080
		$tonal_palettes["purple"] = static::associateTonalPalette(static::generateTonalPalette(
			name: "purple",
			hue: 334.57,
			chroma: 70.266,
			verbose: $verbose,
		));
		// #0080ff
		$tonal_palettes["lightblue"] = static::associateTonalPalette(static::generateTonalPalette(
			name: "lightblue",
			hue: 263.87,
			chroma: 69.366,
			verbose: $verbose,
		));

		echo "\n";

		return $tonal_palettes;
	}

	private static function generateTonalPalette(
		string $name,
		float $hue,
		float $chroma,
		bool &$verbose,
	): array
	{
		return json_decode(static::runCommand(
			description: " | $name:",
			get_output: true,
			command: <<<BASH
			phx-tonal-palette {$hue} {$chroma}
			BASH,
			verbose: $verbose,
		), true);
	}

	private static function associateTonalPalette(array $tonal_palette): array
	{
		$palette = [];
		$tones = [0, 10, 20, 30, 40, 50, 60, 70, 80, 90, 95, 98, 99, 100];
		foreach($tonal_palette["srgb"] as $i => $tone) {
			$palette[$tones[$i]] = [
				"srgb" => $tone,
				"display_p3" => [
					"r" => $tonal_palette["display_p3"][$i]["coords"][0],
					"g" => $tonal_palette["display_p3"][$i]["coords"][1],
					"b" => $tonal_palette["display_p3"][$i]["coords"][2],
				],
				"rec_2020" => [
					"r" => $tonal_palette["rec_2020"][$i]["coords"][0],
					"g" => $tonal_palette["rec_2020"][$i]["coords"][1],
					"b" => $tonal_palette["rec_2020"][$i]["coords"][2],
				],
			];
		}

		return $palette;
	}

	final public static function writePalette(array &$tonal_palettes, string &$output, bool &$verbose): void
	{
		if(file_exists($output)) {
			unlink($output);
		}

		file_put_contents($output, json_encode($tonal_palettes));

		echo BOLD . GREEN . "####################\n" . RESET;
		echo BOLD . "Palette generated in " . GREEN . $output . RESET . BOLD . ".\n" . RESET;
		echo BOLD . GREEN . "####################\n" . RESET;
	}
}
