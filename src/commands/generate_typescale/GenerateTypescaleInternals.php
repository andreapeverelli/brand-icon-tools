<?php

namespace AndreaPeverelli\PhxCli;

final class GenerateTypescaleInternals
{
	use Utils;

	private const REFERENCE = [
		"x_ratio" => [
			"400>16px" => 0.51,
			"regular" => 0.526,
		],
		"cap_ratio" => 0.716,
		"natural_line_ratio" => [
			"400>16px" => 0.956,
			"regular" => 1.024,
		],
		"avg_advance_ratio" => [
			">16px" => [
				"regular" => [
					400 => 0.5748269230769231,
					500 => 0.5955576923076923,
					700 => 0.6193846153846153,
				],
				"italic" => [
					400 => 0.5754807692307693,
					500 => 0.5961923076923077,
					700 => 0.6205192307692308,
				],
			],
			"<=16px" => [
				400 => 0.57625,
				500 => 0.5992884615384615,
				700 => 0.6229423076923077,
			],
		],
		"display" => [
			"large" => [
				"font_size" => 57,
				"line_height" => 64,
				"letter_spacing" => 0,
				"font_weight" => [
					"regular" => 400,
					"emphasized" => 500,
				],
			],
			"medium" => [
				"font_size" => 45,
				"line_height" => 52,
				"letter_spacing" => 0,
				"font_weight" => [
					"regular" => 400,
					"emphasized" => 500,
				],
			],
			"small" => [
				"font_size" => 36,
				"line_height" => 44,
				"letter_spacing" => 0,
				"font_weight" => [
					"regular" => 400,
					"emphasized" => 500,
				],
			],
		],
		"headline" => [
			"large" => [
				"font_size" => 32,
				"line_height" => 40,
				"letter_spacing" => 0,
				"font_weight" => [
					"regular" => 400,
					"emphasized" => 500,
				],
			],
			"medium" => [
				"font_size" => 28,
				"line_height" => 36,
				"letter_spacing" => 0,
				"font_weight" => [
					"regular" => 400,
					"emphasized" => 500,
				],
			],
			"small" => [
				"font_size" => 24,
				"line_height" => 32,
				"letter_spacing" => 0,
				"font_weight" => [
					"regular" => 400,
					"emphasized" => 500,
				],
			],
		],
		"title" => [
			"large" => [
				"font_size" => 22,
				"line_height" => 28,
				"letter_spacing" => 0,
				"font_weight" => [
					"regular" => 400,
					"emphasized" => 500,
				],
			],
			"medium" => [
				"font_size" => 16,
				"line_height" => 24,
				"letter_spacing" => 0,
				"font_weight" => [
					"regular" => 500,
					"emphasized" => 700,
				],
			],
			"small" => [
				"font_size" => 14,
				"line_height" => 20,
				"letter_spacing" => 0,
				"font_weight" => [
					"regular" => 500,
					"emphasized" => 700,
				],
			],
		],
		"body" => [
			"large" => [
				"font_size" => 16,
				"line_height" => 24,
				"letter_spacing" => 0,
				"font_weight" => [
					"regular" => 400,
					"emphasized" => 500,
				],
			],
			"medium" => [
				"font_size" => 14,
				"line_height" => 20,
				"letter_spacing" => 0,
				"font_weight" => [
					"regular" => 400,
					"emphasized" => 500,
				],
			],
			"small" => [
				"font_size" => 12,
				"line_height" => 16,
				"letter_spacing" => 0.1,
				"font_weight" => [
					"regular" => 400,
					"emphasized" => 500,
				],
			],
		],
		"label" => [
			"large" => [
				"font_size" => 14,
				"line_height" => 20,
				"letter_spacing" => 0,
				"font_weight" => [
					"regular" => 500,
					"emphasized" => 700,
				],
			],
			"medium" => [
				"font_size" => 12,
				"line_height" => 16,
				"letter_spacing" => 0.1,
				"font_weight" => [
					"regular" => 500,
					"emphasized" => 700,
				],
			],
			"small" => [
				"font_size" => 11,
				"line_height" => 16,
				"letter_spacing" => 0.1,
				"font_weight" => [
					"regular" => 500,
					"emphasized" => 700,
				],
			],
		],
	];

	final public static function checkDependencies(bool &$verbose): void
	{
		static::runCommand(
			command: "phx-font-metrics --version",
			verbose: $verbose,
			error_message: <<<OUTPUT
			PHX-TOOLS Generate Typescale requires 'phx-font-metrics' for generating a Material You typescale.
			
			For install instructions follow https://github.com/andreapeverelli/phx-font-metrics/blob/main/README.md
			OUTPUT,
		);
	}

