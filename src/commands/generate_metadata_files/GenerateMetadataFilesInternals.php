<?php

namespace AndreaPeverelli\PhxTools;

final class GenerateMetadataFilesInternals
{
	public static function generateManifest(
		array &$phx_config,
		array &$palette,
		string &$icons_uri,
		string &$output,
	): void
	{
		echo BOLD . " | manifest.webmanifest: " . RESET;

		$categories = explode(",", $phx_config["categories"]);

		$manifest = [
			"id" => "/",
			"name" => $phx_config["app_name"],
			"short_name" => $phx_config["app_short_name"],
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

		$file_name = "$output/manifest.webmanifest";
		if(file_exists($file_name)) {
			unlink($file_name);
		}
		file_put_contents($file_name, json_encode($manifest));

		echo BOLD . GREEN . "SUCCESS\n" . RESET;
	}

	public static function generateBrowserconfig(
		array &$phx_config,
		array &$palette,
		string &$icons_uri,
		string &$output,
	): void
	{
		echo BOLD . " | browserconfig.xml: " . RESET;
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

		$file_name = "$output/browserconfig.xml";
		if(file_exists($file_name)) {
			unlink($file_name);
		}
		file_put_contents($file_name, $browser_config);

		echo BOLD . GREEN . "SUCCESS\n" . RESET;
	}

	public static function generateRobots(array &$phx_config, string &$output): void
	{
		echo BOLD . " | robots.txt: " . RESET;

		$robots = <<<TXT
		User-agent: *

		Allow: /

		Sitemap: https://{$phx_config["domain"]}/sitemap.xml

		Host: {$phx_config["domain"]}\n
		TXT;

		$file_name = "$output/robots.txt";
		if(file_exists($file_name)) {
			unlink($file_name);
		}
		file_put_contents($file_name, $robots);

		echo BOLD . GREEN . "SUCCESS\n" . RESET;
	}

	public static function generateSecurity(array &$phx_config, string &$output): void
	{
		echo BOLD . " | security.txt and .well-known/security.txt: " . RESET;

		$expires = (new \DateTimeImmutable("now", new \DateTimeZone("UTC")))->modify("+6 months")->format("Y-m-s\TH:i:s\Z");
		
		$security = <<<TXT
		Contact: {$phx_config["email"]}
		Contact: https://{$phx_config["domain"]}/security

		Expires: $expires

		Preferred-Languages: {$phx_config["languages"]}

		Canonical: https://{$phx_config["domain"]}/.well-known/security.txt\n
		TXT;

		$file_name = "$output/security.txt";
		if(file_exists($file_name)) {
			unlink($file_name);
		}
		file_put_contents($file_name, $security);

		if(!file_exists("$output/.well-known")) {
			mkdir("$output/.well-known/");
		}

		$well_known_file_name = "$output/.well-known/security.txt";
		if(file_exists($well_known_file_name)) {
			unlink($well_known_file_name);
		}
		file_put_contents($well_known_file_name, $security);

		echo BOLD . GREEN . "SUCCESS\n" . RESET;
	}

	public static function generateHumans(array &$phx_config, string &$output): void
	{
		echo BOLD . " | humans.txt: " . RESET;

		$personal_website = "";
		if($phx_config["personal_website"] !== "") {
			$personal_website = "Site: {$phx_config["personal_website"]}\n";
		}

		$x = "";
		if($phx_config["x"] !== "") {
			$x = "X: {$phx_config["x"]}\n";
		}

		$github = "";
		if($phx_config["github"] !== "") {
			$github = "GitHub: {$phx_config["github"]}\n";
		}

		$languages = str_replace(",", "\n", $phx_config["languages"]);
		$current_year = date("Y");

		$humans = <<<TXT
		Developer: {$phx_config["name_surname"]}
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

		$file_name = "$output/humans.txt";
		if(file_exists($file_name)) {
			unlink($file_name);
		}
		file_put_contents($file_name, $humans);

		echo BOLD . GREEN . "SUCCESS\n" . RESET;
	}
}
