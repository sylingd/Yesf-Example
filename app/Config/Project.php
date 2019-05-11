<?php
return [
	'name' => 'app',
	'namespace' => 'App\\',
	'charset' => 'utf-8',
	'router' => [
		'map' => true,
		'extension' => true
	],
	'modules' => ['Api', 'Index'],
	'module' => 'Index',
	'view' => [
		'auto' => false,
		'extension' => 'phtml'
	]
];