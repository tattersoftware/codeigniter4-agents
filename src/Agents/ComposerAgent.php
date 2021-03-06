<?php namespace Tatter\Agents\Agents;

use Tatter\Agents\BaseAgent;

class ComposerAgent extends BaseAgent
{
	/**
	 * Attributes for Tatter\Handlers
	 *
	 * @var array<string, string>  Must include keys: name, uid, class, icon, summary
	 */
	public $attributes = [
		'name'    => 'Composer',
		'uid'     => 'composer',
		'icon'    => 'fas fa-box-open',
		'summary' => 'Harvest Composer states',
	];

	/**
	 * Checks Composer packages and dependencies.
	 *
	 * @return void
	 */
	public function check(): void
	{
		$installed = 0;
		if (is_file(ROOTPATH . 'composer.json'))
		{
			$installed = 1;

			// Read in the entire composer.json
			$composer = json_decode(file_get_contents(ROOTPATH . 'composer.json'), true);
			$this->record('composer.json', 'array', $composer);
		}

		// Check for composer.lock (for installed versions)
		if (is_file(ROOTPATH . 'composer.lock'))
		{
			$installed = 1;

			// Read in the lock file
			$composer = json_decode(file_get_contents(ROOTPATH . 'composer.lock'), true);

			// Save packages
			$packages = $composer['packages'];
			unset($composer['packages'], $composer['_readme']);

			// Save remaining values
			$this->record('composer.lock', 'array', $composer);

			// Parse packages
			$result = [];
			foreach ($packages as $package)
			{
				unset($package['dist'], $package['notification-url'], $package['license'], $package['authors'], $package['keywords']);
				$result[] = $package;
			}

			if (! empty($result))
			{
				$this->record('packages', 'array', $result);
			}
		}

		$this->record('installed', 'bool', $installed);
	}
}
