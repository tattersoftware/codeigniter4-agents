<?php namespace Tatter\Agents\Config;

use CodeIgniter\Config\BaseConfig;

class Handlers extends BaseConfig
{
	// Directory to search across namespaces for supported handlers
	public $directory = 'Agents';
	
	// Model used to track handlers
	public $model = '\Tatter\Agents\Models\AgentModel';
}