	final public static function getFontMetrics(
		string $description,
		string &$input,
		string &$weight_axis,
		bool &$verbose,
	): array
	{
		$weight = self::REFERENCE["display"]["large"]["font_weight"]["regular"];

		return json_decode(static::runCommand(
			description: [$description, "bold" => true, "new_line" => true],
			command: "phx-font-metrics $input $weight_axis $weight",
			get_output: true,
			verbose: $verbose,
		), true);
	}

	final public static function generateHeadingTypescale(array &$font_metrics): array
	{
		$roles = ["display", "headline", "title"];
		$sub_roles = ["large", "medium", "small"];

		$typescale = [];
		foreach($roles as $role) {
			foreach($sub_roles as $sub_role) {
				$typescale[$role][$sub_role] = static::generateTypescale(
					font_metrics: $font_metrics,
					role: $role,
					sub_role: $sub_role,
				);
			}
		}

		return $typescale;
	}

	final public static function generateSupportTypescale(array &$font_metrics): array
	{
		$roles = ["body", "label"];
		$sub_roles = ["large", "medium", "small"];

		$typescale = [];
		foreach($roles as $role) {
			foreach($sub_roles as $sub_role) {
				$typescale[$role][$sub_role] = static::generateTypescale(
					font_metrics: $font_metrics,
					role: $role,
					sub_role: $sub_role,
				);
			}
		}

		return $typescale;
	}

	private static function generateTypescale(array &$font_metrics, string &$role, string &$sub_role): array
	{
		echo BOLD . " | $role-$sub_role:" . RESET;

		$regular_weight =self::REFERENCE[$role][$sub_role]["font_weight"]["regular"];
		$emphasized_weight =self::REFERENCE[$role][$sub_role]["font_weight"]["emphasized"];

		if($regular_weight === 400 && self::REFERENCE[$role][$sub_role]["font_size"] > 16) {
			$x_ratio = self::REFERENCE["x_ratio"]["400>16px"];
			$natural_line_ratio = self::REFERENCE["natural_line_ratio"]["400>16px"];
		} else {
			$x_ratio = self::REFERENCE["x_ratio"]["regular"];
			$natural_line_ratio = self::REFERENCE["natural_line_ratio"]["regular"];
		}

		if(self::REFERENCE[$role][$sub_role]["font_size"] > 16) {
			$avg_advance_ratio = self::REFERENCE["avg_advance_ratio"][">16px"]["regular"][$regular_weight];
		} else {
			$avg_advance_ratio = self::REFERENCE["avg_advance_ratio"]["<=16px"][$regular_weight];
		}

		$font_size = self::REFERENCE[$role][$sub_role]["font_size"] * $x_ratio / $font_metrics["x_ratio"];
		$line_height = self::REFERENCE[$role][$sub_role]["line_height"] * $natural_line_ratio / $font_metrics["natural_line_ratio"];
		$letter_spacing = self::REFERENCE[$role][$sub_role]["letter_spacing"] * $avg_advance_ratio / $font_metrics["advance_ratio"];

		echo BOLD . GREEN . "SUCCESS\n" . RESET;

		return [
			"font_size" => self::REFERENCE[$role][$sub_role]["font_size"],
			"font-weight" => [
				"regular" => $regular_weight,
				"emphasized" => $emphasized_weight,
			],
			"line_height" => $line_height,
			"letter_spacing" => $letter_spacing,
		];
	}

	final public static function writeTypescale(array &$typescale, string &$output): void
	{
		if(file_exists($output)) {
			unlink($output);
		}

		file_put_contents($output, json_encode($typescale));
	}

	final public static function importFonts(string &$input, ?string &$input_support, bool &$verbose): void
	{
		$root = static::getProjectRoot();
		$input_filename = pathinfo($input)["basename"];
		if($input_support) $input_support_filename = pathinfo($input_support)["basename"];

		if(!file_exists("$root/public/fonts/")) {
			static::runCommand(
				command: "mkdir -p $root/public/fonts/",
				verbose: $verbose,
			);
		}

		static::runCommand(
			command: "cp $input $root/public/fonts/$input_filename",
			verbose: $verbose,
		);
		if($input_support) {
			static::runCommand(
				command: "cp $input_support $root/public/fonts/$input_support_filename",
				verbose: $verbose,
			);
		}
	}
}
