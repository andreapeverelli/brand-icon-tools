<?php

namespace AndreaPeverelli\PhxTools;

trait GeneratePalette
{
	private static function generatePalette(array $argv): int
	{
		if(isset($argv[2]) === $argv[2] === "--help") {
			echo <<<OUTPUT
			PHX-TOOLS Generate Palette
			It generates sRGB/Display P3/OAKLAB Material You based palettes from a base HEX color.

			Command structure:
				phx-tools generate:palette --base-color #hex_color [--verbose]
				phx-tools generate:palette --help
			OUTPUT;

			return 0;
		}

		return 0;
	}
}
