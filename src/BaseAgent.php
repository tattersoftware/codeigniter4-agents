<?php namespace Tatter\Agents;

use Tatter\Handlers\Handlers\BaseHandler;
use Tatter\Handlers\Interfaces\HandlerInterface;
use Tatter\Agents\Models\ResultModel;

class BaseAgent extends BaseHandler implements HandlerInterface
{	
	public function __construct()
	{
		$this->config  = config('Agents');
		$this->results = new ResultModel();
	}
	
	// Given a metric and content, creates a new result record for this probe
	protected function record($metric, $format, $content, $level = null)
	{
		// Convert text levels to their integer equivalent
		if ($level && ! is_numeric($level))
		{
			$level = $this->results->levels[$level];
		}
		
		// Serialize arrays
		if ($format=='array' && is_array($content))
		{
			$content = serialize($content);
		}

		$result = [
			'agent_id' => $this->agentId,
			'metric'   => $metric,
			'format'   => $format,
			'content'  => $content,
			'batch'    => $this->batch ?? $this->results->getBatch(),
		];
		return $this->results->insert($result);
	}
}
