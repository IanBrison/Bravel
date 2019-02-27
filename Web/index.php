<?php

// load the autoloader that is composer
require '../vendor/autoload.php';

// laod the main Class for the application
require '../Application.php';

// start the work
$app = new Application(true);
$app->run();
