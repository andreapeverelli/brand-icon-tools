<?php

namespace AndreaPeverelli\PhxTools;

trait GenerateIconset
{
	private static function generateIconset(array &$argv): int
	{
		if(!isset($argv[2])) {
			return static::badArguments(tool: "generate:iconset");
		}

		if($argv[2] === "--help") {
			echo <<<OUTPUT
			PHX-TOOLS Generate Iconset
			Generates favicon/Apple/Android/Microsoft/OpenGraph/Twitter icons from an SVG.

			Command structure:
				phx-tools generate:iconset --input icon.svg [--out custom_path] [--verbose]
				phx-tools generate:iconset --help

			Notes:
				The initial SVG icon should be borderless; all icons will be generated with a 90% scale factor and the PWA maskable icon variant with a 65% scale factor.\n
			OUTPUT;

			return 0;
		}

		$arguments_kv = static::getKeyValue(arguments: array_slice($argv, 2));

		$out = $arguments_kv["--out"] ?? "out/";
		$input = $arguments_kv["--input"] ?? null;

		if(!$input) {
			return static::badArguments(tool: "generate:iconset");
		}

		if(str_ends_with($out, "/")) {
			$out = substr($out, 0, strlen($out) - 1);
		}

		if(!is_dir($out)) {
			mkdir($out);
		}

		$verbose = in_array("--verbose", $arguments_kv) ? true : false;

		$tmp = sys_get_temp_dir();

		GenerateIconsetInternals::checkDependencies(verbose: $verbose);

		GenerateIconsetInternals::normalizeInputSvg(tmp: $tmp, input: $input, verbose: $verbose);
		GenerateIconsetInternals::generateMonochromeSvg(tmp: $tmp, verbose: $verbose);
		GenerateIconsetInternals::optimizeIcons(tmp: $tmp, verbose: $verbose);

		GenerateIconsetInternals::generateFavicons(out: $out, tmp: $tmp, verbose: $verbose);
		GenerateIconsetInternals::generateAppleIcons(out: $out, tmp: $tmp, verbose: $verbose);
		GenerateIconsetInternals::generateAndroidIcons(out: $out, tmp: $tmp, verbose: $verbose);
		GenerateIconsetInternals::generateMicrosoftIcons(out: $out, tmp: $tmp, verbose: $verbose);
		GenerateIconsetInternals::generateOpenGraphIcon(out: $out, tmp: $tmp, verbose: $verbose);
		GenerateIconsetInternals::generateTwitterIcon(out: $out, tmp: $tmp, verbose: $verbose);

		echo BOLD . GREEN . "####################\n" . RESET ;
		echo BOLD . "Iconset generated in " . RESET . BOLD . GREEN .  "$out/" . RESET . BOLD . ".\n" . RESET;
		echo BOLD . GREEN . "####################\n" . RESET;

		return 0;
	}

}
