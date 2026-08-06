<?php

/*
 *
 * GenerateMetadataFilesService.php
 * -----------------------------------
 * Copyright (c) 2026 Andrea Peverelli
 * License: GPL-3.0
 * -----------------------------------
 *
 * PHX-CLI Generate Metadata Files command functionalities.
 *
 */

namespace AndreaPeverelli\PhxCli;

final class GenerateMetadataFilesService
{
	use Utils;

	private function __construct() {}

	final public static function importFiles(array &$arguments): array|int
	{
		if(!file_exists($arguments["phx-config"])) {
			echo <<<OUTPUT
			PHX-CLI Generate Manifest needs a valid phx-config file.

			Please run 'phx generate:config'.\n
			OUTPUT;

			return 1;
		}

		if(!file_exists($arguments["palette"])) {
			echo <<<OUTPUT
			PHX-CLI Generate Manifest needs a valid palette file.

			Please run 'phx generate:palette'.\n
			OUTPUT;

			return 1;
		}

		return [
			"phx_config" => json_decode(file_get_contents($arguments["phx-config"]), true),
			"palette" => json_decode(file_get_contents($arguments["palette"]), true),
		];
	}

	final public static function generateManifest(array &$arguments, array &$phx_config, array &$palette): void
	{
		echo BOLD . " | manifest.webmanifest: " . RESET;

		$icons_uri = $arguments["icons-uri"];

		$categories = explode(",", $phx_config["categories"]);

		$manifest = [
			"id" => "/",
			"name" => $phx_config["app-name"],
			"short_name" => $phx_config["app-short-name"],
			"description" => $phx_config["description"],

			"lang" => $phx_config["languages"],
			"dir" => "ltr",

			"start_url" => "/",
			"scope" => "/",

			"display" => "standalone",
			"display_override" => [
				"window-controls-overlay",
				"standalone",
				"minimal-ui",
				"browser",
			],

			"orientation" => "any",

			"background_color" => $palette["neutral"][98]["srgb"],
			"theme_color" => $palette["primary"][40]["srgb"],

			"categories" => $categories,

			"icons" => [
				[
					"src" => "$icons_uri/favicon-16x16.png",
					"sizes" => "16x16",
					"type" => "image/png",
				],
				[
					"src" => "$icons_uri/favicon-32x32.png",
					"sizes" => "32x32",
					"type" => "image/png",
				],
				[
					"src" => "$icons_uri/favicon-48x48.png",
					"sizes" => "48x48",
					"type" => "image/png",
				],
				[
					"src" => "$icons_uri/favicon-64x64.png",
					"sizes" => "64x64",
					"type" => "image/png",
				],
				[
					"src" => "$icons_uri/favicon-128x128.png",
					"sizes" => "128x128",
					"type" => "image/png",
				],
				[
					"src" => "$icons_uri/favicon-256x256.png",
					"sizes" => "256x256",
					"type" => "image/png",
				],
				[
					"src" => "$icons_uri/favicon.svg",
					"sizes" => "any",
					"type" => "image/svg+xml",
				],
				[
					"src" => "$icons_uri/android-chrome-192x192.png",
					"sizes" => "192x192",
					"type" => "image/png",
					"purpose" => "any",
				],
				[
					"src" => "$icons_uri/maskable-icon-192x192.png",
					"sizes" => "192x192",
					"type" => "image/png",
					"purpose" => "maskable",
				],
				[
					"src" => "$icons_uri/android-chrome-512x512.png",
					"sizes" => "512x512",
					"type" => "image/png",
					"purpose" => "any",
				],
				[
					"src" => "$icons_uri/maskable-icon-512x512.png",
					"sizes" => "512x512",
					"type" => "image/png",
					"purpose" => "maskable",
				],
			],

			"launch_handler" => [
				"client_mode" => [
					"navigate-existing",
					"auto",
				],
			],

			"prefer_related_applications" => false,
		];

		$file_name = "{$arguments["output"]}/manifest.webmanifest";

		static::deleteFile(file_name: $file_name);
		file_put_contents($file_name, json_encode($manifest, JSON_PRETTY_PRINT));

		echo BOLD . GREEN . "SUCCESS\n" . RESET;
	}

