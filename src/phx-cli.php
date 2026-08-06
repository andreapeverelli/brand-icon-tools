<?php

/*
 *
 * phx-cli.php
 * -----------------------------
 * Copyright (c) 2026 Andrea Peverelli
 * License: 	GPL-3.0
 * -----------------------------
 *
 * PHP-side entry point for PHX-CLI.
 *
 */

namespace AndreaPeverelli\PhxCli;

require_once(__DIR__ . "/../vendor/autoload.php");
require_once(__DIR__ . "/BashConstants.php");

exit(App::run(argv: $argv));
