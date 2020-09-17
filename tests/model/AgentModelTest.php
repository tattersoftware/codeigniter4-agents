<?php

use Tatter\Agents\BaseAgent;
use Tests\Support\Agents\TestAgent;
use Tests\Support\AgentsTestCase;

class AgentModelTest extends AgentsTestCase
{
	protected function setUp(): void
	{
		parent::setUp();

		// Register all Agents
		$this->handlers->register();
	}

	public function testCastAsAgent()
	{
		$rows   = $this->agents->builder()->get()->getResultArray(); // @phpstan-ignore-line
		$result = $this->agents->castAsAgent($rows[0]);

		$this->assertInstanceOf(BaseAgent::class, $result);
	}

	public function testFindReturnsAgent()
	{
		$result = $this->agents->where(['uid' => 'test'])->first();

		$this->assertInstanceOf(TestAgent::class, $result);
	}
}
