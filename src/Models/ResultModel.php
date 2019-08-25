<?php namespace Tatter\Agents\Models;

use CodeIgniter\Model;

class ResultModel extends Model
{
	protected $table      = 'agents_results';
	protected $primaryKey = 'id';

	protected $returnType = 'object';
	protected $useSoftDeletes = false;

	protected $allowedFields = ['agent_id', 'metric', 'format', 'content', 'level', 'batch'];

	protected $useTimestamps = true;

	protected $validationRules    = [];
	protected $validationMessages = [];
	protected $skipValidation     = true;
	
	// Result properties
	public static $batch;
	
	protected $levels = [
		'emergency' => 1,
		'alert'     => 2,
		'critical'  => 3,
		'error'     => 4,
		'warning'   => 5,
		'notice'    => 6,
		'info'      => 7,
		'debug'     => 8,
	];
	
	public function getBatch()
	{
		if (! is_numeric(self::$batch))
		{
			$batch = $this->db->table($this->table)
				->selectMax('batch')
				->get()
				->getResultObject();
			$batch = empty($batch) ? 0 : (int)reset($batch)->batch;
			self::$batch = $batch + 1;
		}		
		return self::$batch;
	}
}
