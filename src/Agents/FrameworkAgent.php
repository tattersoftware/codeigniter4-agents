<?php namespace Tatter\Agents\Agents;

use Codeigniter\Codeigniter;
use Tatter\Agents\BaseAgent;
use Tatter\Agents\Interfaces\AgentInterface;

class FrameworkAgent extends BaseAgent implements AgentInterface
{
	// Attributes for Tatter\Handlers
	public $attributes = [
		'name'       => 'Framework',
		'uid'        => 'framework',
		'icon'       => 'fas fa-fire',
		'summary'    => 'Assess CodeIgniter version and state',
	];
	
	// Check CodeIgniter framework and functionality
	public function check()
	{
		$this->record('version', 'string', Codeigniter::CI_VERSION);
		$this->record('environment', 'string', ENVIRONMENT);
		
		// Paths
		$paths = [];
		foreach (config('Paths') as $type => $path)
			$paths[$type] = realpath($path);
		$this->record('paths', 'array', $paths);
		
		// Try to determine installation source
		$source = '';
		if (is_file(ROOTPATH . 'composer.json'))
		{
			// Read from Composer and try to determine source
			$composer = json_decode(file_get_contents(ROOTPATH . 'composer.json'), true);
			if (isset($composer['require']) && is_array($composer['require']))
			{
				// Check each "require" for "codeigniter4/*"
				foreach ($composer['require'] as $name => $version)
				{
					// Check for AppStarter and DevStarter
					if ($name=='codeigniter4/codeigniter4' && $version=='dev-develop')
					{
						$source = 'DevStarter';
						break;
					}
					elseif ($name=='codeigniter4/framework')
					{
						$source = 'AppStarter';
						break;
					}
					elseif (strpos($name, 'codeigniter4/') === 0)
					{
						$source = $name . '@' . $version;
						break;
					}
				}
			}
		}
		$this->record('installation', 'string', $source ?: 'download');
	}
}
