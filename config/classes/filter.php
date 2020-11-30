<?php 

class Filter {
    
    private $post, $validationErrors;
    public $debug;

    function __construct()
    {
        if(isset($_POST))
        {
            $this->post = $_POST;
        }
    }

	function validateFields($validateRules)
    {
        //Verwijder de POST variabele van de submitknop
        array_pop($this->post);

        foreach($this->post as $key => $value)
        {
            if(array_key_exists($key, $validateRules))
            {
                if(array_key_exists('type', $validateRules[$key]))
                {
                    $type = $validateRules[$key]['type'];

                    switch ($type) 
                    {
                        case 'email':
                        case 'int':
                        case 'url':
                            $value = $this->validateInput($value, $type);
                        break;
                    }
                    
                    if(!$value)
                    {
                        $this->validationErrors[$key] = "Ongeldige waarde voor: {$type}";
                        return false;
                    }

                }
                else
                {
                    $this->debug .= "Geen type meegegeven in de validatieregels, dit is verplicht!";
                    return false;
                }

                foreach($validateRules[$key] as $rule => $ruleValue)
                {
                    if($rule != 'type')
                    {
                        if(method_exists($this, $rule))
                        {
                            $value = $this->sanatizeInput($value, $type);
                            $this->$rule($key, $value, $ruleValue);
                        }
                    }
                }
            }
            else 
            {
                $this->debug .= "Geen validatieregels gevonden voor input met de name: {$key}";
            }
        }
        
        if(empty($this->validationErrors))
        {
            return true;
        }
    }

    function getValidationErrors()
    {
        return $this->validationErrors;
    }

    function verplicht($field, $value, $ruleValue)
    {
        if(empty($value) && $ruleValue == true)
        {
            $this->validationErrors[$field] = 'Dit veld is verplicht';
        }
    }

    function validateInput($input, $type)
    {

        $filter = false;
 
        switch ($type) {
            case 'email':
                $input = substr($input, 0, 255);
                $filter = FILTER_VALIDATE_EMAIL;
            break;
            case 'int':
                $filter = FILTER_VALIDATE_INT;
            break;
            case 'url':
                $filter = FILTER_VALIDATE_URL;
            break;
        }
 
        return filter_var($input, $filter);
    }

    function sanatizeInput($input, $type)
    {
        $flags = null;
 
        switch ($type) {
            case 'url':
                $filter = FILTER_SANITIZE_URL;
            break;
            case 'int':
                $filter = FILTER_SANITIZE_NUMBER_INT;
            break;
            case 'float':
                $filter = FILTER_SANITIZE_NUMBER_FLOAT;
                $flags = FILTER_FLAG_ALLOW_FRACTION | FILTER_FLAG_ALLOW_THOUSAND;
            break;
            case 'email':
                $input = substr($input, 0, 255);
                $filter = FILTER_SANITIZE_EMAIL;
            break;
            case 'string':
                $filter = FILTER_SANITIZE_STRING;
                $flags = FILTER_FLAG_NO_ENCODE_QUOTES;
            default:
                $filter = FILTER_SANITIZE_SPECIAL_CHARS;
            break;
 
        }
 
        return filter_var($input, $filter, $flags);
   }
    
}
?>