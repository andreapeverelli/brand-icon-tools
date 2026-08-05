<?php

namespace AndreaPeverelli\PhxCli;

trait GenerateIconset
{
	private static function generateIconset(array &$argv): int
	{
		if(isset($argv[2]) && $argv[2] === "--help") {
			echo <<<OUTPUT
			PHX-CLI Generate Iconset
			Generates favicon/Apple/Android/Microsoft/OpenGraph/Twitter icons from an SVG.

			Command structure:
				phx generate:iconset --input icon.svg [--output custom_path] [--verbose]
				phx generate:iconset --help

			Notes:
				The initial SVG icon should be borderless; all icons will be generated with a 90% scale factor and the PWA maskable icon variant with a 65% scale factor.\n
			OUTPUT;

			return 0;
		}

		$arguments_kv = static::getKeyValue(arguments: array_slice($argv, 2));

		$project_root = static::getProjectRoot();
		$output = $arguments_kv["--output"] ?? "$project_root/public/icons/";
		$input = $arguments_kv["--input"] ?? "";

		$verbose = $arguments_kv["--verbose"] ?? false;

		if(!file_exists($output)) {
			static::runCommand(
				command: "mkdir -p $output",
				verbose: $verbose,
			);
		}

		if($input === "") {
			do {
				$input = trim(readline("SVG Icon: "));
			} while($input === "");
		}

		if(str_ends_with($output, "/")) {
			$output = substr($output, 0, strlen($output) - 1);
		}

		if(!is_dir($output)) {
			mkdir($output);
		}

		$tmp = sys_get_temp_dir();

		GenerateIconsetInternals::checkDependencies(verbose: $verbose);

		GenerateIconsetInternals::normalizeInputSvg(tmp: $tmp, input: $input, verbose: $verbose);
		GenerateIconsetInternals::generateMonochromeSvg(tmp: $tmp, verbose: $verbose);
		GenerateIconsetInternals::optimizeIcons(tmp: $tmp, verbose: $verbose);

		GenerateIconsetInternals::generateFavicons(output: $output, tmp: $tmp, verbose: $verbose);
		GenerateIconsetInternals::generateAppleIcons(output: $output, tmp: $tmp, verbose: $verbose);
		GenerateIconsetInternals::generateAndroidIcons(output: $output, tmp: $tmp, verbose: $verbose);
		GenerateIconsetInternals::generateMicrosoftIcons(output: $output, tmp: $tmp, verbose: $verbose);
		GenerateIconsetInternals::generateOpenGraphIcon(output: $output, tmp: $tmp, verbose: $verbose);
		GenerateIconsetInternals::generateTwitterIcon(output: $output, tmp: $tmp, verbose: $verbose);

		echo BOLD . GREEN . "####################\n" . RESET ;
		echo BOLD . "Iconset generated in " . RESET . BOLD . GREEN .  "$output/" . RESET . BOLD . ".\n" . RESET;
		echo BOLD . GREEN . "####################\n" . RESET;

		return 0;
	}

}
