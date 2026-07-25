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
				Generate Web/Socials/PWA/Android/iOS icons from an SVG

		Available flags:
			| --version
				Shows PHX-TOOLS version
			| --help
				Shows this help message\n
		OUTPUT;

		return 0;
	}
}
