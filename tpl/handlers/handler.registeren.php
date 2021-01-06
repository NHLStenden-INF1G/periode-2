<?php
//Kijk of knop ingedrukt is

$user->Redirect(true);
$this->Set("regError", "");

if(isset($_POST['regSubmit']))
{

    //Regregels voor Filter Class
    $regRegels = array(   
        'voorNaam' => array(
            'type' => 'text',
            'verplicht' => true
        ),
        'achterNaam' => array(
            'type' => 'text',
            'verplicht' => true
        ),
        'regEmail' => array(
            'type' => 'email',
            'verplicht' => true
        ),
        'regPass1' => array(
            'type' => 'text',
            'verplicht' => true
        ),
        'regPass2' => array(
            'type' => 'text',
            'verplicht' => true
        )
     );

    //Controleer of alles correct ingevoerd is
    if($filter->validateFields($regRegels))
    {

        $voorNaam = $_POST['voorNaam'];
        $achterNaam = $_POST['achterNaam'];
        $regEmail = $_POST['regEmail'];
        $regPass1 = $_POST['regPass1'];
        $regPass2 = $_POST['regPass2'];

        $regAntwoord = $user->Register($voorNaam, $achterNaam, $regEmail, $regPass1, $regPass2);
        
        if($regAntwoord == 1)
        {
            $this->Set("regError", "Deze gebruiker bestaat al!");
        }

        else if($regAntwoord == 2)
        {
            $this->Set("regError", "Wachtwoorden komen niet overeen!");
        }
        else if($regAntwoord == 3)
        {
            $this->Set("regError", "Geen NHL Stenden mail!");
        }
        else {
            $loginAntwoord = $user->Login($regEmail, $regPass2);
            $core->Redirect(Config::$loginStartpage);
        }
    
    } else {
        $this->Set("regError", $filter->validateFields($regRegels));
    }
}

?>