<?php

namespace AndreaPeverelli\PhxTools;

trait GenerateMetadataFiles
{
	private static function generateMetadataFiles(array $argv): int
	{
		if(isset($argv[2]) && $argv[2] === "--help") {
			echo <<<OUTPUT
			PHX-TOOLS Generate Metadata Files
				Generates manifest/browserconfig/robots/security/humans metadata files based on configurations

			Command structure:
				phx-tools generate:metadata-files [--phx-config phx_config_file] [--palette palette_file] [--icons-uri icons_uri] [--output output_dir]
				phx-tools generate:metadata-files --help\n
			OUTPUT;

			return 0;
		}

		$arguments_kv = static::getKeyValue(arguments: array_slice($argv, 2));

		$phx_config = $arguments_kv["--phx-config"] ?? "phx.config.json";
		$palette = $arguments_kv["--palette"] ?? "palette.json";

		$icons_uri = $arguments_kv["--icons-uri"] ?? "/icons";
		if(str_ends_with($icons_uri, "/")) {
			$icons_uri = substr($icons_uri, 0, strlen($icons_uri) - 1);
		}

		$output = $arguments_kv["--output"] ?? "public/";

		$verbose = in_array("--verbose", $arguments_kv) ? true : false;

		if(!file_exists($phx_config)) {
			echo <<<OUTPUT
			PHX-TOOLS Generate Manifest needs a valid phx-config file.

			Please run 'phx-tools init'.\n
			OUTPUT;

			return 1;
		}
		$phx_config = json_decode(file_get_contents($phx_config), true);
		if(!file_exists($palette)) {
			echo <<<OUTPUT
			PHX-TOOLS Generate Manifest needs a valid palette file.

			Please run 'phx-tools generate:palette --source-color "#source_color"'.\n
			OUTPUT;

			return 1;
		}
		$palette = json_decode(file_get_contents($palette), true);

		if(!file_exists($output)) {
			mkdir($output);
		}

		echo BOLD . "Generating metadata files:\n" . RESET;

		GenerateMetadataFilesInternals::generateManifest(
			phx_config: $phx_config,
			palette: $palette,
			icons_uri: $icons_uri,
			output: $output,
		);
		GenerateMetadataFilesInternals::generateBrowserconfig(
			phx_config: $phx_config,
			palette: $palette,
			icons_uri: $icons_uri,
			output: $output,
		);
		GenerateMetadataFilesInternals::generateRobots(phx_config: $phx_config, output: $output);
		GenerateMetadataFilesInternals::generateSecurity(phx_config: $phx_config, output: $output);
		GenerateMetadataFilesInternals::generateHumans(phx_config: $phx_config, output: $output);

		echo BOLD . GREEN . "\n###################\n" . RESET;
		echo BOLD . "Files metadata created in " . GREEN . $output . ".\n" . RESET;
		echo BOLD . GREEN . "###################\n" . RESET;

		return 0;
	}
}
