<?php
//Kijk of knop ingedrukt is
$user->Redirect(true);
$this->Set("loginError", "");

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
            $this->Set("loginError", "Deze gebruiker bestaat niet!");
        }

        else if($loginAntwoord == 2)
        {
            $this->Set("loginError", "Verkeerd wachtwoord!");
        }
        else {
            $core->Redirect(Config::$loginStartpage);
        }


    }
}

?>