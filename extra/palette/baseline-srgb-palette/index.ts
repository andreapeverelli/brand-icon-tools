import {argbFromHex, themeFromSourceColor } from "@material/material-color-utilities";

const theme = themeFromSourceColor(argbFromHex("#f82506"), [
	{
		name: "phx-srgb-palette",
		value: argbFromHex("#ff0000"),
		blend: true,
	},
]);

console.log(JSON.stringify(theme, null, null));
