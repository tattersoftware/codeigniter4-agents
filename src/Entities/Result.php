<?php namespace Tatter\Agents\Entities;

use CodeIgniter\Entity;
use Tatter\Agents\Models\HashModel;

class Result extends Entity
{
	protected $dates = [
		'created_at',
		'updated_at',
		'deleted_at',
	];

	// Intercept requests for content and load hashed data (if necessary)
	public function getContent()
	{
		if (empty($this->attributes['hash']))
		{
			return $this->attributes['content'];
		}

		$hashes = new HashModel();
		$row    = $hashes->where('hash', $this->attributes['hash'])->first();
		return unserialize($row->content);
	}
}
