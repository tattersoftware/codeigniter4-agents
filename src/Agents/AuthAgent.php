<?php namespace Tatter\Agents\Agents;

use Tatter\Agents\BaseAgent;
use Tatter\Agents\Models\AgentModel;

class AuthAgent extends BaseAgent
{
	/**
	 * Attributes for Tatter\Handlers
	 *
	 * @var array<string, string>  Must include keys: name, uid, class, icon, summary
	 */
	protected $attributes = [
		'name'    => 'Auth',
		'uid'     => 'auth',
		'icon'    => 'fas fa-user-shield',
		'summary' => 'Collect user, group, and login information',
	];

	/**
	 * Looks for the Auth models and loads their info.
	 *
	 * @return void
	 */
	public function check(): void
	{
		$flag = false;
		
		// Check for a User model
		if ($users = model('UserModel'))
		{
			$ids = $users->findColumn('id') ?? [];
			$this->record('userCount', 'int', count($ids));
			
			// If there were users then fetch the most recent
			if ($ids)
			{
				$user = $users->find(end($ids));

				if (! is_array($user))
				{
					$user = $user->toArray();
				}
				$this->record('latestUser', 'array', $user);				
			}

			$flag = true;
		}
		
		// Check for a Group model
		$groups = model('GroupModel') ?? model('Myth\Auth\Authorization\GroupModel');
		if ($groups)
		{
			$this->record('groups', 'array', $groups->findColumn('name') ?? []);

			$flag = true;
		}

		// Check for a Login model
		if ($logins = model('LoginModel'))
		{
			// Get the last five logins
			$content = $logins->builder()->orderBy('id', 'desc')->limit(5)->get()->getResultArray();
			$this->record('logins', 'array', $content);

			$flag = true;
		}

		// If no models were discovered then disable this Agent so as not to keep running
		if (! $flag)
		{
			model(AgentModel::class)->where('uid', $this->attributes['uid'])->delete();
		}

		return;
	}
}
