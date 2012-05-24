<?php
namespace Core;

class View
{
	protected static $instance;
	
	protected $url;
	
	protected function __construct()
	{
		$this->url = Url::instance();
	}
	
	public static function instance()
	{
		if (!self::$instance instanceof self)
		{
			self::$instance = new self;
		}
		
		return self::$instance;
	}
	
	public function display($name, $data = array(), $tabs = 0)
	{
		echo $this->load($name, $data, $tabs);
	}
	
	public function load($name, $data = array(), $tabs = 0)
	{
		$view = $this->includeViewFile($name, $data);
		$view = $this->tab($view, $tabs);
		
		return $view;
	}
	
	protected function includeViewFile($__name, $__data)
	{
		$__res = null;
		
		if (is_array($__data))
		{
			extract($__data);
		}
		
		$__viewFilePath = $this->viewFilePath($__name);
		
		if (is_file($__viewFilePath) && is_readable($__viewFilePath))
		{
			ob_start();
			include $__viewFilePath;
			$__res = preg_replace("/((\r\n)|(\n)|(\r))$/", '', ob_get_contents());
			ob_end_clean();
		}
		
		return $__res;
	}
	
	protected function viewFilePath($name)
	{
		if (!preg_match('/\./', $name))
		{
			$name .= '.php';
		}
		
		$viewFilePath = APP . 'view/' . $name;
		
		return $viewFilePath;
	}
	
	protected function tab($string, $tabsCount = 1)
	{
		$tab = "\t";
		$tabs = str_repeat($tab, $tabsCount);
		$result = preg_replace("/(^|\n)/", "$1${tabs}", $string);
		
		if ($tabsCount > 0)
		{
			$result .= PHP_EOL;
		}
		
		return $result;
	}
}
?>
