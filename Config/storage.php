<?php

/*
 * config file for storing files
 *
 * LocalDriver is available
 */
return [
	'drivers' => [
		'Local' => [
			'basePath' => '/storage',
		],
	],

	'locations' => [
		'example' => [
			'driver' => 'Local',
			'path' => '/example',
		],
	],
];
