<?php 
    //Handler
    $this->Set("pageTitle", $this->Get("ADMIN_TAGBEHEER"));

    if(isset($_POST['submitInvoegen'])){
        if(!empty($_POST['tagNaam'])){
                $tagNaam = $filter->sanatizeInput($_POST['tagNaam'], 'string');

                $tagID = $DB->Insert("INSERT INTO tag (naam) VALUES (?)", [$tagNaam]);               

                header("Location: /admin/tagbeheer");
        }
        else {
            echo "{TAGBEHEER_GEEN_TAG}";
        }
    }

    if(isset($_POST['sumbmitAanpassen'])){
        if(!empty($_POST['tagNaam'])){
                $tagNaam = $filter->sanatizeInput($_POST['tagNaam'], 'string');
                $tagID = $filter->sanatizeInput($_POST['tagID'], 'int');

                $DB->Update("UPDATE tag SET naam = ? WHERE tag_id = ?", [$tagNaam, $tagID]);
                header("Location: /admin/tagbeheer");
        }
        else {
            echo "{TAGBEHEER_GEEN_TAG}";
        }
    }
?>

<div class="spotlightVideo">
        <div class="adminView">
            <div class="adminViewRand"></div>
            <div class="adminContent"></div>
            <div class="adminOptions">
            <div class="sectionTitle">{ADMIN_TAGBEHEER}</div>
                <div class="sectionTitle link" data-link="/admin/tagbeheer/invoegen">> {BEHEER_NAV_INVOEGEN}</div>
                <div class="sectionTitle link" data-link="/admin/tagbeheer">> {BEHEER_NAV_TERUG_OVERZICHT}</div>
                <div class="sectionTitle link" data-link="/admin/start">> {BEHEER_NAV_TERUG_HOOFDMENU}</div>

            </div>
            <div class="adminTableView">
        <?php 

        //Laat de weergave pagina zien
        if(!isset($_GET['Path_2']) && !isset($_GET['Path_3']))
        {
            $gebuikerResult = $DB->Select("SELECT * FROM tag"); //Haalt alle docenten op

            echo '<div class="sectionTitle">{BEHEER_OVERZICHT}</div><table>
                    <thead>
                        <tr>
                            <th>Tag</th>
                            <th><i class="fa fa-pencil-square-o" aria-hidden="true"></i></th>
                            <th><i class="fa fa-times" aria-hidden="true"></i></th>
                        </tr>
                    </thead>
                    <tbody>';

            foreach($gebuikerResult as $key => $value) 
            {
                echo "<tr>";
                echo "<td>".$value['naam']."</td>";
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

            echo '<div class="sectionTitle">{BEHEER_NAV_INVOEGEN}</div>
            <form method="post">
                <label>Tag: <input type="text" placeholder="{TAGBEHEER_TAGNAAM}" name="tagNaam"></label><br>';
            echo '<button type="submit" name="submitInvoegen" required>{BEHEER_NAV_INVOEGEN}</button>
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
               
                echo '<div class="sectionTitle">{VIDEOBEHEER_AANPASSEN_TITEL}</div>
                        <form method="post">
                            <label>Tag: <input type="text" placeholder="Tagnaam" name="tagNaam" value="'.$tagResult['naam'].'"></label><br>';
                echo '<input type="hidden" name="tagID" value="'.$tagID.'"><button type="submit" name="sumbmitAanpassen">{VIDEOBEHEER_AANPASSEN_TITEL}</button></form>';
            }
            
        }
    ?>
            </div>
        </div>
    </div>