	final public static function generateBrowserconfig(array &$arguments, array &$phx_config, array &$palette): void
	{
		echo BOLD . " | browserconfig.xml: " . RESET;

		$icons_uri = $arguments["icons-uri"];

		$browser_config = <<<XML
		<?xml version="1.0" encoding="utf-8"?>

		<browserconfig>
			<msapplication>
				<tile>
					<square70x70logo src="$icons_uri/mstile-70x70.png"/>
					<square150x150logo src="$icons_uri/mstile-150x150.png"/>
					<square310x310logo src="$icons_uri/mstile-310x310.png"/>
					<wide310x150logo src="$icons_uri/mstile-310x150.png"/>

					<TileColor>{$palette["primary"][40]["srgb"]}</TileColor>
				</tile>
			</msapplication>
		</browserconfig>\n
		XML;

		$file_name = "{$arguments["output"]}/browserconfig.xml";

		static::deleteFile(file_name: $file_name);
		file_put_contents($file_name, $browser_config);

		echo BOLD . GREEN . "SUCCESS\n" . RESET;
	}

	final public static function generateRobots(array &$arguments, array &$phx_config): void
	{
		echo BOLD . " | robots.txt: " . RESET;

		$robots = <<<TXT
		User-agent: *

		Allow: /

		Sitemap: https://{$phx_config["domain"]}/sitemap.xml

		Host: {$phx_config["domain"]}\n
		TXT;

		$file_name = "{$arguments["output"]}/robots.txt";

		static::deleteFile(file_name: $file_name);
		file_put_contents($file_name, $robots);

		echo BOLD . GREEN . "SUCCESS\n" . RESET;
	}

	final public static function generateSecurity(array &$arguments, array &$phx_config): void
	{
		echo BOLD . " | security.txt and .well-known/security.txt: " . RESET;

		$output = $arguments["output"];

		$expires = (new \DateTimeImmutable("now", new \DateTimeZone("UTC")))->modify("+6 months")->format("Y-m-s\TH:i:s\Z");
		
		$security = <<<TXT
		Contact: {$phx_config["email"]}
		Contact: https://{$phx_config["domain"]}/security

		Expires: $expires

		Preferred-Languages: {$phx_config["languages"]}

		Canonical: https://{$phx_config["domain"]}/.well-known/security.txt\n
		TXT;

		$file_name = "$output/security.txt";

		static::deleteFile(file_name: $file_name);
		file_put_contents($file_name, $security);

		$well_known_directory = "$output/.well-known";
		static::ensureDirectoryExists(directory: $well_known_directory, verbose: $arguments["verbose"]);

		$well_known_file_name = "$well_known_directory/security.txt";

		static::deleteFile(file_name: $well_known_file_name);
		file_put_contents($well_known_file_name, $security);

		echo BOLD . GREEN . "SUCCESS\n" . RESET;
	}

	final public static function generateHumans(array &$arguments, array &$phx_config): void
	{
		echo BOLD . " | humans.txt: " . RESET;

		$personal_website = $phx_config["personal-website"] !== "" ? "Site: {$phx_config["personal-website"]}\n" : "";
		$x = $phx_config["x"] !== "" ? "X: {$phx_config["x"]}\n" : "";
		$github = $phx_config["github"] !== "" ? "GitHub: {$phx_config["github"]}\n" : "";

		$languages = str_replace(",", "\n", $phx_config["languages"]);
		$current_year = date("Y");

		$humans = <<<TXT
		Developer: {$phx_config["name-surname"]}
		$personal_website$x$github
		Location: Earth

		Standards:
		HTML5
		CSS3
		ECMAScript

		Framework:
		PHX

		Generated:
		$current_year

		Language:
		$languages

		Powered by:
		PHX\n
		TXT;

		$file_name = "{$arguments["output"]}/humans.txt";

		static::deleteFile(file_name: $file_name);
		file_put_contents($file_name, $humans);

		echo BOLD . GREEN . "SUCCESS\n" . RESET;
	}
}
