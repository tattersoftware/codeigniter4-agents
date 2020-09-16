<?php namespace Tatter\Agents\Models;

use CodeIgniter\Model;

class HashModel extends Model
{
	protected $table      = 'agents_hashes';
	protected $primaryKey = 'id';

	protected $returnType     = 'object';
	protected $useSoftDeletes = false;

	protected $allowedFields = [
		'hash',
		'content',
	];

	protected $useTimestamps = false;

	protected $validationRules    = [];
	protected $validationMessages = [];
	protected $skipValidation     = true;
}
