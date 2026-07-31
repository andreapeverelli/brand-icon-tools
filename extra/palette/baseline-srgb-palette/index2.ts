import {argbFromHex, CorePalette} from "@material/material-color-utilities";

process.stdout.write(JSON.stringify(CorePalette.of(argbFromHex(process.argv[2])), null, null));

process.exit(0);
