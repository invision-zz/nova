<?php
namespace Core;

class MySQL
{
	protected $host;
	protected $username;
	protected $password;
	protected $database;
	protected $prefix;
	protected $charset;
	
	protected $connected;
	protected $link;
	protected $res;
	protected $row;
	protected $rows;
	protected $numRows;
	protected $affRows;
	protected $insertId;
	protected $error;
	protected $errorNo;
	
	protected static $instance;
	
	protected function __construct($host, $username, $password, $database, $prefix, $charset)
	{
		$this->host = $host;
		$this->username = $username;
		$this->password = $password;
		$this->database = $database;
		$this->prefix = $prefix;
		$this->charset = $charset;
		
		$this->connected = false;
		$this->link = null;
		$this->res = null;
		$this->row = array();
		$this->rows = array();
		$this->numRows = 0;
		$this->affRows = 0;
		$this->insertId = 0;
		$this->error = '';
		$this->errorNo = 0;
	}
	
	public function __destruct()
	{
		$this->disconnect();
	}
	
	public static function instance()
	{
		if (!self::$instance instanceof self)
		{
			$host = Nova::config('db', 'host');
			$user = Nova::config('db', 'username');
			$pwd = Nova::config('db', 'password');
			$db = Nova::config('db', 'database');
			$pref = Nova::config('db', 'prefix');
			$chars = Nova::config('db', 'charset');
			
			self::$instance = new self($host, $user, $pwd, $db, $pref, $chars);
		}
		
		return self::$instance;
	}
	
	protected function connect()
	{
		$this->link = mysql_connect($this->host, $this->username, $this->password)
				and mysql_select_db($this->database)
				or exit(mysql_error());
		
		$this->connected = true;
	}
	
	protected function disconnect()
	{
		if ($this->connected)
		{
			mysql_close($this->link);
			$this->connected = false;
		}
	}
	
	public function sql($sql, $shadow = false)
	{
		if (!$this->connected)
		{
			$this->connect();
			$this->sql("SET NAMES `{$this->charset}`;", true);
		}
		
		$res = mysql_query($sql, $this->link);
		
		if (!$shadow)
		{
			unset($this->row);
			unset($this->rows);
			
			$this->res = $res;
			$this->numRows = @intval(mysql_num_rows($this->res));
			$this->affRows = @intval(mysql_affected_rows($this->link));
			$this->insertId = @intval(mysql_insert_id($this->link));
			$this->row = array();
			$this->rows = array();
			
			if ($this->numRows > 0)
			{
				while ($row = mysql_fetch_assoc($res))
				{
					$this->rows[] = $row;
				}
				
				$this->row = $this->rows[0];
			}
		}
		
		return $res;
	}
	
	public function __get($memberName)
	{
		return $this->$memberName;
	}
}
?>
