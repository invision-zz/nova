<?php
namespace Core;

class Load
{
	protected static $instance;
	
	protected function __construct()
	{
		spl_autoload_register(array($this, 'load'));
	}
	
	public static function instance()
	{
		if (!self::$instance instanceof self)
		{
			self::$instance = new self();
		}
		
		return self::$instance;
	}
	
	protected function load($className)
	{
		$filePath = $this->filePath($className);
		
		if (file_exists($filePath) && is_readable($filePath))
		{
			require_once $filePath;
		}
	}
	
	public function filePath($className)
	{
		$className = strtolower($className);
		$classParts = explode('\\', $className);
		$filePart = array_pop($classParts);
		$namespaceParts = $classParts;
		$namespacePath = implode('/', $namespaceParts);
		$namespacePath .= empty($namespacePath) ? '' : '/';
		$filePath = ROOT . $namespacePath . $filePart . '.php';
		
		return $filePath;
	}
	
	public function dirPath($className)
	{
		$className = strtolower($className);
		$classParts = explode('\\', $className);
		$filePart = array_pop($classParts);
		$namespaceParts = $classParts;
		$namespacePath = implode('/', $namespaceParts);
		$namespacePath .= empty($namespacePath) ? '' : '/';
		
		return $namespacePath;
	}
}
?>
