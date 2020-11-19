<?php
class Database
{
	private $link;
	public $error, $debug;
	public $stmt; 


	function __construct(){  
		// Set DSN  
			$s=& Config::$DB;

			$dsn = 'mysql:host=' . $s['hostname'] . ';dbname=' . $s['database'];  
			// Set options  
			$options = array(  
				PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
				PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
				PDO::ATTR_EMULATE_PREPARES   => false
			);  
			// Create a new PDO instanace  
			try{  
				$this->link = new PDO($dsn, $s['username'], $s['password'], $options); 
			}  
			// Catch any errors  
			catch(PDOException $e){  
				$this->error .= $e->getMessage();  
			}  
		}  
	
	function Close(){
		$this->link = null;
		$this->stmt = null;
	}

	function Debug(){
		
		if(Config::$debug_DB) {
			$this->stmt->debugDumpParams();
			$output = ob_get_contents();
			$output = ob_get_clean();
	
			$this->debug .= $output;	
		}
	}

	function numRows(){
		//Get number of rows from most recent SQL Query
		return $this->stmt->rowCount();
		$this->Close();
	}

	function lastInsertId(){  
		//Get last inserted ID from latest SQL Query
		return $this->link->lastInsertId(); 
		$this->Close(); 
	}  

	function Bind($param, $value, $type = null){  
			switch ($type) 
			{  
				case 'i':  
					$type = PDO::PARAM_INT;  
				break;  
				case 'b':  
					$type = PDO::PARAM_BOOL;  
				break;  
				case 'n':  
					$type = PDO::PARAM_NULL;  
				break;  
				case 's':  
					$type = PDO::PARAM_STR;  
				break;  
				default:  
					$type = PDO::PARAM_STR;  
			}  
		
		$this->stmt->bindValue($param, $value, $type);  
	}

	function Select($query, $parameters = null, $values = null){  
		global $core;

		$parameters = $core->listArray($parameters);
		$values 	= $core->listArray($values);

		if($this->stmt = $this->link->prepare($query)){
			$this->Debug();

			if(count($parameters) == count($values)){	
				foreach ($parameters as $key => $value){
					$this->Bind($value, $values[$key]);
				}
			}
			else {
				$this->error .= "Parameter count does not match value count";
			}

			if(!$this->stmt->execute()) {
				$this->error .= "Error executing prepared query";
			}
		}


		return $this->stmt;

		$this->Close();

	}

	function Update($query, $parameters, $values){  
		global $core; 

		$parameters = $core->listArray($parameters);
		$values 	= $core->listArray($values);

		if($this->stmt = $this->link->prepare($query)){
			$this->Debug();

			if(count($parameters) == count($values)){	
				foreach ($parameters as $key => $value){
					$this->Bind($value, $values[$key]);
				}
			}
			else {
				$this->error .= "Parameter count does not match value count";
			}

			if(!$this->stmt->execute()) {
				$this->error .= "Error executing prepared query";
			}
		}

		$this->Close();
	}

	function Insert($table, $parameters, $values){ 
		$parametersArray = array_map('trim', explode(',', $parameters));
		$values = array_map('trim', explode(',', $values));

		$PDOParamters = preg_filter('/^(.*?)$/', ':$0', $parametersArray);
		$PDOParamters = implode(', ', $PDOParamters);

		$query = "INSERT INTO {$table} ({$parameters}) VALUES ({$PDOParamters})";
		
		$this->Prepare($query);

		if(count($parametersArray) == count($values)){	
			foreach ($parametersArray as $key => $value) {					
				$this->Bind($value, $values[$key]);
			}
		}
		else {
			$this->error .= "Insertfunction error: Parameter count does not match value count";
		}

		$this->Debug();
		$this->stmt->execute();

		$this->Close();
	}  
	
	function Delete($table, $parameter, $value){ 
		$sqlParameter = ":".$parameter;

		$query = "DELETE FROM {$table} WHERE {$parameter} = {$sqlParameter}";
		$this->Prepare($query);
		$this->Bind($sqlParameter, $value);
		$this->Debug();
		$this->stmt->execute();

		$this->Close();
	}  

}
?>