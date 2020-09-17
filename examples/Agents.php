<?php namespace Config;

/***
*
* This file contains example values to alter default library behavior.
* Recommended usage:
*	1. Copy the file to app/Config/
*	2. Change any values
*	3. Remove any lines to fallback to defaults
*
***/

class Agents extends \Tatter\Agents\Config\Agents
{
	// whether to continue instead of throwing exceptions
	public $silent = true;
	
	// How many minutes a check lasts before it is considered "stale"
	public $ttl = 15;
	
	// the session variable to check for a logged-in user ID
	public $userSource = 'logged_in';
}
