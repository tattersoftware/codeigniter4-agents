<?php namespace Tests\Support\Agents;

use Tatter\Agents\BaseAgent;

class TestAgent extends BaseAgent
{
	/**
	 * Attributes for Tatter\Handlers
	 *
	 * @var array<string, string>  Must include keys: name, uid, class, icon, summary
	 */
	public $attributes = [
		'name'    => 'Test Agent',
		'uid'     => 'test',
		'icon'    => 'fas fa-flask',
		'summary' => 'Agent for testing',
	];

	/**
	 * Sets a Session variable.
	 *
	 * @return void
	 */
	public function check(): void
	{
		$this->record('checkStatus', 'bool', true);
	}
}
