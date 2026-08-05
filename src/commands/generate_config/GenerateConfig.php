<?php

namespace AndreaPeverelli\PhxCli;

trait GenerateConfig
{
	private static function generateConfig(array &$argv): int
	{
		if(isset($argv[2]) && $argv[2] === "--help") {
			echo <<<OUTPUT
			PHX-CLI Init
			Generates a project configuration interactivelly

			Command structure:
				phx init [--help]\n
			OUTPUT;

			return 0;
		}

		do {
			$config["app_name"] = trim(readline("App Name: "));
		} while($config["app_name"] === "");

		do {
			$config["app_short_name"] = trim(readline("App Short Name: "));
		} while($config["app_short_name"] === "");

		do {
			$config["vendor"] = trim(readline("Vendor: "));
		} while($config["vendor"] === "");

		do {
			$config["description"] = trim(readline("Description: "));
		} while($config["description"] === "");

		do {
			$config["license"] = trim(readline("License: "));
		} while($config["license"] === "");

		$config["homepage"] = trim(readline("Homepage (optional): "));

		do {
			$config["languages"] = trim(readline("Languages (separated by ,): "));
		} while($config["languages"] === "");

		do {
			$config["categories"] = trim(readline("Categories (separated by ,): "));
		} while($config["categories"] === "");
		do {
			$config["domain"] = trim(readline("Domain: "));
		} while($config["domain"] === "");

		do {
			$config["name_surname"] = trim(readline("Name and Surname: "));
		} while($config["name_surname"] === "");

		do {
			$config["email"] = trim(readline("Email: "));
		} while($config["email"] === "");

		$config["personal_website"] = trim(readline("Personal Website (optional): "));
		$config["x"] = trim(readline("X (optional): "));
		$config["github"] = trim(readline("GitHub (optional): "));

		if(file_exists("phx.config.json")) {
			unlink("phx.config.json");
		}
		file_put_contents("phx.config.json", json_encode($config));

		echo BOLD . GREEN . "\n##############################\n" . RESET;
		echo BOLD . "PHX configuration generated in " . GREEN . "phx.config.json" . RESET . BOLD . ".\n" . RESET;
		echo BOLD . GREEN . "##############################\n" . RESET;

		return 0;
	}
}
