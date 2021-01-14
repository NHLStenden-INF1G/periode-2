<?php 
    $this->Set("pageTitle", $this->Get("ADMIN_GEBRUIKERBEHEER"));

    $this->Set("regError", "");

    //Handler
    if(isset($_POST['submitInvoegen'])){
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

            $voorNaam = $filter->sanatizeInput($_POST['voorNaam'], 'string');
            $achterNaam = $filter->sanatizeInput($_POST['achterNaam'], 'string');
            $regEmail = $filter->sanatizeInput($_POST['regEmail'], 'email');
            $regPass1 =  $filter->sanatizeInput($_POST['regPass1'], 'string');
            $regPass2 =  $filter->sanatizeInput($_POST['regPass2'], 'string');
            $regSelect = $filter->sanatizeInput($_POST['levelSelect'], 'int');

            $regAntwoord = $user->Register($voorNaam, $achterNaam, $regEmail, $regPass1, $regPass2, $regSelect);
            
            if($regAntwoord == 1)
            {
                $this->Set("regError", $this->Get("GEBRUIKERBEHEER_BESTAAT"));
            }

            else if($regAntwoord == 2)
            {
                $this->Set("regError", $this->Get("GEBRUIKERBEHEER_WACHTWOORDEN_OVEREEN"));
            }
            else if($regAntwoord == 3)
            {
                $this->Set("regError", $this->Get("GEBRUIKERBEHEER_INVOEGEN_VERKEERDE_EMAIL"));
            }
            else {
                header("Refresh:0");
            }
        
        } else {
            $this->Set("regError", $filter->validateFields($regRegels).'<br />');
        }
    }



    $this->Set("updateError", "");

    //Handler
    if(isset($_POST['submitEdit'])){
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

            $voorNaam = $filter->sanatizeInput($_POST['voorNaam'], 'string');
            $achterNaam = $filter->sanatizeInput($_POST['achterNaam'], 'string');
            $regEmail = $filter->sanatizeInput($_POST['regEmail'], 'email');
            $regPass1 =  $filter->sanatizeInput($_POST['regPass1'], 'string');
            $regPass2 =  $filter->sanatizeInput($_POST['regPass2'], 'string');
            $regSelect = $filter->sanatizeInput($_POST['levelSelect'], 'int');
            $gebruikerID = $filter->sanatizeInput($_POST['gebruikerID'], 'int');

            $regAntwoord = $user->Edit($voorNaam, $achterNaam, $regEmail, $regPass1, $regPass2, $regSelect, $gebruikerID);
 
            if($regAntwoord == 2)
            {
                $this->Set("updateError", $this->Get("GEBRUIKERBEHEER_WACHTWOORDEN_OVEREEN"));
            }
            else if($regAntwoord == 3)
            {
                $this->Set("updateError",  $this->Get("GEBRUIKERBEHEER_INVOEGEN_VERKEERDE_EMAIL"));
            }
            else {
                header("Refresh:0");
            }
        
        } else {
            $this->Set("updateError", $filter->validateFields($regRegels).'<br />');
        }
    }

    //Geen goede rank
    if($user->rank != 3){
        $core->Redirect("/admin");
    }

?>

