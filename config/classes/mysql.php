<?php
class Database {	
	
    private $connection;
	public  $error;

    function __construct()
    {  
        $s=& Config::$DB;
        try 
        {
            $this->connection = new MySQLi($s['hostname'], $s['username'], $s['password'], $s['database']);
            
            if ($this->connection->connect_error) 
            {
                throw new Exception("Could not connect to database: ". $this->link->connect_error);   
            }		
        }
        catch(Exception $e)
        {
            throw new Exception($e->getMessage());   
        }		
    }

    // Insert a row/s in a Database Table
    function Insert($query, $params = []) 
    {
        try 
        {
            $stmt = $this->executeStatement($query, $params);
            $stmt->close();
            return $this->connection->insert_id;
        } 
        catch(Exception $e)
        {
            throw New Exception($e->getMessage());
        }
	
        return false;
    }

    // Select a row/s in a Database Table
    function Select($query, $params = []) 
    {
        try
        {
            $stmt = $this->executeStatement( $query , $params );
		
            $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);				
            $stmt->close();
		
            return $result;
		
        }
        catch(Exception $e)
        {
            throw New Exception($e->getMessage());
        }
	
        return false;
    }

    // Update a row/s in a Database Table
    function Update($query, $params = []) 
    {
        try 
        {
            $this->executeStatement( $query , $params )->close();
        } 
        catch(Exception $e) 
        {
            throw New Exception( $e->getMessage() );
        }
	
        return false;
    }		

    // Remove a row/s in a Database Table
    function Delete($query, $params = [])
    {
        try
        {
            $this->executeStatement( $query , $params )->close();
        } 
        catch(Exception $e)
        {
            throw New Exception( $e->getMessage() );
        }
	
        return false;
    }		

    // execute statement
    function executeStatement($query, $params = [])
    {
        try 
        {
            $stmt = $this->connection->prepare($query);
		
            if($stmt === false) 
            {
                throw New Exception("Unable to do prepared statement: " . $query);
            }

            if($params)
            {
                
                foreach ($params as $key => $value) 
                {
                    $dataType[] = substr(gettype($value), 0, 1);
                }
                $stmt->bind_param(implode($dataType), ...$params);
            }
		
           $stmt->execute();
		
           return $stmt;
		
        }
        catch(Exception $e)
        {
            throw New Exception( $e->getMessage() );
        }
	
    }
    
    function InsertId()
    {
        return $this->connection->insert_id;
    }
		
}
?>