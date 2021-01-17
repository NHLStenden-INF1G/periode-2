<?php 
class Language {
    
    public $langInput = [];
    public $chosenLanguage;

    function __construct()
    {
        global $TPL, $core;

        switch (@$_COOKIE['lang']) {
            case 'en':
                $this->chosenLanguage = 'en';
                require("language/lang.en.php");
                break;
            case 'nl':
            default:
                $this->chosenLanguage = 'nl';
                require("language/lang.nl.php");
                break;
        }

        $core->SetCookie('lang', $this->chosenLanguage);

        foreach($this->langInput as $key => $value)
        {   
            $TPL->Set($key, $value);
        }

        switch ($this->chosenLanguage) {
            case 'en':
                $TPL->Set('taalKnop', '<form method="POST"><button type="submit" name="taalKnop"><span><i class="fa fa-language fa-lg fa-fw" aria-hidden="true"></i> NL</span></button></form>');
                break;
            case 'nl':
            default:
                $TPL->Set('taalKnop', '<form method="POST"><button type="submit" name="taalKnop"><span><i class="fa fa-language fa-lg fa-fw" aria-hidden="true"></i> EN</span></button></form>');
                break;
        }
        
        if(isset($_POST['taalKnop'])) {
            $this->changeLanguage();
            header( "Refresh:0; url="."http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]", true, 303);
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