<?php
?>

<div class="spotlightVideo">
        <div class="adminView">
            <div class="adminViewRand"></div>
            <div class="adminContent"></div>
            <div class="adminOptions">
            <div class="sectionTitle">suggestiebeheer</div>
                <div class="sectionTitle link" data-link="/admin/start">> terug naar hoofdmenu</div>

            </div>
            <div class="adminTableView">
        <?php 

        //Laat de weergave pagina zien
        if(!isset($_GET['Path_2']) && !isset($_GET['Path_3']))
        {
            $videoResult = $DB->Select("SELECT * FROM suggestie 
                                        INNER JOIN gebruiker
                                        ON gebruiker.gebruiker_id = suggestie.gebruiker_id
                                        ORDER BY datum DESC");

            echo '<div class="sectionTitle">overzicht</div>
            <div style="overflow-x:auto;">
                <table>
                    <thead>
                        <tr>
                            <th>Gebruiker</th>
                            <th>Datum</th>
                            <th>Tekst</th>
                            <th>Link</th>
                            <th>Status</th>
                            <th><i class="fa fa-check-circle-o" aria-hidden="true"></i></th>
                            <th><i class="fa fa-times" aria-hidden="true"></i></th>
                        </tr>
                    </thead>
                    <tbody>';

            foreach($videoResult as $key => $value) 
            {
                echo "<tr>";
                    //td
                    echo "<td>".$value['voornaam']." ".$value['achternaam']. "</td>";
                    echo "<td>".$value['datum']."</td>";
                    echo "<td>".$value['tekst']."</td>";
                    echo "<td>".$value['link']."</td>";
                    if($value['status'] == 0) {
                        $huidigestatus = '<span style="color: orange" class="fa fa-circle" aria-hidden="true"></i>'; //geen status
                    }
                    if($value['status'] == 1) {
                        $huidigestatus = '<i style="color: green" class="fa fa-check-circle-o" aria-hidden="true"></i>'; //goedgekeurd
                    }
                    if($value['status'] == 2) {
                        $huidigestatus = '<i style="color: red" class="fa fa-times-circle" aria-hidden="true"></i>'; //geweigerd
                    }
                    echo "<td>".$huidigestatus."</td>";
                    echo "<td class='link' data-link='/admin/suggestiebeheer/goed/".$value['suggestie_id']."'>
                    <i style='color: green' class='fa fa-check-circle-o' aria-hidden='true'></i>
                    </td>";
                    echo "<td class='link' data-link='/admin/suggestiebeheer/verwijder/".$value['suggestie_id']."'>
                            <i style='color: red' class='fa fa-times' aria-hidden='true'></i>
                        </td>";
                echo "</tr>";
            }

            echo "</tbody>
            </table></div>";

        }
        //Laat de delete pagina zien
        else if(isset($_GET['Path_2']) && $_GET['Path_2'] == 'verwijder')
        {
            $suggestieID = $filter->sanatizeInput($_GET['Path_3'], 'int');
            $DB->Update("UPDATE suggestie SET status = 2 WHERE suggestie_id = ?", [$suggestieID]);

            header('Location: /admin/suggestiebeheer');
        }
        else if(isset($_GET['Path_2']) && $_GET['Path_2'] == 'goed')
        {
            $suggestieID = $filter->sanatizeInput($_GET['Path_3'], 'int');

            $DB->Update("UPDATE suggestie SET status = 1 WHERE suggestie_id = ?", [$suggestieID]);

            header('Location: /admin/suggestiebeheer');
        }

    ?>
             
            </div>
        </div>
    </div>