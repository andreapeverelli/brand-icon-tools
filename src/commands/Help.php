<?php

namespace AndreaPeverelli\PhxTools;

trait Help
{
	private static function help(): int
	{
		echo <<<OUTPUT
		WELCOME TO PHX-TOOLS.
		
		Available tools:
			| generate:iconset
				Generates favicon/Apple/Android/Microsoft/OpenGraph/Twitter icons from an SVG.

		Available flags:
			| --version
				Shows PHX-TOOLS version
			| --help
				Shows this help message\n
		OUTPUT;

		return 0;
	}
}
