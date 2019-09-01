<?php namespace Tatter\Agents\Controllers;

use CodeIgniter\Controller;
use Tatter\Agents\Models\AgentModel;
use Tatter\Agents\Models\HashModel;
use Tatter\Agents\Models\ResultModel;

class Agents extends Controller
{
	public function __construct()
	{
		// Preload the models & config
		$this->agents  = new AgentModel();
		$this->hashes  = new HashModel();
		$this->results = new ResultModel();
		
		$this->config  = config('Agents');
	}
	
	// Get all active agents for this instance
	public function agents()
	{
		// Get all agents
		$agents = [];
		foreach ($this->agents->findAll() as $agent)
		{
			$agents[] = [
				'name'    => $agent->name,
				'uid'     => $agent->uid,
				'class'   => $agent->class,
				'icon'    => $agent->icon,
				'summary' => $agent->summary,
			];
		}

		return $this->dataHandler($agents);
	}
	
	// Load any hashes since $timestamp
	public function hashes($timestamp = 0)
	{
		$hashes = $this->results->builder()
			->select('agents_hashes.hash, agents_hashes.content')
			->distinct()
            ->join('agents_hashes', 'agents_hashes.hash = agents_results.hash')
            ->where('agents_results.created_at >=', date('Y-m-d H:i:s', $timestamp))
            ->get()->getResultArray();
        
		return $this->dataHandler($hashes);
	}
	
	// Load any results since $timestamp
	public function results($timestamp = 0)
	{
		$results = $this->results->builder()
			->select('agents_results.*, agents.uid')
            ->join('agents', 'agents.id = agents_results.agent_id')
            ->where('agents_results.created_at >=', date('Y-m-d H:i:s', $timestamp))
            ->orderBy('created_at', 'asc')
            ->get()->getResultArray();
        
		return $this->dataHandler($results);
	}
	
	protected function dataHandler($array)
	{
		$this->response->setHeader('Cache-Control', 'no-cache');
		$this->response->setHeader('Content-Type', 'application/json');
		return json_encode($array);
	}
}
