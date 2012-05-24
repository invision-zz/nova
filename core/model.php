<?php
namespace Core;

abstract class Model
{
	protected $db;
	
	public function __construct()
	{
		$this->db = MySQL::instance();
	}
	
	public function escape($string)
	{
		$escaped = addslashes($string);
		
		return $escaped;
	}
	
	public function purify($string)
	{
		$purified = trim($string);
		$purified = strip_tags($purified);
		$purified = preg_replace("/[\r\n]/", '', $purified);
		$purified = $this->escape($purified);
		
		return $purified;
	}
	
	public function removeIllegalChars($string)
	{
		return iconv('UTF-8', 'UTF-8//IGNORE', $string);
	}
}
?>
