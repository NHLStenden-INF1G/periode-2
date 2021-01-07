<?php 
    //Handler
    if(isset($_POST['submitInvoegen'])){
    
    }

    if(isset($_POST['aanpassenSubmit'])){
        if(isset($_POST['opleidingNaam'])){
            if(isset($_POST['opleidingJaarlaag'])){
                if(isset($_POST['opleidingPeriode'])){
                    if(isset($_POST['opleidingEditID'])){

                        //Filter vars
                        $opleidingNaam = $filter->sanatizeInput($_POST['opleidingNaam'], "string");
                        $opleidingJaarlaag = $filter->sanatizeInput($_POST['opleidingJaarlaag'], "string");
                        $opleidingPeriode = $filter->sanatizeInput($_POST['opleidingPeriode'], "string");
                        $opleidingEditID = $filter->sanatizeInput($_POST['opleidingEditID'], "string");

                        $DB->Insert("UPDATE opleiding SET opleiding_naam = ?, jaar = ?, periode = ? WHERE opleiding_id = ?", 
                            [$opleidingNaam, $opleidingJaarlaag, $opleidingPeriode, $opleidingEditID]);
                            
                        //header("Location: opleidingbeheer");
                    }
                } 
            } 
       }    
    }
        //Laat de weergave pagina zien
        if(!isset($_GET['Path_2']) && !isset($_GET['Path_3']))
        {
        
            $gebuikerResult = $DB->Select("SELECT * FROM gebruiker WHERE gebruiker_id != ? AND level < ? ORDER BY gebruiker_id ASC ", [
                $user->id, $user->rank]); //Haalt alle docenten op

            echo "<table class='docentBeheer'>";

            foreach($gebuikerResult as $key => $value) 
            {
                echo "<tr>";
                    echo "<td>{$value['voornaam']} {$value['achternaam']}</td>";
                    echo "<td>
                            <div class='link button' data-link='gebruikerbeheer/delete/{$value['gebruiker_id']}'>
                                <i class='fa fa-trash' aria-hidden='true'></i>
                            </div>
                        </td>";
                echo "</tr>";
            }

            echo "</table>";

        }
        //Laat de invoegen pagina zien
        else if(isset($_GET['Path_2']) && $_GET['Path_2'] == 'add')
        {
            echo 2;
        }

        //Laat de delete pagina zien
        else if(isset($_GET['Path_2']) && $_GET['Path_2'] == 'delete')
        {
            echo 3;
        }

        //Laat de studentaanpassen pagina zien
        else if(isset($_GET['Path_2']) && $_GET['Path_2'] == 'studentedit' && isset($_GET['Path_3']) && $user->rank >= 2)
        {
            echo 4;
        }

        //Laat de docentaanpassen weergave zien voor administrators
        else if(isset($_GET['Path_2']) && $_GET['Path_2'] == 'adminedit' && isset($_GET['Path_3']) && $user->rank === 3)
        {
            echo 5;
        }
    ?>