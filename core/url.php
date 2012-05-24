<?php
namespace Core;

class Url
{
	protected static $instance;
	
	protected function __construct()
	{}
	
	public static function instance()
	{
		if (!self::$instance instanceof self)
		{
			self::$instance = new self();
		}
		
		return self::$instance;
	}
	
	public function site($subPath = '')
	{
		$siteUrl = Nova::config('url', 'site');
		$path = $siteUrl . $subPath;
		
		return $path;
	}
	
	public function asset($subPath = '')
	{
		$assetUrl = Nova::config('url', 'assets');
		$path = $this->site($assetUrl . $subPath);
		
		return $path;
	}
	
	public function img($name)
	{
		$url = Nova::config('url', 'img');
		$path = $this->asset($url . $name);
		
		return $path;
	}

	public function css($name)
	{
		$url = Nova::config('url', 'css');
		$path = $this->asset($url . $name . '.css');
		
		return $path;
	}

	public function js($name)
	{
		$url = Nova::config('url', 'js');
		$path = $this->asset($url . $name . '.js');
		
		return $path;
	}
}
?>
