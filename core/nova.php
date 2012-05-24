<?php
namespace Core;

class Nova
{
	protected $load;
	
	public function __construct()
	{
		$this->load = Load::instance();
	}
	
	public function start()
	{
		$this->setErrorReporting();
		
		$query = $this->query();
		$controllerName = 'App\\Controller';
		$controllerArgs = array();
		$found = false;
		
		if (empty($query))
		{
			$controllerName .= '\\Index';
			
			if ($this->controllerExists($controllerName))
			{
				$found = true;
			}
		}
		else
		{
			for ($i = 0; $i < count($query); $i++)
			{
				if ($found)
				{
					$controllerArgs[] = $query[$i];
				}
				else
				{
					$queryPart = $this->sanitizedName($query[$i]);
					$controllerName .= '\\' . $queryPart;
					
					if ($this->controllerExists($controllerName))
					{
						$found = true;
					}
					else if (!$this->controllerDirectoryExists($controllerName))
					{
						break;
					}
				}
			}
			
			$indexControllerName = $controllerName . '\\Index';
			
			if (!$found && $this->controllerExists($indexControllerName))
			{
				$controllerName = $indexControllerName;
				$found = true;
			}
		}
		
		if ($found)
		{
			$controller = new $controllerName;
			
			$controller->args($controllerArgs);
			$controller->main();
		}
		else
		{
			Controller::show404();
		}
	}
	
	protected function setErrorReporting()
	{
		error_reporting(E_ALL);
		
		if (self::config('production'))
		{
			ini_set('display_errors', 0);
		}
		else
		{
			ini_set('display_errors', 1);
		}
	}
	
	protected function query()
	{
		$getKeys = array_keys($_GET);
		$queryString = count($getKeys) > 0 ? array_shift($getKeys) : '';
		$queryStringParts = explode('/', $queryString);
		$query = array();
		
		foreach ($queryStringParts as $queryStringPart)
		{
			if (!empty($queryStringPart))
			{
				$query[] = trim(strval($queryStringPart));
			}
		}
		
		return $query;
	}
	
	protected function sanitizedName($name)
	{
		$sanitizedName = preg_replace('/(^_*)|(\W)/', '', $name);
		
		return $sanitizedName;
	}
	
	protected function controllerExists($controllerName)
	{
		$filePath = $this->load->filePath($controllerName);
		$controllerExists = is_file($filePath) && is_readable($filePath);
		
		return $controllerExists;
	}
	
	protected function controllerDirectoryExists($controllerName)
	{
		$dirPath = $this->load->dirPath($controllerName);
		$controllerDirectoryExists = is_dir($dirPath);
		
		return $controllerDirectoryExists;
	}
	
	public static function config()
	{
		global $config;
		
		$path = func_get_args();
		$confVal = $config;
		
		while (!empty($path))
		{
			$nextKey = array_shift($path);
			
			if (isset($confVal[$nextKey]))
			{
				$confVal = $confVal[$nextKey];
			}
			else
			{
				$confVal = '';
				
				break;
			}
		}
		
		return $confVal;
	}
}
?>
