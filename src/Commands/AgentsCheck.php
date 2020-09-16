<?php namespace Tatter\Agents\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use Tatter\Agents\Models\AgentModel;
use Tatter\Handlers\Handlers;

class AgentsCheck extends BaseCommand
{
	protected $group       = 'Agents';
	protected $name        = 'agents:check';
	protected $description = 'Pull and assess status from all detected agents.';

	public function run(array $params)
	{
		// Discover and register any new Agents
		(new Handlers('Agents'))->register();

		// Run each Agent's check
		$critical = 0;
		foreach (model(AgentModel::class)->findAll() as $agent)
		{
			$critical += $agent->check();
		}

		if ($critical === 1)
		{
			CLI::write('WARNING: returned one critical error!', 'yellow');
		}
		elseif ($critical > 1)
		{
			CLI::write('WARNING: returned ' . $critical . ' critical errors!', 'yellow');
		}
	}
}
