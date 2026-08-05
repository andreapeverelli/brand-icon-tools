<?php

namespace AndreaPeverelli\PhxCli;

trait Help
{
	private static function help(): int
	{
		echo <<<OUTPUT
		WELCOME TO PHX-CLI.
		
		Available tools:
			| init
				Initialize a new PHX project.
			| register:project
				Adds an already existing project to the user's projects list.
			| setup
				Installs PHX and its dependencies.
			| generate:config
				Generates a PHX config file.
			| generate:iconset
				Generates favicon/Apple/Android/Microsoft/OpenGraph/Twitter icons from an SVG.
			| generate:palette
				Generates sRGB/Display P3/Rec. 2020 tonal palettes using HCT Material You core palette based on an Hex source color.
			| generate:metadata-files
				Generates manifest/browserconfig/robots/security/humans metadata files based on configurations.
			| generate:typescale
				Generate Material You based typescale.


		Available flags:
			| --version
				Shows PHX-CLI version
			| --help
				Shows this help message\n
		OUTPUT;

		return 0;
	}
}
