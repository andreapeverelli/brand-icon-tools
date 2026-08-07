<?php

/*
 *
 * GeneratePaletteService.php
 * -----------------------------------
 * Copyright (c) 2026 Andrea Peverelli
 * License: GPL-3.0
 * -----------------------------------
 *
 * PHX-CLI Generate Palette command functionalities.
 *
 */

namespace AndreaPeverelli\PhxCli;

final class GeneratePaletteService
{
	use Utils;

	private function __construct() {}

	private const ROLES_PROPERTIES = [
			"standard" => [
				"role" => [
					"palette" => [
						"light" => [
							"default-contrast" => ,
							"high-contrast" => ,
						],
						"dark" => [
							"default-contrast" => ,
							"high-contrast" => ,
						],
					],
					"background-of" => [],
				],
				"on-role" => [
					"palette" => [
						"light" => [
							"default-contrast" => ,
							"high-contrast" => ,
						],
						"dark" => [
							"default-contrast" => ,
							"high-contrast" => ,
						],
					],
					"background-of" => [],
				],
				"role-container" => [
					"palette" => [
						"light" => [
							"default-contrast" => ,
							"high-contrast" => ,
						],
						"dark" => [
							"default-contrast" => ,
							"high-contrast" => ,
						],
					],
					"background-of" => [],
				],
				"on-role-container" => [
					"palette" => [
						"light" => [
							"default-contrast" => ,
							"high-contrast" => ,
						],
						"dark" => [
							"default-contrast" => ,
							"high-contrast" => ,
						],
					],
					"background-of" => [],
				],
				"role-fixed" => [
					"palette" => [
						"light" => [
							"default-contrast" => ,
							"high-contrast" => ,
						],
						"dark" => [
							"default-contrast" => ,
							"high-contrast" => ,
						],
					],
					"background-of" => [],
				],
				"on-role-fixed" => [
					"palette" => [
						"light" => [
							"default-contrast" => ,
							"high-contrast" => ,
						],
						"dark" => [
							"default-contrast" => ,
							"high-contrast" => ,
						],
					],
					"background-of" => [],
				],
				"role-fixed-dim" => [
					"palette" => [
						"light" => [
							"default-contrast" => ,
							"high-contrast" => ,
						],
						"dark" => [
							"default-contrast" => ,
							"high-contrast" => ,
						],
					],
					"background-of" => [],
				],
				"on-role-fixed-variant" => [
					"palette" => [
						"light" => [
							"default-contrast" => ,
							"high-contrast" => ,
						],
						"dark" => [
							"default-contrast" => ,
							"high-contrast" => ,
						],
					],
					"background-of" => [],
				],
			],
			"special" => [
				"inverse-primary" => [
					"palette" => [
						"light" => [
							"default-contrast" => ,
							"high-contrast" => ,
						],
						"dark" => [
							"default-contrast" => ,
							"high-contrast" => ,
						],
					],
					"background-of" => [],
				],
				"surface" => [
					"palette" => [
						"light" => [
							"default-contrast" => ,
							"high-contrast" => ,
						],
						"dark" => [
							"default-contrast" => ,
							"high-contrast" => ,
						],
					],
					"background-of" => [],
				],
				"on-surface" => [
					"palette" => [
						"light" => [
							"default-contrast" => ,
							"high-contrast" => ,
						],
						"dark" => [
							"default-contrast" => ,
							"high-contrast" => ,
						],
					],
					"background-of" => [],
				],
				"surface-variant" => [
					"palette" => [
						"light" => [
							"default-contrast" => ,
							"high-contrast" => ,
						],
						"dark" => [
							"default-contrast" => ,
							"high-contrast" => ,
						],
					],
					"background-of" => [],
				],
				"on-surface-variant" => [
					"palette" => [
						"light" => [
							"default-contrast" => ,
							"high-contrast" => ,
						],
						"dark" => [
							"default-contrast" => ,
							"high-contrast" => ,
						],
					],
					"background-of" => [],
				],
				"surface-container-highest" => [
					"palette" => [
						"light" => [
							"default-contrast" => ,
							"high-contrast" => ,
						],
						"dark" => [
							"default-contrast" => ,
							"high-contrast" => ,
						],
					],
					"background-of" => [],
				],
				"surface-container-high" => [
					"palette" => [
						"light" => [
							"default-contrast" => ,
							"high-contrast" => ,
						],
						"dark" => [
							"default-contrast" => ,
							"high-contrast" => ,
						],
					],
					"background-of" => [],
				],
				"surface-container" => [
					"palette" => [
						"light" => [
							"default-contrast" => ,
							"high-contrast" => ,
						],
						"dark" => [
							"default-contrast" => ,
							"high-contrast" => ,
						],
					],
					"background-of" => [],
				],
				"surface-container-low" => [
					"palette" => [
						"light" => [
							"default-contrast" => ,
							"high-contrast" => ,
						],
						"dark" => [
							"default-contrast" => ,
							"high-contrast" => ,
						],
					],
					"background-of" => [],
				],
				"surface-container-lowest" => [
					"palette" => [
						"light" => [
							"default-contrast" => ,
							"high-contrast" => ,
						],
						"dark" => [
							"default-contrast" => ,
							"high-contrast" => ,
						],
					],
					"background-of" => [],
				],
				"inverse-surface" => [
					"palette" => [
						"light" => [
							"default-contrast" => ,
							"high-contrast" => ,
						],
						"dark" => [
							"default-contrast" => ,
							"high-contrast" => ,
						],
					],
					"background-of" => [],
				],
				"inverse-on-surface" => [
					"palette" => [
						"light" => [
							"default-contrast" => ,
							"high-contrast" => ,
						],
						"dark" => [
							"default-contrast" => ,
							"high-contrast" => ,
						],
					],
					"background-of" => [],
				],
				"surface-tint" => [
					"palette" => [
						"light" => [
							"default-contrast" => ,
							"high-contrast" => ,
						],
						"dark" => [
							"default-contrast" => ,
							"high-contrast" => ,
						],
					],
					"background-of" => [],
				],
				"surface-tint-color" => [
					"palette" => [
						"light" => [
							"default-contrast" => ,
							"high-contrast" => ,
						],
						"dark" => [
							"default-contrast" => ,
							"high-contrast" => ,
						],
					],
					"background-of" => [],
				],
				"outline" => [
					"palette" => [
						"light" => [
							"default-contrast" => ,
							"high-contrast" => ,
						],
						"dark" => [
							"default-contrast" => ,
							"high-contrast" => ,
						],
					],
					"background-of" => [],
				],
				"outline-variant" => [
					"palette" => [
						"light" => [
							"default-contrast" => ,
							"high-contrast" => ,
						],
						"dark" => [
							"default-contrast" => ,
							"high-contrast" => ,
						],
					],
					"background-of" => [],
				],
				"background" => [
					"palette" => [
						"light" => [
							"default-contrast" => ,
							"high-contrast" => ,
						],
						"dark" => [
							"default-contrast" => ,
							"high-contrast" => ,
						],
					],
					"background-of" => [],
				],
				"on-background" => [
					"palette" => [
						"light" => [
							"default-contrast" => ,
							"high-contrast" => ,
						],
						"dark" => [
							"default-contrast" => ,
							"high-contrast" => ,
						],
					],
					"background-of" => [],
				],
				"surface-bright" => [
					"palette" => [
						"light" => [
							"default-contrast" => ,
							"high-contrast" => ,
						],
						"dark" => [
							"default-contrast" => ,
							"high-contrast" => ,
						],
					],
					"background-of" => [],
				],
				"surface-dim" => [
					"palette" => [
						"light" => [
							"default-contrast" => ,
							"high-contrast" => ,
						],
						"dark" => [
							"default-contrast" => ,
							"high-contrast" => ,
						],
					],
					"background-of" => [],
				],
				"scrim" => [
					"palette" => [
						"light" => [
							"default-contrast" => ,
							"high-contrast" => ,
						],
						"dark" => [
							"default-contrast" => ,
							"high-contrast" => ,
						],
					],
					"background-of" => [],
				],
				"shadow" => [
					"palette" => [
						"light" => [
							"default-contrast" => ,
							"high-contrast" => ,
						],
						"dark" => [
							"default-contrast" => ,
							"high-contrast" => ,
						],
					],
					"background-of" => [],
				],
			],
	];

