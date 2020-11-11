<?php
class MySQL
{
	private $link;

	function __construct()
	{
		$s =& Config::$DB;
		$this->link = new MySQLi(($s['persistent'] ? 'p:' : '').$s['hostname'], $s['loginname'], $s['password'], $s['database']);
	}

	function Query($query)
	{
		return $this->link->query($query);
	}

	function Run($query)
	{
		$this->link->real_query($query);
	}

	function Select($columns, $table, $key, $value)
	{
		return $this->GetRow('SELECT '.$columns.' FROM '.$table.' WHERE '.$key." = '".$value."'", false);
	}

	function Insert($table, $values)
	{
		foreach ($values as $key => $value)
		{
			$values[$key] = "'".$value."'";
		}

		$this->Run('INSERT INTO '.$table.' ('.implode(', ', array_keys($values)).') VALUES('.implode(', ', array_values($values)).')');
	}

	function Update($table, $key, $value, $updatevalues)
	{
		$update = Array();

		foreach ($updatevalues as $k => $v)
		{
			$update[] = $k.' = \''.$v.'\'';
		}

		$this->Run('UPDATE '.$table.' SET '.implode(', ', $update)." WHERE ".$key." = '".$value."' LIMIT 1");
	}

	function Delete($table, $key, $value)
	{
		$this->Run("DELETE FROM ".$table." WHERE ".$key." = '".$value."' LIMIT 1");
	}

	function Exists($table, $key, $value)
	{
		return ($this->Get("SELECT * FROM ".$table." WHERE ".$key." = '".$value."' LIMIT 1")->num_rows > 0);
	}

}
?>