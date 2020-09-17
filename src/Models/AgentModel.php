<?php namespace Tatter\Agents\Models;

use CodeIgniter\Model;
use Tatter\Agents\BaseAgent;

class AgentModel extends Model
{
	protected $table      = 'agents';
	protected $primaryKey = 'id';
	protected $returnType = 'array'; // afterFind converts to BaseAgent

	protected $useSoftDeletes = true;
	protected $useTimestamps  = true;
	protected $skipValidation = false;

	protected $allowedFields = [
		'name',
		'uid',
		'class',
		'icon',
		'summary',
	];

	protected $afterFind = ['convertToAgents'];

	/**
	 * Converts a database row to its Agent equivalent.
	 *
	 * @param array $row
	 *
	 * @return BaseAgent
	 */
	public function castAsAgent(array $row): ?BaseAgent
	{
		if (empty($row['class']))
		{
			return null;
		}

		// Get the BaseAgent instance
		$agent = new $row['class']();

		// Add database-specific fields
		foreach (['id', 'created_at', 'updated_at', 'deleted_at'] as $field)
		{
			$agent->$field = $row[$field];
		}

		return $agent;
	}

	/**
	 * Converts find* results to Agents. Triggered by `afterFind`
	 *
	 * @param array $eventData
	 *
	 * @return array  $eventData but the 'data' array replaced with Agents
	 */
	protected function convertToAgents(array $eventData): array
	{
		if (! empty($eventData['data']))
		{
			$eventData['data'] = $eventData['singleton']
				? $this->castAsAgent($eventData['data'])
				: array_map([$this, 'castAsAgent'], $eventData['data']);
		}

		return $eventData;
	}
}