	final public static function checkDependencies(bool &$verbose): void
	{
		static::runCommand(
			command: "phx-core-palette --version",
			verbose: $verbose,
			error_message: <<<OUTPUT
			PHX-CLI Generate Palette requires 'phx-core-palette' for HCT color space manipulation.

			For install instructions follow https://github.com/andreapeverelli/phx-core-palette/blob/main/README.md
			OUTPUT,
		);

		static::runCommand(
			command: "phx-tonal-palette --version",
			verbose: $verbose,
			error_message: <<<OUTPUT
			PHX-CLI Generate Palette requires 'phx-tonal-palette' for HCT color space manipulation.

			For install instructions follow https://github.com/andreapeverelli/phx-tonal-palette/blob/main/README.md
			OUTPUT,
		);
	}

	final public static function generateCorePalette(array &$arguments): array
	{
		return json_decode(static::runCommand(
			description: ["Generating core palette:", "bold" => true, "new_line" => true],
			get_output: true,
			command: <<<BASH
			phx-core-palette "{$arguments["source-color"]}"
			BASH,
			verbose: $arguments["verbose"],
		), true);
	}

	final public static function generateTonalPalettes(array $core_palette, bool &$verbose): array
	{
		echo BOLD . "Generating sRGB/Display P3/Rec. 2020 tonal palettes:\n" . RESET;

		$base_colors = [
			"primary" => [
				"hue" => $core_palette["a1"]["hue"],
				"chroma" => $core_palette["a1"]["chroma"],
			],
			"secondary" => [
				"hue" => $core_palette["a2"]["hue"],
				"chroma" => $core_palette["a2"]["chroma"],
			],
			"tertiary" => [
				"hue" => $core_palette["a3"]["hue"],
				"chroma" => $core_palette["a3"]["chroma"],
			],
			"neutral" => [
				"hue" => $core_palette["n1"]["hue"],
				"chroma" => $core_palette["n1"]["chroma"],
			],
			"neutral_variant" => [
				"hue" => $core_palette["n2"]["hue"],
				"chroma" => $core_palette["n2"]["chroma"],
			],
			"error" => [
				"hue" => $core_palette["error"]["hue"],
				"chroma" => $core_palette["error"]["chroma"],
			],

			// rainbow colors
			// #ff0000
			"red" => ["hue" => 27.41, "chroma" => 113.36],
			// #ff7f00
			"orange" => ["hue" => 52.033, "chroma" => 69.146],
			// #ffff00
			"yellow" => ["hue" => 111.05, "chroma" => 75.504],
			// #00ff00
			"green" => ["hue" => 142.14, "chroma" => 108.41],
			// #00ffff
			"cyan" => ["hue" => 196.55, "chroma" => 58.964],
			// #0000ff
			"blue" => ["hue" => 282.76, "chroma" => 87.228],
			// #4b0082
			"indigo" => ["hue" => 310.96, "chroma" => 60.765],
			// #8f00ff
			"violet" => ["hue" => 308.56, "chroma" => 91.356],

			// other common colors
			// #808080
			"grey" => ["hue" => 209.54, "chroma" => 1.8977],
			// #a52a2a
			"brown" => ["hue" => 22.698, "chroma" => 66.983],
			// #ffc0cb
			"pink" => ["hue" => 6.3747, "chroma" => 25.466],
			// #ff00ff
			"magenta" => ["hue" => 334.63, "chroma" => 107.39],
			// #b300ff
			"fuchsia" => ["hue" => 317.98, "chroma" => 95.403],
			// #800080
			"purple" => ["hue" => 334.57, "chroma" => 70.266],
			// #0080ff
			"lightblue" => ["hue" => 263.87, "chroma" => 69.366],
		];

		foreach($base_colors as $name => $value) {
			$tonal_palettes[$name] = static::indexTonalPalette(tonal_palette: static::generateTonalPalette(
				name: $name,
				hue: $value["hue"],
				chroma: $value["chroma"],
				verbose: $verbose,
			));
		}

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
			phx-tonal-palette $hue $chroma
			BASH,
			verbose: $verbose,
		), true);
	}

	private static function indexTonalPalette(array $tonal_palette): array
	{
		$tones = [0, 4, 6, 10, 12, 17, 20, 22, 24, 30, 40, 50, 60, 70, 80, 87, 90, 92, 94, 95, 96, 98, 99, 100];

		$palette = [];
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
				"hct" => [
					"h" => $tonal_palette["hct"][$i]["coords"][0],
					"c" => $tonal_palette["hct"][$i]["coords"][1],
					"t" => $tonal_palette["hct"][$i]["coords"][2],
				],
			];
		}

		return $palette;
	}

	final public static function writePalette(array &$arguments, array &$tonal_palettes): void
	{
		static::deleteFile(file_name: $arguments["output"]);
		file_put_contents($arguments["output"], json_encode($tonal_palettes, JSON_PRETTY_PRINT));
	}
}
