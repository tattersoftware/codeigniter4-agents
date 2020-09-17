<?php namespace Tatter\Agents\Config;

use CodeIgniter\Config\BaseConfig;

class Agents extends BaseConfig
{
	// Whether to continue instead of throwing exceptions
	public $silent = true;

	// How many minutes a check lasts before it is considered "stale"
	public $ttl = 15;

	// Session variable to check for a logged-in user ID
	public $userSource = 'logged_in';
}
