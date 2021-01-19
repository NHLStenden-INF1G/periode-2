<?php
//Kijk of knop ingedrukt is
$user->Redirect(true);

$this->Set("loginError", "");
$this->Set("extraCSS", '<link rel="stylesheet" href="'.$this->Get("assetsFolder").'/css/page/canvas_special.css">');
$this->Set("pageTitle", $this->Get("LOGIN_INLOGGEN"));

if(isset($_POST['loginSubmit']))
{
    //Loginregels voor Filter Class
    $loginRegels = array(   
        'loginEmail' => array(
            'type' => 'email',
            'verplicht' => true
        ),
        'loginPassword' => array(
            'type' => 'text',
            'verplicht' => true
        )
     );

    //Controleer of alles correct ingevoerd is
    if($filter->validateFields($loginRegels))
    {
        $loginEmail = $_POST['loginEmail'];
        $loginPassword = $_POST['loginPassword'];

        $loginAntwoord = $user->Login($loginEmail, $loginPassword);

        if($loginAntwoord == 1)
        {
            $this->Set("loginError", $this->Get("LOGIN_VERKEERDE_EMAIL"));
        }

        else if($loginAntwoord == 2)
        {
            $this->Set("loginError", $this->Get("LOGIN_VERKEERDE_WACHTWOORD"));
        }
        else 
        {
            $core->Redirect(Config::$loginStartpage);
        }
    }
}

?>