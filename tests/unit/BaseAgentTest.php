<?php

use Tests\Support\Agents\TestAgent;
use Tests\Support\AgentsTestCase;
use Tests\Support\BaseAgent;

class BaseAgentTest extends AgentsTestCase
{
	public function testRegisterAddsToDatabase()
	{
		$agent = new TestAgent();
		$agent->register();

		$result = $this->agents->builder()->get()->getResult(); // @phpstan-ignore-line

		$this->assertCount(1, $result);
	}
}