<div class="spotlightVideo">
        <div class="adminView">
            <div class="adminViewRand"></div>
            <div class="adminContent"></div>
            <div class="adminOptions">
            <div class="sectionTitle">{ADMIN_GEBRUIKERBEHEER}</div>
                <div class="sectionTitle link" data-link="/admin/gebruikerbeheer/invoegen">> {BEHEER_NAV_INVOEGEN}</div>
                <div class="sectionTitle link" data-link="/admin/gebruikerbeheer">> {BEHEER_NAV_TERUG_OVERZICHT}</div>
                <div class="sectionTitle link" data-link="/admin/start">> {BEHEER_NAV_TERUG_HOOFDMENU}</div>

            </div>
            <div class="adminTableView">
        <?php 

        //Laat de weergave pagina zien
        if(!isset($_GET['Path_2']) && !isset($_GET['Path_3']))
        {
            $gebuikerResult = $DB->Select("SELECT * FROM gebruiker WHERE gebruiker_id != ? AND level < ? ORDER BY level ASC ", [
                $user->id, $user->rank]); //Haalt alle docenten op

            echo '<div class="sectionTitle">{BEHEER_OVERZICHT}</div><table>
                    <thead>
                        <tr>
                            <th>Level</th>
                            <th>{GEBRUIKERBEHEER_NAAM}</th>
                            <th>Email</th>
                            <th><i class="fa fa-pencil-square-o" aria-hidden="true"></i></th>
                            <th><i class="fa fa-times" aria-hidden="true"></i></th>
                        </tr>
                    </thead>
                    <tbody>';

            foreach($gebuikerResult as $key => $value) 
            {
                echo "<tr>";
                    echo "<td>{$user->userLevel($value['gebruiker_id'])}</td>";
                    echo "<td>".$value['voornaam']." ".$value['achternaam']. "</td>";
                    echo "<td>".$value['email']."</td>";
                    echo "<td class='link' data-link='/admin/gebruikerbeheer/edit/".$value['gebruiker_id']."'>
                    <i class='fa fa-pencil-square-o' aria-hidden='true'></i>
                    </td>";
                    echo "<td class='link' data-link='/admin/gebruikerbeheer/verwijder/".$value['gebruiker_id']."'>
                            <i class='fa fa-times' aria-hidden='true'></i>
                        </td>";
                echo "</tr>";
            }

            echo "</tbody>
            </table>";

        }
        //Laat de invoegen pagina zien
        else if(isset($_GET['Path_2']) && $_GET['Path_2'] == 'invoegen')
        {
            echo '<form method="post">
                {regError}
                <label>{GEBRUIKERBEHEER_INVOEGEN_VOORNAAM}: <input type="text" name="voorNaam" placeholder="{GEBRUIKERBEHEER_INVOEGEN_VOORNAAM}" required></label><br />
                <label>{GEBRUIKERBEHEER_INVOEGEN_ACHTERNAAM}: <input type="text" name="achterNaam" placeholder="{GEBRUIKERBEHEER_INVOEGEN_ACHTERNAAM}" required></label><br />
                <label>Email: <input type="email" name="regEmail" placeholder="Emailadres" required></label><br />
                <label>{PROFIEL_AANPASSEN_WACHTWOORD}: <input type="password" name="regPass1" placeholder="{PROFIEL_AANPASSEN_WACHTWOORD}" required></label><br />
                <label>{GEBRUIKERBEHEER_INVOEGEN_HERHAAL_WACHTWOORD}: <input type="password" name="regPass2" placeholder="{GEBRUIKERBEHEER_INVOEGEN_HERHAAL_WACHTWOORD}" required></label><br />
                <label>Level: <select name="levelSelect"><option value="1">Student</option><option value="2">Docent</option></select><br />
                <button type="submit" name="submitInvoegen" required>{BEHEER_NAV_INVOEGEN}</button>
            </form>';
        }

        //Laat de delete pagina zien
        else if(isset($_GET['Path_2']) && $_GET['Path_2'] == 'verwijder')
        {
            $gebruikerID = $filter->sanatizeInput($_GET['Path_3'], 'int');
            $DB->Delete("DELETE FROM gebruiker WHERE gebruiker_id = ?", [$gebruikerID]);
            header('Location: /admin/gebruikerbeheer');
        }

        //Laat de studentaanpassen pagina zien
        else if(isset($_GET['Path_2']) && $_GET['Path_2'] == 'edit' && isset($_GET['Path_3']) && $user->rank >= 2)
        {
            $gebruikerID = $filter->sanatizeInput($_GET['Path_3'], 'int');

            $gebruikerResult = $DB->Select("SELECT * FROM gebruiker WHERE gebruiker_id = ? LIMIT 1 ", [$gebruikerID]); 
            //Haalt alle docenten op

        echo '<div class="sectionTitle">{VIDEOBEHEER_AANPASSEN_TITEL}</div><form method="post">
                    {updateError}<br />
                    <label>{GEBRUIKERBEHEER_INVOEGEN_VOORNAAM}: <input type="text" name="voorNaam" placeholder="{GEBRUIKERBEHEER_INVOEGEN_VOORNAAM}" value="'.$gebruikerResult[0]['voornaam'].'" required></label><br />
                    <label>{GEBRUIKERBEHEER_INVOEGEN_ACHTERNAAM}: <input type="text" name="achterNaam" placeholder="{GEBRUIKERBEHEER_INVOEGEN_ACHTERNAAM}" value="'.$gebruikerResult[0]['achternaam'].'" required></label><br />
                    <label>Email: <input type="email" name="regEmail" placeholder="Emailadres" value="'.$gebruikerResult[0]['email'].'" required></label><br />
                    <label>{PROFIEL_AANPASSEN_WACHTWOORD}: <input type="password" name="regPass1" placeholder="{PROFIEL_AANPASSEN_WACHTWOORD}" required></label><br />
                    <label>{GEBRUIKERBEHEER_INVOEGEN_HERHAAL_WACHTWOORD}: <input type="password" name="regPass2" placeholder="{GEBRUIKERBEHEER_INVOEGEN_HERHAAL_WACHTWOORD}" required></label><br />
                    <input type="hidden" value="'.$gebruikerResult[0]['gebruiker_id'].'" name="gebruikerID">
                    <label>Level: <select name="levelSelect">';
            for( $i=1; $i < 3; $i++ ) {
                echo '<option ' . ($gebruikerResult[0]['level'] == $i ? 'selected="selected"' : '') . ' value="'.$i.'">' . $user->userLevelName($i) . '</option>';
            }
            echo '</select>
                
                <br />
            <button type="submit" name="submitEdit" required>{BEHEER_OPSLAAN}</button>
        </form>';
        }
    ?>
             
            </div>
        </div>
    </div>