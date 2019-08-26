<?php namespace Tatter\Agents\Agents;

use Tatter\Agents\BaseAgent;
use Tatter\Agents\Interfaces\AgentInterface;
use Tatter\Agents\Models\AgentModel;

class AuthAgent extends BaseAgent implements AgentInterface
{
	// Attributes for Tatter\Handlers
	public $attributes = [
		'name'       => 'Auth',
		'uid'        => 'auth',
		'icon'       => 'fas fa-user-shield',
		'summary'    => 'Collect user, group, and login information',
	];
	
	// Looks for the auth models and loads their info
	public function check()
	{
		$flag = false;
		
		// Check for a user model
		if (class_exists('App\Models\UserModel'))
			$users = new \App\Models\UserModel();
		elseif (class_exists('Myth\Auth\Models\UserModel'))
			$users = new \Myth\Auth\Models\UserModel();
		
		if ($users)
		{
			$flag = true;
			$ids = $users->findColumn('id');
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
		}
		
		// Check for a group model
		if (class_exists('App\Models\GroupModel'))
			$groups = new \App\Models\GroupModel();
		elseif (class_exists('Myth\Auth\Authorization\GroupModel'))
			$groups = new \Myth\Auth\Authorization\GroupModel();

		if ($groups)
		{
			$flag = true;
			$this->record('groups', 'array', $groups->findColumn('name'));
		}
		
		// If no models were discovered then commit suicide so as not to keep running
		if (! $flag)
		{
			$agents = new AgentModel();
			$agents->where('uid', $this->uid)->delete();
		}
		
		// Check for a login model
		if (class_exists('App\Models\LoginModel'))
			$logins = new \App\Models\LoginModel();
		elseif (class_exists('Myth\Auth\Models\LoginModel'))
			$logins = new \Myth\Auth\Models\LoginModel();

		if ($logins)
		{
			$flag = true;
			
			// Get the last five logins
			$content = $logins->builder()->orderBy('id', 'desc')->limit(5)->get()->getResultArray();
			$this->record('logins', 'array', $content);
		}
		
		// If no models were discovered then commit suicide so as not to keep running
		if (! $flag)
		{
			$agents = new AgentModel();
			$agents->where('uid', $this->uid)->delete();
		}
		
		return;
	}
}
