<?php
class Database
{
	private $link;
	public  $error;
	
    function __construct()
    {  
        $s=& Config::$DB;

        $this->link = new MySQLi($s['hostname'], $s['username'], $s['password'], $s['database']);
        if ($this->link->connect_error) 
        {
            $this->error .= "Connection failed: " . $this->link->connect_error;
        }
    }

    function prepareQuery($query, $params = NULL) 
    {
        if($statement = $this->link->prepare($query)) 
        {
            if(IS_NULL($params))
            {
                if($statement->execute()) 
                {
                    return $statement;
                    $statement->close();
                } else 
                {
                    $this->error .= "Executing Error: ". $this->link->error;
                    return false;
                }
            }
            else 
            {
                if($statement->param_count == count($params)) 
                {

                    foreach ($params as $key => $value) 
                    {
                        $dataType[] = substr(gettype($value), 0, 1);
                    }
                    
                    $statement->bind_param(implode($dataType), ...$params);
                    if(!$statement->execute())
                    {
                        $this->error .= $this->link->error;
                       
                        // return $statement->num_rows();
                    } 
                    else {
                        echo 1;

                        return $statement;
                        $statement->close();  

                    }
                }
                else 
                {
                    $this->error .= "Parameter count error: does not match with array.";
                    return false;
                }
            }
        }
        else 
        {
            $this->error .= "Query Error: ". $this->link->error;
            return false;
        }
    }

    function SelectRow($query, $params = NULL)
    {
        $result = $this->Select($query.' LIMIT 1', $params);
        return ($result->num_rows > 0 ? $result->fetch_object() : false);
    }

    function Select($query, $params = NULL)
    {
        if($statement = $this->prepareQuery($query, $params))
        {
            $result = $statement->get_result();
            return ($result->num_rows > 0 ? $result : false);
        }
            return false;
    }

    function Update($query, $params = NULL)
    {
        return ($this->prepareQuery($query, $params) ? true : false);
    }

    function Insert($query, $params = NULL)
    {
        return ($this->prepareQuery($query, $params) ? true : false);
    }

    function Delete($query, $params = NULL)
    {
        return ($this->prepareQuery($query, $params) ? true : false);
    }

    function Exists($query, $params = NULL)
	{
        return ($this->prepareQuery($query, $params)->get_result()->num_rows > 0 ? true : false);
    }
    
    function InsertId()
    {
        return $this->link->insert_id;
    }
}
?>