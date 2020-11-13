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
				PDO::ATTR_PERSISTENT => true,  
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
		
		function pdoDebug(){
			
			$this->stmt->debugDumpParams();
			$output = ob_get_contents();
			$output = ob_get_clean();

			$this->debug .= $output;
		}

		function numRows(){
			//Get number of rows from most recent SQL Query
			return $this->stmt->rowCount();
		}

		function lastInsertId(){  
			//Get last inserted ID from latest SQL Query
			return $this->link->lastInsertId();  
		}  

		function Prepare($query){  
			$this->stmt = $this->link->prepare($query);  
		}  

		function Bind($param, $value, $type = null){  
			if(is_null($type)){
				switch ($type) {  
				case 'int':  
					$type = PDO::PARAM_INT;  
				break;  
				case 'bool':  
					$type = PDO::PARAM_BOOL;  
				break;  
				case 'null':  
					$type = PDO::PARAM_NULL;  
				break;  
				default:  
					$type = PDO::PARAM_STR;  
				}  
			}

			$this->stmt->bindValue($param, $value, $type);  
		}

		function Select($query, $parameters = null, $values = null){  
			

			$parameters = array_map('trim', explode(',', $parameters));
			$values = array_map('trim', explode(',', $values));

			$this->Prepare($query);

			if(!is_null($parameters) && !is_null($values)){
				if(count($parameters) == count($values)){	
					foreach ($parameters as $key => $value) {
						$this->Bind($value, $values[$key]);
					}
				}
				else {
					$this->error .= "Parameter count does not match value count";
				}
			}

			$this->pdoDebug();
			$this->stmt->execute();

			if($this->numRows() == 1){
				return $this->stmt->fetch(PDO::FETCH_ASSOC);
			}
			else if($this->numRows() > 1){
				return $this->stmt->fetchAll(PDO::FETCH_ASSOC);
			}
			else {
				return false;
			}
		}

		function Update($query, $parameters = null, $values = null){  
			
			$parameters = array_map('trim', explode(',', $parameters));
			$values = array_map('trim', explode(',', $values));

			$this->Prepare($query);

			if(!is_null($parameters) && !is_null($values)){
				if(count($parameters) == count($values)){	
					foreach ($parameters as $key => $value) {
						$this->Bind($value, $values[$key]);
					}
				}
				else {
					$this->error .= "Parameter count does not match value count";
				}
			}

			$this->pdoDebug();
			$this->stmt->execute();
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

			$this->pdoDebug();
			$this->stmt->execute();

		}  
		
		function Delete($table, $parameter, $value){ 
			$pdoParamter = ":".$parameter;

			$query = "DELETE FROM {$table} WHERE {$parameter} = {$pdoParamter}";
			$this->Prepare($query);
			$this->Bind($pdoParamter, $value);
			$this->pdoDebug();
			$this->stmt->execute();
		}  

}
?>