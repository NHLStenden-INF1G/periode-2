<?php

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
            <div class="sectionTitle">opleidingbeheer</div>
                <div class="sectionTitle link" data-link="/admin/opleidingbeheer/invoegen">> invoegen</div>
                <div class="sectionTitle link" data-link="/admin/opleidingbeheer">> terug naar overzicht</div>

                <div class="sectionTitle link" data-link="/admin/start">> terug naar hoofdmenu</div>
            </div>
            <div class="adminTableView">
        <?php 

        //Laat de weergave pagina zien
        if(!isset($_GET['Path_2']) && !isset($_GET['Path_3']))
        {
            $opleidingResult = $DB->Select("SELECT * FROM opleiding");

            echo '<div class="sectionTitle">overzicht</div><table>
                    <thead>
                        <tr>
                            <th>Jaar</th>
                            <th>Periode</th>
                            <th>Naam</th>
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
           echo '<div class="sectionTitle">invoegen</div><form method="POST">
           <label>Naam: <input type="text" name="opleidingNaam" placeholder="Opleidingnaam"></label>
           
           <label>Jaar:  
                <select name="opleidingJaar">
                    <option value="1">Jaar 1</option>
                    <option value="2">Jaar 2</option>
                    <option value="3">Jaar 3</option>
                    <option value="4">Jaar 4</option>
                </select>
            </label>
            <label>Periode:  
                <select name="opleidingPeriode">
                    <option value="1">Periode 1</option>
                    <option value="2">Periode 2</option>
                    <option value="3">Periode 3</option>
                    <option value="4">Periode 4</option>
            </select>
            <button type="submit" name="submitInvoegen">Opslaan</button>';
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
            echo '<div class="sectionTitle">aanpassen</div><form method="POST">
            <label>Naam: <input type="text" name="opleidingNaam" placeholder="Opleidingnaam" value="'.$opleidingData['naam'].'"></label>
            
            <label>Jaar: (huidig: '.$opleidingData['jaar'].')
                 <select name="opleidingJaar">
                     <option value="1">Jaar 1</option>
                     <option value="2">Jaar 2</option>
                     <option value="3">Jaar 3</option>
                     <option value="4">Jaar 4</option>
                 </select>
             </label>
             <label>Periode: (huidig: '.$opleidingData['periode'].')
                 <select name="opleidingPeriode">
                     <option value="1">Periode 1</option>
                     <option value="2">Periode 2</option>
                     <option value="3">Periode 3</option>
                     <option value="4">Periode 4</option>
             </select>
             <button type="submit" name="submitAanpassen">Opslaan</button>';

        }

    ?>
             
            </div>
        </div>
    </div>