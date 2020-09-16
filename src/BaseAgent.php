<?php namespace Tatter\Agents;

use CodeIgniter\Entity;
use Tatter\Agents\Config\Agents;
use Tatter\Agents\Exceptions\AgentsException;
use Tatter\Agents\Models\AgentModel;
use Tatter\Agents\Models\HashModel;
use Tatter\Agents\Models\ResultModel;
use Tatter\Handlers\Interfaces\HandlerInterface;

abstract class BaseAgent extends Entity implements HandlerInterface
{
	/**
	 * Attributes for Tatter\Handlers
	 *
	 * @var array<string, string>  Must include keys: name, uid, class, icon, summary
	 */
	protected $attributes;

	/**
	 * An instance of the configuration.
	 *
	 * @var Agents
	 */
	protected $config;

	/**
	 * Overriding value to use for the result batch
	 *
	 * @var integer|null
	 */
	protected $batch;

	/**
	 * Initializes the class.
	 *
	 * @param Agents|null $config
	 */
	public function __construct(Agents $config = null)
	{
		$this->config = $config ?? config('Agents');
	}

	/**
	 * Runs this Agent's status check. Usually in turn calls record().
	 *
	 * @return void
	 */
	abstract protected function check(): void;

	/**
	 * Sets an overriding batch ID to use.
	 *
	 * @param integer|null $batch
	 *
	 * @return $this
	 */
	protected function setBatch(int $batch = null): self
	{
		$this->batch = $batch;

		return $this;
	}

	/**
	 * Add this Agent to the database, if it does not exist.
	 *
	 * @return boolean  Whether this was a new registration
	 */
	public function register(): bool
	{
		// Check for an existing entry
		if (model(AgentModel::class)->withDeleted()->where(['uid' => $this->attributes['uid']])->first())
		{
			return false;
		}

		// Build the row and add it to the database
		$row          = $this->toArray();
		$row['class'] = get_class($this);

		return (bool) model(AgentModel::class)->insert($row);
	}

	/**
	 * Creates a single result record.
	 *
	 * @param string              $metric
	 * @param string              $format
	 * @param mixed               $content
	 * @param string|integer|null $level
	 */
	protected function record(string $metric, string $format, $content, $level = null)
	{
		// Convert text levels to their integer equivalent
		if ($level && ! is_numeric($level))
		{
			$level = model(ResultModel::class)::$levels[$level];
		}

		// Serialize arrays
		if (is_array($content) || is_object($content))
		{
			$string = serialize($content);

			if (empty($content))
			{
				throw AgentsException::forUnserializable(get_class($content));
			}

			$content = $string;
			unset($string);
		}

		// Build the result row
		$result = [
			'agent_id' => $this->attributes['id'],
			'metric'   => $metric,
			'format'   => $format,
			'content'  => $content ?? '',
			'batch'    => $this->batch ?? model(ResultModel::class)->getBatch(),
		];

		// If content exceeds 255 limit then hash and store it out
		if (strlen($content) > 255)
		{
			$hashes = model(HashModel::class);
			$hash   = md5($content);

			$result['hash'] = $hash;
			unset($result['content']);

			// Check for existing data with this hash
			if (! $hashes->where('hash', $hash)->first())
			{
				$hashes->insert([
					'hash'    => $hash,
					'content' => $content,
				]);
			}
		}

		return model(ResultModel::class)->insert($result);
	}
}
