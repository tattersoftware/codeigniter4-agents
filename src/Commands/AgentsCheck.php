<?php namespace Tatter\Agents\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use Tatter\Agents\Models\AgentModel;
use Tatter\Agents\Models\ResultModel;
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
		foreach (model(AgentModel::class)->findAll() as $agent)
		{
			$agent->check();
		}
	}
}
