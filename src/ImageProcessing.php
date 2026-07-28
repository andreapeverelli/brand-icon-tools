<?php

namespace AndreaPeverelli\PhxTools;

trait ImageProcessing
{
	private static function normalizeSvg(
		string $input,
		string $output,
		bool &$verbose,
		null|string|array $description = null,
	): void
	{
		static::runCommand(
			description: $description,
			command: <<<BASH
			inkscape $input \
				--export-plain-svg \
				--export-filename=$output
			BASH,
			verbose: $verbose,
		);
	}

	private static function svgToMonochromeSvg(
		string $input,
		string $output,
		bool &$verbose,
		null|string|array $description = null,
	): void
	{
		static::runCommand(
			description: $description,
			command: <<<BASH
			inkscape $input \
				--export-plain-svg \
				--export-filename=$output \
				--actions="select-all;object-set-property:fill,#000000;object-set-property:stroke,#000000"
			BASH,
			verbose: $verbose,
		);
	}

	private static function optimizeSvg(
		string $input,
		string $output,
		bool &$verbose,
		null|string|array $description = null,
	): void
	{
		static::runCommand(
			description: $description,
			command: <<<BASH
			svgo $input \
				--output $output \
				--config /usr/share/phx-tools/config/svgo-config.js
			BASH,
			verbose: $verbose,
		);
	}

	private static function optimizeSvgs(
		array $inputs,
		array $outputs,
		bool &$verbose,
		array $descriptions = [],
	): void
	{
		foreach($inputs as $i => $input) {
			static::optimizeSvg(
				description: $descriptions[$i] ?? null,
				input: $input,
				output: $outputs[$i],
				verbose: $verbose,
			);
		}
	}

	private static function svgToPng(
		string $input,
		string $output,
		int|array $size,
		int $scale,
		bool &$verbose,
		null|string|array $description = null,
	): void
	{
		if(gettype($size) === "array") {
			$inner_size_x = $size[0] * $scale / 100;
			$inner_size_y = $size[1] * $scale / 100;
		} else {
			$inner_size_x = $size * $scale / 100;
			$inner_size_y = $size * $scale / 100;
		}

		static::runCommand(
			description: $description,
			command: <<<BASH
			rsvg-convert \
				--keep-aspect-ratio \
				--width "$inner_size_x" \
				--height "$inner_size_y" \
				"$input" \
				> "$output"
			BASH,
			verbose: $verbose,
		);
	}

	private static function pngToPng(
		string $input,
		string $output,
		int|array $size,
		int $scale,
		bool &$verbose,
		null|string|array $description = null,
	): void
	{
		if(gettype($size) === "array") {
			$x = $size[0];
			$y = $size[1];
		} else {
			$x = $size;
			$y = $size;
		}

		static::runCommand(
			description: $description,
			command: <<<BASH
			magick "$input" \
				-background none \
				-gravity center \
				-extent "{$x}x{$y}" \
				-alpha on \
				-depth 8 \
				-define png:exclude-chunk=all \
				@verbose_argument()"$output"
			BASH,
			verbose: $verbose,
			verbose_argument: "-verbose",
		);
	}

	private static function svgToCustomPng(
		string $input,
		string $output,
		int|array $size,
		int $scale,
		bool &$verbose,
		null|string|array $description = null,
	): void
	{
		$id = uniqid();
		$tmp = sys_get_temp_dir();

		static::svgToPng(
			input: $input,
			output: "$tmp/$id.png",
			size: $size,
			scale: $scale,
			verbose: $verbose,
		);
		static::pngToPng(
			description: $description,
			input: "$tmp/$id.png",
			output: $output,
			size: $size,
			scale: $scale,
			verbose: $verbose,
		);
	}

	private static function svgToCustomPngs(
		string $input,
		array $outputs,
		array $sizes,
		int $scale,
		bool &$verbose,
		array $descriptions = [],
	): void
	{
		for($i = 0; $i < count($outputs); $i++) {
			static::svgToCustomPng(
				input: $input,
				output: $outputs[$i],
				size: $sizes[$i],
				scale: $scale,
				verbose: $verbose,
				description: $descriptions[$i] ?? null,
			);
		}
	}

	private static function pngsToIco(
		array $inputs,
		string $output,
		bool &$verbose,
		null|string|array $description = null,
	): void
	{
		$inputs = implode(" ", $inputs);

		static::runCommand(
			description: $description,
			command: <<<BASH
			magick @verbose_argument() \
				$inputs \
				$output
			BASH,
			verbose: $verbose,
			verbose_argument: "-verbose"
		);
	}
}
