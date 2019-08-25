<?php namespace Tatter\Agents\Agents;

use CodeIgniter\Database\Database;
use CodeIgniter\Database\Exceptions\DatabaseException;
use Tatter\Agents\BaseAgent;
use Tatter\Agents\Interfaces\AgentInterface;

class DatabaseAgent extends BaseAgent implements AgentInterface
{
	// Attributes for Tatter\Handlers
	public $attributes = [
		'name'       => 'Database',
		'uid'        => 'database',
		'icon'       => 'fas fa-database',
		'summary'    => 'Verify database connectivity and permissions',
	];
	
	// Databases to ignore when recording
	protected $ignoredDatabases = ['information_schema'];
	
	// Check each database group for connectivity and various permissions
	public function check()
	{
		$critical = 0;
		
		// Get database groups from the config
		$config = config('Database');
		
		// Default group
		$this->record('defaultGroup', 'string', $config->defaultGroup);
		
		// Group configurations
		$groups = [];
		foreach ($config as $group => $array)
		{
			// Skip items that aren't groups
			if (! is_array($array) || ! isset($array['DBDriver']))
			{
				continue;
			}
			
			// If it is a valid configuration then add it to the connections to test
			if ($array['DBDriver'])
			{
				$groups[] = $group;
			}
			
			// Don't transmit passwords
			$array['password'] = empty($array['password']) ? '*Not set*' : '*Set*';
			$array['group'] = $group;
			$this->record("{$group}Config", 'array', $array);
		}
		
		if (empty($groups))
		{
			$critical++;
			$this->record('groups', 'No valid database groups configured', 'string', 'emergency');			
		}
		
		// Try connections for each group
		$connected = 0;
		foreach ($groups as $group)
		{
			try {
				$db = \Config\Database::connect($group);
			}
			catch (\Exception $e)
			{
				$this->record("{$group}Connect", 'string', $e->getMessage(), 'error');
				continue;
			}
			$this->record("{$group}Connect", 'bool', 1);
			$connected++;
			
			// Determine available databases
			$util = (new Database())->loadUtils($db);
			$databases = $util->listDatabases();
			$content = array_diff($databases, $this->ignoredDatabases);
			$this->record("{$group}Databases", 'array', $content);
		}
		
		// Make sure there was at least one connection
		if ($connected) {
			$this->record('connections', 'int', $connected);
		} else {
			$critical++;
			$this->record('connections', 'int', 0, 'emergency');
		}
		
		return $critical;
	}
}
