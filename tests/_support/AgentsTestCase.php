<?php namespace Tests\Support;

use CodeIgniter\Test\CIDatabaseTestCase;
use Tatter\Agents\Models\AgentModel;
use Tatter\Handlers\Handlers;

class AgentsTestCase extends CIDatabaseTestCase
{
	/**
	 * Should the database be refreshed before each test?
	 *
	 * @var boolean
	 */
	protected $refresh = true;

	/**
	 * The namespace to help us find the migration classes.
	 *
	 * @var string
	 */
	protected $namespace = 'Tatter\Agents';

	/**
	 * Instance of AgentModel.
	 *
	 * @var AgentModel
	 */
	protected $agents;

	/**
	 * Instance of the Handlers library.
	 *
	 * @var Handlers
	 */
	protected $handlers;

	protected function setUp(): void
	{
		parent::setUp();

		// Initialize the model and handler library
		$this->agents   = new AgentModel();
		$this->handlers = new Handlers('Agents');
	}
}
