<?php

namespace AndreaPeverelli\PhxTools;

trait GenerateIconset
{
	final public static function generateIconset(array $argv): int
	{
		if($argv[2] === "--help") {
			echo <<<OUTPUT
			PHX-TOOLS Generate Iconset

			Command structure:
				phx-tools generate:iconset --input icon.svg [--out custom_path]

			Notes:
				The initial SVG icon should be borderless; all icons will be generated with a 90% scale factor and the PWA maskable icon variant with a 65% scale factor.\n
			OUTPUT;

			return 0;
		}

		echo <<<OUTPUT
		Bad arguments.
		Try 'phx-tools generate:iconset --help' for tool help.\n
		OUTPUT;
	}
}
