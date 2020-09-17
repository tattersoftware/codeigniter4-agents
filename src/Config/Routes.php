<?php

// Routes to Agents controller
$routes->group('agents', ['namespace' => 'Tatter\Agents\Controllers'], function ($routes) {
	$routes->get('agents', 'Agents::agents');
	$routes->get('hashes', 'Agents::hashes');
	$routes->get('results', 'Agents::results');
});
