<?php
$this->Set("pageTitle", $this->Get("ADMIN_OPLEIDINGBEHEER"));

if(isset($_POST['submitInvoegen'])) {
    if(!empty($_POST['opleidingNaam'])) {
        if(!empty($_POST['opleidingJaar'])) {
            if(!empty($_POST['opleidingPeriode'])) {
                $opleidingNaam = $filter->sanatizeInput($_POST['opleidingNaam'], "string");
                $opleidingJaar = $filter->sanatizeInput($_POST['opleidingJaar'], "int");
                $opleidingPeriode = $filter->sanatizeInput($_POST['opleidingPeriode'], "int");

                $DB->Insert("INSERT INTO opleiding (jaar, periode, naam) VALUES (?, ?, ?)", [$opleidingJaar, $opleidingPeriode, $opleidingNaam]);
                header("Location: /admin/opleidingbeheer");
            }
        }
    }
}


if(isset($_POST['submitAanpassen'])) {
    if(!empty($_POST['opleidingNaam'])) {
        if(!empty($_POST['opleidingJaar'])) {
            if(!empty($_POST['opleidingPeriode'])) {
                $opleidingNaam = $filter->sanatizeInput($_POST['opleidingNaam'], "string");
                $opleidingJaar = $filter->sanatizeInput($_POST['opleidingJaar'], "int");
                $opleidingPeriode = $filter->sanatizeInput($_POST['opleidingPeriode'], "int");

                $DB->Update("UPDATE opleiding SET jaar = ?, periode = ?, naam = ?", [$opleidingJaar, $opleidingPeriode, $opleidingNaam]);
                header("Refresh: 0");
            }
        }
    }
}

?>

<div class="spotlightVideo">
        <div class="adminView">
            <div class="adminViewRand"></div>
            <div class="adminContent"></div>
            <div class="adminOptions">
            <div class="sectionTitle">{ADMIN_OPLEIDINGBEHEER}</div>
                <div class="sectionTitle link" data-link="/admin/opleidingbeheer/invoegen">> {BEHEER_NAV_INVOEGEN}</div>
                <div class="sectionTitle link" data-link="/admin/opleidingbeheer">> {BEHEER_NAV_TERUG_OVERZICHT}</div>
                <div class="sectionTitle link" data-link="/admin/start">> {BEHEER_NAV_TERUG_HOOFDMENU}</div>
            </div>
            <div class="adminTableView">
        <?php 

        //Laat de weergave pagina zien
        if(!isset($_GET['Path_2']) && !isset($_GET['Path_3']))
        {
            $opleidingResult = $DB->Select("SELECT * FROM opleiding");

            echo '<div class="sectionTitle">{BEHEER_OVERZICHT}</div><table>
                    <thead>
                        <tr>
                            <th>{OPLDEIDINGBEHEER_JAAR}</th>
                            <th>{OPLDEIDINGBEHEER_PERIODE}</th>
                            <th>{GEBRUIKERBEHEER_NAAM}</th>
                            <th><i class="fa fa-pencil-square-o" aria-hidden="true"></i></th>
                            <th><i class="fa fa-times" aria-hidden="true"></i></th>
                        </tr>
                    </thead>
                    <tbody>';

            foreach($opleidingResult as $key => $value) 
            {
                echo "<tr>";
                    echo "<td>".$value['jaar']."</td>";
                    echo "<td>".$value['periode']."</td>";
                    echo "<td>".$value['naam']."</td>";
                    echo "<td class='link' data-link='/admin/opleidingbeheer/edit/".$value['opleiding_id']."'>
                    <i class='fa fa-pencil-square-o' aria-hidden='true'></i>
                    </td>";
                    echo "<td class='link' data-link='/admin/opleidingbeheer/verwijder/".$value['opleiding_id']."'>
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
           echo '<div class="sectionTitle">{BEHEER_NAV_INVOEGEN}</div><form method="POST">
           <label>{GEBRUIKERBEHEER_NAAM}: <input type="text" name="opleidingNaam" placeholder="{OPLDEIDINGBEHEER_OPLEIDINGNAAM}"></label>
           
           <label>{OPLDEIDINGBEHEER_JAAR}:  
                <select name="opleidingJaar">
                    <option value="1">{OPLDEIDINGBEHEER_JAAR} 1</option>
                    <option value="2">{OPLDEIDINGBEHEER_JAAR} 2</option>
                    <option value="3">{OPLDEIDINGBEHEER_JAAR} 3</option>
                    <option value="4">{OPLDEIDINGBEHEER_JAAR} 4</option>
                </select>
            </label>
            <label>{OPLDEIDINGBEHEER_PERIODE}:  
                <select name="opleidingPeriode">
                    <option value="1">{OPLDEIDINGBEHEER_PERIODE} 1</option>
                    <option value="2">{OPLDEIDINGBEHEER_PERIODE} 2</option>
                    <option value="3">{OPLDEIDINGBEHEER_PERIODE} 3</option>
                    <option value="4">{OPLDEIDINGBEHEER_PERIODE} 4</option>
            </select>
            <button type="submit" name="submitInvoegen">{BEHEER_OPSLAAN}</button>';
        }
        
        //Laat de delete pagina zien
        else if(isset($_GET['Path_2']) && $_GET['Path_2'] == 'verwijder')
        {
            $opleidingID = $filter->sanatizeInput($_GET['Path_3'], 'int');
            $DB->Delete("DELETE FROM opleiding WHERE opleiding_id = ?", [$opleidingID]);

            header('Location: /admin/opleidingbeheer');
        }
        else if(isset($_GET['Path_2']) && $_GET['Path_2'] == 'edit')
        {
            $opleidingID = $filter->sanatizeInput($_GET['Path_3'], 'int');
            $opleidingData = $DB->Select("SELECT * FROM opleiding WHERE opleiding_id = ? LIMIT 1", [$opleidingID])[0];
            echo '<div class="sectionTitle">{VIDEOBEHEER_AANPASSEN_TITEL}</div><form method="POST">
            <label>{GEBRUIKERBEHEER_NAAM}: <input type="text" name="opleidingNaam" placeholder="{OPLDEIDINGBEHEER_OPLEIDINGNAAM}" value="'.$opleidingData['naam'].'"></label>
            
            <label>{OPLDEIDINGBEHEER_JAAR}: ({OPLDEIDINGBEHEER_HUIDIG}: '.$opleidingData['jaar'].')
                 <select name="opleidingJaar">
                     <option value="1">{OPLDEIDINGBEHEER_JAAR} 1</option>
                     <option value="2">{OPLDEIDINGBEHEER_JAAR} 2</option>
                     <option value="3">{OPLDEIDINGBEHEER_JAAR} 3</option>
                     <option value="4">{OPLDEIDINGBEHEER_JAAR} 4</option>
                 </select>
             </label>
             <label>{OPLDEIDINGBEHEER_PERIODE}: ({OPLDEIDINGBEHEER_HUIDIG}: '.$opleidingData['periode'].')
                 <select name="opleidingPeriode">
                     <option value="1">{OPLDEIDINGBEHEER_PERIODE} 1</option>
                     <option value="2">{OPLDEIDINGBEHEER_PERIODE} 2</option>
                     <option value="3">{OPLDEIDINGBEHEER_PERIODE} 3</option>
                     <option value="4">{OPLDEIDINGBEHEER_PERIODE} 4</option>
             </select>
             <button type="submit" name="submitAanpassen">{BEHEER_OPSLAAN}</button>';

        }

    ?>
             
            </div>
        </div>
    </div>