<?php

namespace AndreaPeverelli\PhxTools;

trait Help
{
	private static function help(): int
	{
		echo <<<OUTPUT
		WELCOME TO PHX-TOOLS.
		
		Available tools:
			| init
				It generates a project configuration interactivelly
			| generate:iconset
				Generates favicon/Apple/Android/Microsoft/OpenGraph/Twitter icons from an SVG.
			| generate:palette
				It generates sRGB/Display P3/Rec. 2020 tonal palettes using HCT Material You core palette based on an Hex source color.


		Available flags:
			| --version
				Shows PHX-TOOLS version
			| --help
				Shows this help message\n
		OUTPUT;

		return 0;
	}
}
