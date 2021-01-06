<main class="content">

<div class='contentBlock-nohover vakkenbeheer'>
    <div class='contentBlock-side'></div>
    <div class='contentBlock-content'>


<?php 
    if(isset($_POST['aanpassenSubmit'])){
        if(isset($_POST['vakNaam'])){
            $vakNaam = $_POST['vakNaam'];
            $vakJaarlaag = $_POST['vakJaarlaag'];
            $vakPeriode = $_POST['vakPeriode'];
            $vakDocent = $_POST['vakDocent'];
            $vakID = $_POST['boekVakID'];
            $vakTeams = $_POST['vakTeams'];
            $vakBlackboard = $_POST['vakBlackboard'];

            if(empty($_FILES["vakBoek"]["name"])){
                //Geen nieuw moduleboek
                $DB->Get("UPDATE vakken SET vak = '{$vakNaam}', jaarlaag = '{$vakJaarlaag}', periode = '{$vakPeriode}', teams = '{$vakTeams}', blackboard = '{$vakBlackboard}'  WHERE vak_id = '{$vakID}'");
            }
            else {
                //Moduleboek toegevoegd
                $fileName = basename($_FILES["vakBoek"]["name"]); 
                $fileType = pathinfo($fileName, PATHINFO_EXTENSION); 
                
                if($fileType == 'pdf'){ 
                    $pdf = $_FILES['vakBoek']['tmp_name']; 
                    $pdfContent = addslashes(file_get_contents($pdf)); 
                    $DB->Get("UPDATE vakken SET vak = '{$vakNaam}', jaarlaag = '{$vakJaarlaag}', periode = '{$vakPeriode}', moduleboek = '{$pdfContent}', teams = '{$vakTeams}', blackboard = '{$vakBlackboard}' WHERE vak_id = '{$vakID}'");
                }
                else {
                    echo "Je mag alleen een .pdf bestand uploaden.";
                } 
            }
                $DB->Get("DELETE FROM docenten_vakken WHERE vak_id = '{$vakID}'");
                $DB->Get("INSERT INTO docenten_vakken (docent_id, vak_id) VALUES('{$vakDocent}', '{$vakID}')");

                if(isset($_POST['vakKlas'])){
                    // verwijder alle klassen die het vak hadden
                        $DB->Get("DELETE FROM opleiding_vakken WHERE vak_id='{$vakID}'");
                    foreach ($_POST['vakKlas'] as $key => $klasID) {
                        // voeg alle klassen toe die zijn ingevuld
                        $DB->Get("INSERT INTO opleiding_vakken (opleiding_id, vak_id) VALUES ('{$klasID}','{$vakID}')");
                    }
                }
            header("Location: vakkenbeheer");
        }
        else {
            echo "Vaknaam is niet ingevuld.";
        }
       
    }


    //Moduleboek downloaden
    if(isset($_POST['boekView'])){
        if(isset($_POST['boekVakID'])){

            //Gegevens uit database halen
            $downloadModuleboek = $DB->Get("SELECT * FROM vakken WHERE vak_id ='{$_POST['boekVakID']}'");
            $moduleboekData = $downloadModuleboek->fetch_assoc();

            $fileName = $moduleboekData['vak'].' '.$moduleboekData['jaarlaag'].'-'.$moduleboekData['periode'].' - moduleboek.pdf';

            echo $Core->downloadFile($moduleboekData['moduleboek'], $fileName);
        }
    }

    //Invoegen
    if(isset($_POST['submitInvoegen'])){
        if(isset($_POST['vakNaam'])){
            if(isset($_POST['vakOpleidingen'])){

                $vakNaam = $_POST['vakNaam'];
            $vakJaarlaag = $_POST['vakJaarlaag'];
            $vakPeriode = $_POST['vakPeriode'];
            $vakDocent = $_POST['vakDocent'];
            $vakTeams = $_POST['vakTeams'];
            $vakBlackboard = $_POST['vakBlackboard'];

            if(empty($_FILES["vakBoek"]["name"])){
                //Geen moduleboek
                
                $DB->Get("INSERT INTO 
                                        vakken (vak, jaarlaag, periode, teams, blackboard)
                                        VALUES ('{$vakNaam}', '{$vakJaarlaag}', '{$vakPeriode}', '{$vakTeams}', '{$vakBlackboard}')");//>vakken
            }
            else {
                //Moduleboek toegevoegd
                $fileName = basename($_FILES["vakBoek"]["name"]); 
                $fileType = pathinfo($fileName, PATHINFO_EXTENSION); 
                
                if($fileType == 'pdf'){ 
                    $pdf = $_FILES['vakBoek']['tmp_name']; 
                    $pdfContent = addslashes(file_get_contents($pdf)); 
                    
                    $DB->Get("INSERT INTO 
                                            vakken (vak, jaarlaag, periode, moduleboek, teams, blackboard)
                                            VALUES ('{$vakNaam}', '{$vakJaarlaag}', '{$vakPeriode}', '{$pdfContent}', '{$vakTeams}', '{$vakBlackboard}')");//>vakken 
                }
                else {
                    echo "Je mag alleen een .pdf bestand uploaden.";
                } 
            }
                $vakID = $DB->LastID();
                $DB->Get("INSERT INTO docenten_vakken (docent_id, vak_id) VALUES ('{$vakDocent}','{$vakID}')");

                foreach ($_POST['vakOpleidingen'] as $key => $klasID) {
                    $DB->Get("INSERT INTO opleiding_vakken (opleiding_id, vak_id) VALUES ('{$klasID}','{$vakID}')");
                }
                header("Location: vakkenbeheer");
            }
            else {
                echo "Geen opleiding geselecteerd.";
            }
        }
        else {
            echo "Vaknaam is niet ingevuld.";
        }
    }



        $docentID = intval($_COOKIE['userID']);

        //Laat de weergave pagina zien
        if(!isset($_POST['invoegenPage']) && !isset($_POST['submitDelete']) && !isset($_POST['boekView']) && !isset($_POST['aanpassenPage'])){
            echo "<div class='contentBlock-title'>{$lang["VAKKEN_BEHEER_KEUZEMENU"]} </div><div class='contentBlock-text-normal'>";
            //lijst met vakken met optie om ze aan te passen. (verwijderen)
            //knop voor nieuw vak

            $vakkenResult = $DB->Get("	SELECT vakken.vak_id, vakken.vak, vakken.jaarlaag, vakken.periode 
            FROM docenten_vakken INNER JOIN vakken 
            ON docenten_vakken.vak_id = vakken.vak_id 
            ORDER BY vakken.jaarlaag ASC, vakken.periode ASC"); //Haalt alle vakken van de ID docent op.

            echo "<table class='beheerTabel'>";
            while($vakkenData = $vakkenResult->fetch_assoc()){
                echo "<tr>";
                    echo "<td>{$vakkenData['vak']}</td>";
                    echo "<td>Jaar {$vakkenData['jaarlaag']}</td>";
                    echo "<td>Periode {$vakkenData['periode']}</td>";
                    echo "<td><form method='post'><input type='hidden' value='{$vakkenData['vak_id']}' name='aanpassenID'><button type='submit' name='aanpassenPage'><i class='fa fa-pencil' aria-hidden='true'></i></button></form></td>";
                    echo "<td><form method='post'><input type='hidden' value='{$vakkenData['vak_id']}' name='verwijderID'><button type='submit' name='submitDelete'><i class='fa fa-trash' aria-hidden='true'></i></button></form></td>";
                echo "</tr>";
            }
            echo "</table><form method='post'><button type='submit' name='invoegenPage'>{$lang["NIEUWS_BEHEER_INVOEGEN"]}</button></form>";

        }
        //Laat de invoegen pagina zien
        else if(isset($_POST['invoegenPage']) && !isset($_POST['submitDelete']) && !isset($_POST['boekView']) && !isset($_POST['aanpassenPage'])){
                echo '<div class="contentBlock-title">'.$lang["VAKKEN_BEHEER_INVOEGEN"].'</div>
                        <div class="contentBlock-text-normal">
                        <form method="POST" enctype="multipart/form-data"> 
                            <div class="subTitle">'.$lang["VAKKEN_BEHEER_INFORMATIE"].'</div>
                            

                            <label for="vakNaam">'.$lang["VAKKEN_BEHEER_VAKNAAM"].'*</label><br />
                            <input type="text" id="vakNaam" name="vakNaam" placeholder="'.$lang["VAKKEN_BEHEER_VAKNAAM"].'" style="width: 65%;" required><br />
                            
    
                            <label for="vakTeams">Microsoft Teams</label><br />
                            <input type="text" id="vakTeams" name="vakTeams" placeholder="Teamcode" ><br />
                            
                            <label for="vakBlackboard">Blackboardcourse</label><br />
                            <input type="text" id="vakBlackboard" name="vakBlackboard" placeholder="Link naar blackboardcourse" ><br />

                            <label for="vakJaarlaag">'.$lang["VAKKEN_BEHEER_JAARLAAG"].'*</label><br />
                            <select name="vakJaarlaag" id="vakJaarlaag">
                                <option value="1">Jaar 1</option>
                                <option value="2">Jaar 2</option>
                                <option value="3">Jaar 3</option>
                                <option value="4">Jaar 4</option>
                            </select><br />

                            <label for="vakPeriode">'.$lang["VAKKEN_BEHEER_PERIODE"].'*</label><br />
                            <select name="vakPeriode" id="vakPeriode">
                                <option value="1">Periode 1</option>
                                <option value="2">Periode 2</option>
                                <option value="3">Periode 3</option>
                                <option value="4">Periode 4</option>
                            </select><br />
                            <div class="subTitle">'.$lang['VAKKEN_BEHEER_KLASSEN'].'</div>';
                    
                    $opleidingResult = $DB->Get("SELECT * FROM opleidingen");

                    echo "<label for='vakOpleidingen'>Opleidingen*</label><br />
                            <select class='selectMult' name='vakOpleidingen[]' id='vakOpleidingen' multiple>";
                    while($opleidingData = $opleidingResult->fetch_assoc()){
                        echo "<option value='{$opleidingData['opleiding_id']}'>{$opleidingData['opleiding_naam']}</option>";
                    }
                   
                    echo '</select><br />';
                    echo '<div class="subTitle">'.$lang['VAKKEN_BEHEER_DOCENTEN'].'</div>';
 
                    $docentResult = $DB->Get("SELECT docent_id, voornaam, achternaam FROM docenten");
            
                    echo "<label for='vakDocent'>".$lang['VAKKEN_BEHEER_DOCENTEN']."*</label><br />
                            <select id='vakDocent' name='vakDocent'>";

                    while($docentData = $docentResult->fetch_assoc()){
                        echo "<option value='{$docentData['docent_id']}'>{$docentData['voornaam']} {$docentData['achternaam']}</option>";
                    }
                    
                    echo "</select><br />        
                    <div class='subTitle'>".$lang['VAKKEN_BEHEER_BESTANDEN']."</div>
                        <label for='vakBoek'>".$lang['VAKKEN_BEHEER_MODULEBOEK']." (.pdf)</label><br />
                        <input type='file' name='vakBoek' id='vakBoek'><br />
                        <p>{$lang["DOCENTEN_BEHEER_VERPLICHT"]}</p>
                        <button type='submit' name='submitInvoegen'>{$lang["BESCHIKBAARHEID_SAVE"]}</button>
                        <button type='button' onclick=".'"'."window.location.href='vakkenbeheer'".'"'.">{$lang["DOCENTEN_BEHEER_ANNULEREN"]}</button>
                    </form>";
    }
    //Laat de aanpassen pagina zien
    else if(!isset($_POST['invoegenPage']) && !isset($_POST['submitDelete']) && isset($_POST['aanpassenPage']) && !isset($_POST['boekView']) && intval($_POST['aanpassenID'])){

        //Haal huidige data op
        $currentResult = $DB->Get("SELECT vakken.vak_id, vakken.vak, vakken.jaarlaag, vakken.periode, 
                                        vakken.teams, vakken.blackboard,
                                        docenten.voornaam, docenten.achternaam, docenten_vakken.docent_id,
                                    vakken.moduleboek
                                FROM vakken 
                                INNER JOIN docenten_vakken ON vakken.vak_id  = docenten_vakken.vak_id
                                INNER JOIN docenten ON docenten_vakken.docent_id = docenten.docent_id
                                WHERE vakken.vak_id = '{$_POST['aanpassenID']}'
                                LIMIT 1");

        $currentData = $currentResult->fetch_assoc();

    echo '<div class="contentBlock-title">'.$lang["VAKKEN_BEHEER_BEWERKEN"].'</div>
            <div class="contentBlock-text-normal">
            <form method="POST" enctype="multipart/form-data"> 
            <div class="subTitle">'.$lang["VAKKEN_BEHEER_INFORMATIE"].'</div>
            
            <label for="vakNaam">'.$lang["VAKKEN_BEHEER_VAKNAAM"].'*</label><br />
            <input value="'.$currentData['vak'].'" type="text" id="vakNaam" name="vakNaam" placeholder="'.$lang["VAKKEN_BEHEER_VAKNAAM"].'" style="width: 65%;" required><br />
 
            
            <label for="vakTeams">Microsoft Teams</label><br />
            <input value="'.$currentData['teams'].'" type="text" id="vakTeams" name="vakTeams" placeholder="Teamcode" ><br />
            
            <label for="vakBlackboard">Blackboardcourse</label><br />
            <input value="'.$currentData['blackboard'].'" type="text" id="vakBlackboard" name="vakBlackboard" placeholder="Link naar blackboardcourse" ><br />


            <label for="vakJaarlaag">'.$lang["VAKKEN_BEHEER_JAARLAAG"].'*</label><br />
            <select name="vakJaarlaag" id="vakJaarlaag">';

                for ($i=1; $i <= 4; $i++) { 
                    if($i == $currentData['jaarlaag']){
                        echo '<option class="optionSelected" value="'.$currentData['jaarlaag'].'" selected>'.$lang["VAKKEN_BEHEER_JAAR"].$currentData['jaarlaag'].$lang["VAKKEN_BEHEER_GESELECTEERD"].'</option>';
                    }
                    else if($i != $currentData['jaarlaag']){
                        echo '<option value="'.$i.'">'.$lang["VAKKEN_BEHEER_JAAR"].$i.'</option>';
                    }
                }

            echo '</select><br />
            <label for="vakPeriode">'.$lang["VAKKEN_BEHEER_PERIODE"].'*</label><br />
            <select name="vakPeriode" id="vakPeriode">';

            for ($i=1; $i <= 4; $i++) { 
                if($i == $currentData['periode']){
                    echo '<option class="optionSelected" value="'.$currentData['periode'].'" selected>'.$lang["VAKKEN_BEHEER_PERIODE"].$currentData['periode'].$lang["VAKKEN_BEHEER_GESELECTEERD"].'</option>';
                }
                else if($i != $currentData['periode']){
                    echo '<option value="'.$i.'">'.$lang["VAKKEN_BEHEER_PERIODE"].$i.'</option>';
                }
            }

            echo '</select><br />';
                    
            
               
            $klassen_vakkenResult = $DB->Get("SELECT opleiding_vakken.opleiding_id, opleidingen.opleiding_naam FROM opleiding_vakken
                                        INNER JOIN opleidingen 
                                        ON opleiding_vakken.opleiding_id = opleidingen.opleiding_id
                                        WHERE opleiding_vakken.vak_id = '{$currentData['vak_id']}'");
            
            $klassenResult = $DB->Get("SELECT * FROM opleidingen");

            echo "<label for='vakOpleiding'>Opleiding* (Selecteer meerdere met control.)</label><br />
                    <select class='selectMult' name='vakKlas[]' id='vakOpleiding' multiple>";


            $klassenVakData = $klassen_vakkenResult->fetch_assoc();
            while($klassenData = $klassenResult->fetch_assoc()){
                if(@in_array($klassenData['opleiding_id'], $klassenVakData) && $klassenVakData != NULL){
                    //selected
                    $klassenVakData = $klassen_vakkenResult->fetch_assoc();
                    echo "<option class='optionSelected' value='{$klassenData['opleiding_id']}' selected >{$klassenData['opleiding_naam']}</option>";
                }
                else{
                    //unselected
                    echo "<option value='{$klassenData['opleiding_id']}'>{$klassenData['opleiding_naam']}</option>";
                }
            }
           
            echo '</select><br />
            <div class="subTitle">'.$lang["VAKKEN_BEHEER_DOCENTEN"].'</div>';
 
            $docentResult = $DB->Get("SELECT docent_id, voornaam, achternaam FROM docenten");
           
            echo "<label for='vakDocent'>".$lang["VAKKEN_BEHEER_DOCENTEN"]."*</label><br />
                    <select name='vakDocent' id='vakDocent'>";
            while($docentData = $docentResult->fetch_assoc()){
                if($docentData['docent_id'] == $currentData['docent_id']){
                    echo "<option class='optionSelected' value='{$currentData['docent_id']}' selected>{$currentData['voornaam']} {$currentData ['achternaam']} {$lang["VAKKEN_BEHEER_GESELECTEERD"]}</option>";
                }
                else {
                    echo "<option value='{$docentData['docent_id']}'>{$docentData['voornaam']} {$docentData['achternaam']}</option>";
                }
            }
            echo "</select><br />
            <div class='subTitle'>".$lang["VAKKEN_BEHEER_BESTANDEN"]."</div>";
                if(empty($currentData['moduleboek'])){
                    echo "<label for='vakBoek'>".$lang["VAKKEN_BEHEER_MODULEBOEK"]." (.pdf)</label> <b>Momenteel niks geupload</b><br />";
                }
                else {
                    echo "<label for='vakBoek'>".$lang["VAKKEN_BEHEER_MODULEBOEK"]." (.pdf) 
                <button type='submit' name='boekView' id='vakBoek'>".$lang["VAKKEN_BEHEER_WEERGEVEN"]."</button></label><br />";
                }

                echo "
                <input type='file' name='vakBoek' id='vakBoek'><br />
                <p>".$lang["VAKKEN_BEHEER_VERPLICHT"]."</p>
                <input type='hidden' name='boekVakID' value='{$currentData['vak_id']}'>
                <button type='submit' name='aanpassenSubmit'>{$lang["VAKKEN_BEHEER_OPSLAAN"]}</button>
                <button type='button' onclick=".'"'."window.location.href='vakkenbeheer'".'"'.">{$lang["VAKKEN_BEHEER_ANNULEREN"]}</button>
            </form>
        <br />";

    }
    //Gebruik de verwijderen pagina en controleert of de ingevoegde value wel een integer is.
    else if(!isset($_POST['invoegenPage']) && isset($_POST['submitDelete']) && !isset($_POST['aanpassenPage']) && !isset($_POST['boekView']) && !isset($_POST['aanpassenPage']) && intval($_POST['verwijderID'])){
        $DB->Get("DELETE FROM vakken WHERE vak_id='{$_POST['verwijderID']}'");
        header('location: vakkenbeheer');
    }
    else if(!isset($_POST['invoegenPage']) && !isset($_POST['submitDelete']) && !isset($_POST['aanpassenPage']) && !isset($_POST['boekView']) && intval($_POST['verwijderID'])){
        $DB->Get("DELETE FROM vakken WHERE vak_id='{$_POST['verwijderID']}'");
        header('location: vakkenbeheer');
    }
    ?>
            </div>
        </div>
    </div>
</main> 