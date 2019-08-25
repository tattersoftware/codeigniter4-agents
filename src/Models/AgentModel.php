<?php namespace Tatter\Agents\Models;

use CodeIgniter\Model;

class AgentModel extends Model
{
	protected $table      = 'agents';
	protected $primaryKey = 'id';

	protected $returnType = 'Tatter\Agents\Entities\Agent';
	protected $useSoftDeletes = true;

	protected $allowedFields = [
		'name', 'uid', 'class', 'icon', 'summary',
	];

	protected $useTimestamps = true;

	protected $validationRules    = [];
	protected $validationMessages = [];
	protected $skipValidation     = false;
}
