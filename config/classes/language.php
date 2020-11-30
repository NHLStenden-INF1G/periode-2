<?php 

class Language {
    
    public $langInput = [];

    function __construct()
    {
        global $TPL, $core;


        switch (@$_COOKIE['lang']) {
            case 'en':
                $core->SetCookie('lang', 'en');
                require("language/lang.en.php");
                break;
            case 'nl':
            default:
                $core->SetCookie('lang', 'nl');
                require("language/lang.nl.php");
                break;
        }

        foreach($this->langInput as $key => $value)
        {   
            $TPL->Set($key, $value);
        }
    }

    function changeLanguage()
    {
        global $core;
        
        switch ($_COOKIE['lang']) 
        {
            case 'nl':
                $core->SetCookie('lang', 'en');
            break;
            case 'en':
                $core->SetCookie('lang', 'nl');
            break;
        }
    }
    
}
?>