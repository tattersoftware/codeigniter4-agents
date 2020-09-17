<?php namespace Tatter\Agents\Exceptions;

use CodeIgniter\Exceptions\ExceptionInterface;
use CodeIgniter\Exceptions\FrameworkException;

class AgentsException extends \RuntimeException implements ExceptionInterface
{
	public static function forNoHandlers()
	{
		return new static(lang('Agents.noHandlers'));
	}

	public static function forUnserializable($class)
	{
		return new static(lang('Agents.unserializable', [$class]));
	}
}
