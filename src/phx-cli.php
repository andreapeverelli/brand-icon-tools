<?php

namespace AndreaPeverelli\PhxCli;

require_once(__DIR__ . "/../vendor/autoload.php");
require_once(__DIR__ . "/BashConstants.php");

exit(App::run(argv: $argv));
