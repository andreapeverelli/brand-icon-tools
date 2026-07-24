<?php

namespace AndreaPeverelli\PhxTools;

trait Help
{
	final public static function help(): int
	{
		echo <<<OUTPUT
		WELCOME TO PHX-TOOLS.
		
		Available tools:
			| generate:iconset
				Generate Web/Socials/PWA/Android/iOS icons from an SVG

		Available flags:
			| --version
				Shows PHX-TOOLS version
			| --update
				Updates PHX-TOOLS
			| --uninstall
				Uninstalls PHX-TOOLS
			| --help
				Shows this help message\n
		OUTPUT;

		return 0;
	}
}
