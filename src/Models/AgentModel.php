<?php namespace Tatter\Agents\Models;

use CodeIgniter\Model;

class AgentModel extends Model
{
	protected $table      = 'agents';
	protected $primaryKey = 'id';
	protected $returnType = 'object'; // afterFind converts to BaseAgent

	protected $useSoftDeletes = true;
	protected $useTimestamps  = true;
	protected $skipValidation = false;

	protected $allowedFields = [
		'name', 'uid', 'class', 'icon', 'summary',
	];

	protected $afterFind = ['castAsAgent'];

	/**
	 * Add this Agent to the database, if it does not exist.
	 *
	 * @param array $eventData
	 *
	 * @return array  $eventData but the 'data' array replaced with actual Agents
	 */
	protected function castAsAgent(array $eventData): array
	{
		$result = [];
		foreach ($eventData['data'] as $object)
		{
			// Get the BaseAgent instance
			$agent = new $object->class();

			// Add database-specific fields
			foreach (['id', 'created_at', 'updated_at', 'deleted_at'] as $field)
			{
				$agent->$field = $object->$field;
			}

			$result[] = $agent;
		}

		$eventData['data'] = $result;
		return $eventData;
	}
}
