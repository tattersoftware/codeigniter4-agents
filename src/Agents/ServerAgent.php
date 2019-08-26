<?php namespace Tatter\Agents\Agents;

use Tatter\Agents\BaseAgent;
use Tatter\Agents\Interfaces\AgentInterface;

class ServerAgent extends BaseAgent implements AgentInterface
{
	// Attributes for Tatter\Handlers
	public $attributes = [
		'name'       => 'Server',
		'uid'        => 'server',
		'icon'       => 'fas fa-server',
		'summary'    => 'Load server information and configuration',
	];
	
	public function check()
	{
		// Get the hostname from $_SERVER
		$request = service('request');
    	$this->record('hostname', 'string', $request->getServer('HOSTNAME'));
		
		// Get OS info
		$this->record('system', 'string', php_uname());
		
    	// PHP
		$this->record('phpVersion', 'string', phpversion());
		$this->record('phpIni', 'array', ini_get_all());
		$this->record('phpExtensions', 'array', get_loaded_extensions());
		
		// Apache
		if (function_exists('apache_get_version'))
			$this->record('apacheVersion', 'string', apache_get_version());
		if (function_exists('apache_get_modules'))
			$this->record('apacheModules', 'array', apache_get_modules());
		
		// Stream info
		$this->record('streamWrappers', 'array', stream_get_wrappers());
		$this->record('streamTransports', 'array', stream_get_transports());
		$this->record('streamFilters', 'array', stream_get_filters());
		
	}
}
