<?php namespace Tatter\Agents\Controllers;

use CodeIgniter\Controller;
use Tatter\Agents\Models\AgentModel;
use Tatter\Agents\Models\ResultModel;

class Agents extends Controller
{
	public function __construct()
	{		
		// Preload the models & config
		$this->agents  = new AgentModel();
		$this->results = new ResultModel();
		$this->config  = config('Agents');
	}
	
	// Displays a list of all result batches
	public function index()
	{
		// Aggregate results into a navigable table
		$rows = [];
		$results = $this->results->builder()
			->select('batch')
			->selectCount('*')
			->selectCount('agent_id')
			->selectSum('level')
			->groupBy('batch')
			->get()->getResult();
				
		var_dump($results);
	}
}
