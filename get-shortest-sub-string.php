#!/usr/bin/env php
<?php

set_time_limit(0);

require_once __DIR__ . '/vendor/autoload.php';

require __DIR__ . '/app/app.php';

$console = $application->get('console');
$console->run();
