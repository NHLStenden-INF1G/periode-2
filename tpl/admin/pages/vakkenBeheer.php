<?php
$this->Set("pageTitle", $this->Get("ADMIN_VAKKENBEHEER"));

//Geen goede rank
if($user->rank != 3){
    $core->Redirect("/admin");
}
    
if(isset($_POST['submitInvoegen'])) {
    if(!empty($_POST['vakNaam'])) {  
        if(!empty($_POST['opleidingNaam'])) {
            $vakNaam = $filter->sanatizeInput($_POST['vakNaam'], "string");
            $opleidingID = $filter->sanatizeInput($_POST['opleidingNaam'], "int");

            $DB->Insert("INSERT INTO vak (opleiding_id, vak_naam) VALUES (?, ?)", [$opleidingID, $vakNaam]);
            header("Location: /admin/vakkenbeheer");
        }
    }
}


if(isset($_POST['submitAanpassen'])) {
    if(!empty($_POST['vakNaam'])) {
        if(!empty($_POST['opleidingNaam'])) {

            $vakID = $filter->sanatizeInput($_POST['vakID'], "int");
            $vakNaam = $filter->sanatizeInput($_POST['vakNaam'], "string");
            $opleidingID = $filter->sanatizeInput($_POST['opleidingNaam'], "int");

            $DB->Update("UPDATE vak SET vak_naam = ?, opleiding_id = ? WHERE vak_id = ?", [$vakNaam, $opleidingID, $vakID]);
            header("Refresh: 0");
        }
    }
}

?>

<div class="spotlightVideo">
        <div class="adminView">
            <div class="adminViewRand"></div>
            <div class="adminContent"></div>
            <div class="adminOptions">
            <div class="sectionTitle">{ADMIN_VAKKENBEHEER}</div>
                <div class="sectionTitle link" data-link="/admin/vakkenbeheer/invoegen">> {BEHEER_NAV_INVOEGEN}</div>
                <div class="sectionTitle link" data-link="/admin/vakkenbeheer">> {BEHEER_NAV_TERUG_OVERZICHT}</div>
                <div class="sectionTitle link" data-link="/admin/start">> {BEHEER_NAV_TERUG_HOOFDMENU}</div>
            </div>
            <div class="adminTableView">
        <?php 

        //Laat de weergave pagina zien
        if(!isset($_GET['Path_2']) && !isset($_GET['Path_3']))
        {
            $vakResult = $DB->Select("SELECT * FROM vak INNER JOIN opleiding ON opleiding.opleiding_id = vak.opleiding_id");

            echo '<div class="sectionTitle">{BEHEER_OVERZICHT}</div><table>
                    <thead>
                        <tr>
                            <th>{VIDEOBEHEER_UPLOADEN_VAK}</th>
                            <th>{GEBRUIKERBEHEER_NAAM}</th>
                            <th><i class="fa fa-pencil-square-o" aria-hidden="true"></i></th>
                            <th><i class="fa fa-times" aria-hidden="true"></i></th>
                        </tr>
                    </thead>
                    <tbody>';

            foreach($vakResult as $key => $value) 
            {
                echo "<tr>";
                    echo "<td>".$value['vak_naam']."</td>";
                    echo "<td>".$value['naam']."</td>";
                    echo "<td class='link' data-link='/admin/vakkenbeheer/edit/".$value['vak_id']."'>
                            <i class='fa fa-pencil-square-o' aria-hidden='true'></i>
                          </td>";
                    echo "<td class='link' data-link='/admin/vakkenbeheer/verwijder/".$value['vak_id']."'>
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
            $opleidingData = $DB->Select("SELECT * FROM opleiding");
           echo '<div class="sectionTitle">{BEHEER_NAV_INVOEGEN}</div><form method="POST">
           <label>{GEBRUIKERBEHEER_NAAM}: <input type="text" name="vakNaam" placeholder="{GEBRUIKERBEHEER_NAAM}"></label>
           
           <label>{OPLDEIDINGBEHEER_OPLEIDINGNAAM}:  
                <select name="opleidingNaam">';

                foreach ($opleidingData as $key => $value) {
                    echo '<option value="'.$value['opleiding_id'].'">'.$value['naam'].' ({OPLDEIDINGBEHEER_JAAR}: '.$value['jaar'].', {OPLDEIDINGBEHEER_PERIODE}: '.$value['periode'].')</option>';
                }

            echo '</select>
                </label>

            <button type="submit" name="submitInvoegen">{BEHEER_OPSLAAN}</button>';
        }
        
        //Laat de delete pagina zien
        else if(isset($_GET['Path_2']) && $_GET['Path_2'] == 'verwijder')
        {
            $vakID = $filter->sanatizeInput($_GET['Path_3'], 'int');
            $DB->Delete("DELETE FROM vak WHERE vak_id = ?", [$vakID]);

            header('Location: /admin/vakkenbeheer');
        }
        else if(isset($_GET['Path_2']) && $_GET['Path_2'] == 'edit')
        {
            $vakID = $filter->sanatizeInput($_GET['Path_3'], 'int');
            $opleidingData = $DB->Select("SELECT * FROM opleiding");

            $vakData = $DB->Select("SELECT * FROM vak WHERE vak_id = ? LIMIT 1", [$vakID])[0];
            echo '<div class="sectionTitle">{VIDEOBEHEER_AANPASSEN_TITEL}</div><form method="POST">
            <label>{GEBRUIKERBEHEER_NAAM}: <input type="text" name="vakNaam" placeholder="{GEBRUIKERBEHEER_NAAM}" value="'.$vakData['vak_naam'].'"></label>
            
            <label>{OPLDEIDINGBEHEER_OPLEIDINGNAAM}:  
                 <select name="opleidingNaam">';
 
                 foreach ($opleidingData as $key => $value) {
                     echo '<option value="'.$value['opleiding_id'].'">'.$value['naam'].' ({OPLDEIDINGBEHEER_JAAR}: '.$value['jaar'].', {OPLDEIDINGBEHEER_PERIODE}: '.$value['periode'].')</option>';
                 }
 
             echo '</select>
                 </label>
                 <input type="hidden" name="vakID" value="'.$vakData['vak_id'].'">
             <button type="submit" name="submitAanpassen">{BEHEER_OPSLAAN}</button>';

        }

    ?>
             
            </div>
        </div>
    </div>