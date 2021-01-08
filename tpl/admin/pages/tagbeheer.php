<?php 
    //Handler
    if(isset($_POST['submitInvoegen'])){
        if(!empty($_POST['tagNaam'])){
            if(!empty($_POST['tagOpleiding'])) {
                $tagNaam = $filter->sanatizeInput($_POST['tagNaam'], 'string');

                foreach ($_POST['tagOpleiding'] as $key => $value) {
                    $tagOpleiding = $filter->sanatizeInput($value, 'int');
                    $DB->Insert("INSERT INTO tag (opleiding_id, naam) VALUES (?, ?)", [$tagOpleiding, $tagNaam]);               
                }

                header("Location: /admin/tagbeheer");
            }
            else {
                echo "Geen opleiding opgegeven";
            }
        }
        else {
            echo "Geen tagnaam opgegeven";
        }
    }

    if(isset($_POST['sumbmitAanpassen'])){
        if(!empty($_POST['tagNaam'])){
            if(!empty($_POST['tagOpleiding'])) {
                $tagNaam = $filter->sanatizeInput($_POST['tagNaam'], 'string');

                foreach ($_POST['tagOpleiding'] as $key => $value) {
                    $tagOpleiding = $filter->sanatizeInput($value, 'int');
                    $DB->Delete("DELETE FROM tag WHERE tag_id = ?", [$tagID]);
                    $DB->Insert("INSERT INTO tag (opleiding_id, naam) VALUES (?, ?)", [$tagOpleiding, $tagNaam]);               
                }

                header("Location: /admin/tagbeheer");
            }
            else {
                echo "Geen opleiding opgegeven";
            }
        }
        else {
            echo "Geen tagnaam opgegeven";
        }
    }
?>

<div class="spotlightVideo">
        <div class="adminView">
            <div class="adminViewRand"></div>
            <div class="adminContent"></div>
            <div class="adminOptions">
            <div class="sectionTitle">tagbeheer</div>
                <div class="sectionTitle link" data-link="/admin/tagbeheer/invoegen">> invoegen</div>
                <div class="sectionTitle link" data-link="/admin/tagbeheer">> terug naar overzicht</div>
                <div class="sectionTitle link" data-link="/admin/start">> terug naar hoofdmenu</div>

            </div>
            <div class="adminTableView">
        <?php 

        //Laat de weergave pagina zien
        if(!isset($_GET['Path_2']) && !isset($_GET['Path_3']))
        {
            $gebuikerResult = $DB->Select("SELECT opleiding.naam AS opleidingnaam, tag.naam AS tagnaam, tag.tag_id, opleiding.jaar FROM tag 
                                            INNER JOIN opleiding
                                            ON opleiding.opleiding_id = tag.opleiding_id"); //Haalt alle docenten op

            echo '<div class="sectionTitle">overzicht</div><table>
                    <thead>
                        <tr>
                            <th>Opleiding</th>
                            <th>Jaar</th>
                            <th>Tagnaam</th>
                            <th><i class="fa fa-pencil-square-o" aria-hidden="true"></i></th>
                            <th><i class="fa fa-times" aria-hidden="true"></i></th>
                        </tr>
                    </thead>
                    <tbody>';

            foreach($gebuikerResult as $key => $value) 
            {
                echo "<tr>";
                echo "<td>".$value['opleidingnaam']."</td>";
                echo "<td>".$value['jaar']."</td>";
                echo "<td>".$value['tagnaam']."</td>";
                    echo "<td class='link' data-link='/admin/tagbeheer/edit/".$value['tag_id']."'>
                    <i class='fa fa-pencil-square-o' aria-hidden='true'></i>
                    </td>";
                    echo "<td class='link' data-link='/admin/tagbeheer/verwijder/".$value['tag_id']."'>
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

            echo '<div class="sectionTitle">invoegen</div>
            <form method="post">
                <label>Tagnaam: <input type="text" placeholder="Tagnaam" name="tagNaam"></label><br>';
                
            if(!empty($opleidingData))  {
                echo '<label>Opleiding (meerdere mogelijk): <select name="tagOpleiding[]" multiple>';

                foreach($opleidingData as $key => $opleidingLijst) 
                { 
                    echo "<option value='{$opleidingLijst["opleiding_id"]}'>{$opleidingLijst["naam"]} (jaar: {$opleidingLijst['jaar']})</option>";
                }
               
                echo '</select></label><br>';
            } 
                
            echo '<button type="submit" name="submitInvoegen" required>Invoegen</button>
            </form>';
        }

        //Laat de delete pagina zien
        else if(isset($_GET['Path_2']) && $_GET['Path_2'] == 'verwijder')
        {
            $tagID = $filter->sanatizeInput($_GET['Path_3'], 'int');
            $DB->Delete("DELETE FROM tag WHERE tag_id = ?", [$tagID]);
            header('Location: /admin/tagbeheer');
        }

        //Laat de aanpassen pagina zien
        else if(isset($_GET['Path_2']) && $_GET['Path_2'] == 'edit' && isset($_GET['Path_3']) && $user->rank >= 2)
        {
            $tagID = $filter->sanatizeInput($_GET['Path_3'], 'int');

            $tagResult = $DB->Select("SELECT * FROM tag WHERE tag_id = ? LIMIT 1", [$tagID])[0]; 

            if(!empty($tagResult)){
                $opleidingData = $DB->Select("SELECT * FROM opleiding");

                echo '<div class="sectionTitle">aanpassen</div>
                        <form method="post">
                            <label>Tagnaam: <input type="text" placeholder="Tagnaam" name="tagNaam" value="'.$tagResult['naam'].'"></label><br>';
                
            if(!empty($opleidingData))  {
                echo '<label>Opleiding (meerdere mogelijk): <select name="tagOpleiding[]" multiple>';

                foreach($opleidingData as $key => $opleidingLijst) 
                { 
                    if(in_array($opleidingLijst['opleiding_id'], $tagResult)) {
                        echo "<option class='optionSelected' value='{$opleidingLijst['opleiding_id']}' selected >{$opleidingLijst['naam']} (geselecteerd)</option>";
                    }
                    
                    else {
                        echo "<option value='{$opleidingLijst['opleiding_id']}'>{$opleidingLijst['naam']}</option>";
                    }
                }

                echo '</select></label><br>';
            } 
                echo '<input type="hidden" name="tagID" value="'.$tagID.'"><button type="submit" name="sumbmitAanpassen">Aanpassen</button></form>';
            }
            
        }
    ?>
            </div>
        </div>
    </div>