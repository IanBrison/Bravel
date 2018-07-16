<?php

// load the autoloader that is composer
require '../vendor/autoload.php';

// laod the main Class for the application
require '../Application.php';

$app = new Application(true);
$app->run();
