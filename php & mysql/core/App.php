<?php

/**
 * 
 */
class App
{
	protected static $data = [];
	public static function bind($key, $value)
	{
		static::$data[$key] = $value;
	}

	public static function get($key)
	{
		return self::$data[$key];
	}
}