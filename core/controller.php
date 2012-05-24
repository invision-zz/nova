<?php
namespace Core;

abstract class Controller
{
	protected $args;
	protected $view;
	protected $url;
	
	public function __construct()
	{
		$this->args = array();
		$this->view = View::instance();
		$this->url = Url::instance();
	}
	
	public function main()
	{}
	
	public function args($args = null)
	{
		if (is_array($args))
		{
			$this->args = $args;
		}
		
		return $this->args;
	}
	
	public function arg($index, $default = null)
	{
		if (array_key_exists($index, $this->args))
		{
			$arg = $this->args[$index];
		}
		else
		{
			$arg = $default;
		}
		
		return $arg;
	}
	
	public function post($index, $default = '')
	{
		$value = $default;
		
		if (array_key_exists($index, $_POST))
		{
			$value = $this->filterMagicQuotes($_POST[$index]);
		}
		
		return $value;
	}
	
	public function sess($index, $default = '')
	{
		$value = $default;
		
		if (array_key_exists($index, $_SESSION))
		{
			$value = $_SESSION[$index];
		}
		
		return $value;
	}
	
	public function setSess($index, $value)
	{
		if ($value !== null)
		{
			$_SESSION[$index] = $value;
		}
		else
		{
			unset($_SESSION[$index]);
		}
	}
	
	public function cook($index, $default = '')
	{
		$value = $default;
		
		if (array_key_exists($index, $_COOKIE))
		{
			$value = $this->filterMagicQuotes($_COOKIE[$index]);
		}
		
		return $value;
	}
	
	public function setCook($index, $value)
	{
		$expire = $value === null
				? time() - 1
				: time() + Nova::config('cookie', 'time');
		
		$path = Nova::config('cookie', 'path');
		
		setcookie($index, $value, $expire, $path);
	}
	
	public function filterMagicQuotes($string = '')
	{
		if (get_magic_quotes_gpc())
		{
			$filtered = stripslashes($string);
		}
		else
		{
			$filtered = $string;
		}
		
		return $filtered;
	}
	
	public static function pathTo($subpath = '')
	{
		return Url::instance()->site($subpath);
	}
	
	public static function redirectTo()
	{
		header('Location: ' . static::pathTo());
	}
	
	public static function show404()
	{
		$override404 = Nova::config('override404');
		
		if (empty($override404))
		{
			include CORE . '404.html';
		}
		else
		{
			View::instance()->display($override404);
		}
	}
}
?>
