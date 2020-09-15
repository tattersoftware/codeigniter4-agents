<?php namespace Tatter\Agents\Models;

use CodeIgniter\Model;

class ResultModel extends Model
{
	protected $table      = 'agents_results';
	protected $primaryKey = 'id';
	protected $returnType = 'Tatter\Agents\Entities\Result';

	protected $useSoftDeletes = false;
	protected $useTimestamps  = true;
	protected $skipValidation = true;

	protected $allowedFields = [
		'server_id', 'agent_id', 'level', 'batch',
		'metric', 'format', 'content', 'hash',
	];

	/**
	 * The latest batch of results processed.
	 *
	 * @var int|null
	 */
	public static $batch;

	/**
	 * Names and levels for result severity.
	 * Should be identical to CodeIgniter\Log\Logger::$logLevels.
	 *
	 * @var array
	 */
	public static $levels = [
		'emergency' => 1,
		'alert'     => 2,
		'critical'  => 3,
		'error'     => 4,
		'warning'   => 5,
		'notice'    => 6,
		'info'      => 7,
		'debug'     => 8,
	];

	/**
	 * Returns the number ID for the next batch.
	 *
	 * @return int
	 */
	public function getBatch()
	{
		if (! is_int(self::$batch))
		{
			$batch = $this->db->table($this->table)
				->selectMax('batch')
				->get()->getResultObject();
			$batch = empty($batch) ? 0 : (int) reset($batch)->batch;

			self::$batch = $batch + 1;
		}

		return self::$batch;
	}
}
