<?php 
    //Handler
    if(isset($_POST['submitInvoegen'])){
    
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