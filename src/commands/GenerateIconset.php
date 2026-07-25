<?php

namespace AndreaPeverelli\PhxTools;

trait GenerateIconset
{
	final public static function generateIconset(array $argv): int
	{
		if(!isset($argv[2])) {
			return static::badArguments(tool: "generate:iconset");
		}

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

		$arguments_kv = static::getKeyValue(arguments: array_slice($argv, 2));

		$out = $arguments_kv["--out"] ?? "out/";
		$input = $arguments_kv["--input"] ?? null;

		if(!$input) {
			return static::badArguments(tool: "generate:iconset");
		}

		if(!str_ends_with($out, "/")) {
			$out .= "/";
		}

		if(!is_dir($out)) {
			mkdir($out);
		}

		$tmp = sys_get_temp_dir();

		$make_icon = function(
			int $size,
			string $name,
			int $scale,
		) use (
			$input,
			$out,
			$tmp,
		): void
		{
			$inner_size = $size * $scale / 100;

			exec(<<<BASH
			rsvg-convert \
				--keep-aspect-ratio \
				--width "$inner_size" \
				--height "$inner_size" \
				"$input" \
				> "$tmp/icon.png"
			BASH);

			exec(<<<BASH
			magick "$tmp/icon.png" \
				-background none \
				-gravity center \
				-extent "{$size}x{$size}" \
				-alpha on \
				-depth 8 \
				-define png:exclude-chunk=all \
				"$out$name.png"
			BASH);
		};

		echo "Generating favicon...\n";
		$favicon_sizes = [16, 32, 48, 64, 128, 256];

		foreach($favicon_sizes as $favicon_size) {
			makeIcon(
				size: $favicon_size,
				name: "favicon-$favicon_size",
				scale: 90,
			);
		}

		exec(<<<BASH
		magick \
			{$out}favicon-16.png \
			{$out}favicon-32.png \
			{$out}favicon-48.png \
			{$out}favicon-64.png \
			{$out}favicon-128.png \
			{$out}favicon-256.png \
			{$out}favicon.ico
		BASH);

		unlink("{$out}favicon-16.png");
		unlink("{$out}favicon-32.png");
		unlink("{$out}favicon-48.png");
		unlink("{$out}favicon-64.png");
		unlink("{$out}favicon-128.png");
		unlink("{$out}favicon-256.png");

		echo "Generating Apple Icon...\n";
		makeIcon(
			size: 180,
			name: "apple-touch-icon",
			scale: 90,
		);

		echo "Generating PWA/Android...\n";
		makeIcon(
			size: 192,
			name: "icon-192",
			scale: 90,
		);
		makeIcon(
			size: 512,
			name: "icon-512",
			scale: 90,
		);
		makeIcon(
			size: 512,
			name: "icon-512-maskable",
			scale: 65,
		);

		echo "Generating OpenGraph...\n"
		exec(<<<BASH
		magick "{$out}icon-512.png" \
			-background none \
			-gravity center \
			-extent "1200x630" \
			-alpha on \
			-depth 8 \
			-define png:exclude-chunk=all \
			"{$out}og-image.png"
		BASH);

		echo "\nIconset generated in $out.\n";

		return 0;
	}
}
