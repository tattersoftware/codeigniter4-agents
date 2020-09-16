<?php namespace Tatter\Agents\Agents;

use Tatter\Agents\BaseAgent;
use Tatter\Agents\Interfaces\AgentInterface;

class LogAgent extends BaseAgent implements AgentInterface
{
	// Attributes for Tatter\Handlers
	public $attributes = [
		'name'       => 'Log',
		'uid'        => 'log',
		'icon'       => 'fas fa-clipboard',
		'summary'    => 'Collect recent log excerpts',
	];
	
	public function check(): void
	{
		// Verify the path
		$path = WRITEPATH . 'logs/';
		$this->record('path', 'string', $path);
		
		// Get all the files from the directory
		helper('filesystem');
		$files = get_filenames($path);
		$this->record('count', 'int', count($files));
		
		// Get the newest file
		rsort($files);
		$file = reset($files);
		if (empty($file))
			return;
		$this->record('file', 'string', $file);
		
		// Read the file in by lines and trim to the last 30
		$content = file($path . $file);
		$content = array_slice($content, -30);
		if (empty($content))
			return;		
		$this->record('recent', 'array', $content);
	}
}
