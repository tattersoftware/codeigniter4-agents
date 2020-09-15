<?php namespace Tatter\Agents\Entities;

use CodeIgniter\Entity;

class Agent extends Entity
{
	protected $dates = ['created_at', 'updated_at', 'deleted_at'];
	
	// Load the class and pass to the handler
	public function check(...$args)
	{
		$class            = $this->attributes['class'];
		$handler          = new $class();
		$handler->agentId = $this->attributes['id'];
		
		return $handler->check(...$args);
	}
}
