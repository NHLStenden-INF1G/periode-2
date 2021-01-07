<?php

if(isset($_POST["submitButton"])) 
{
    if(!empty($_POST['titel'])) 
    {
        if(!empty($_POST['titel'] && !empty($_FILES["file"]["name"])))
        {

            $vakKeuze = $filter->sanatizeInput($_POST["vakKeuze"], 'int');
            $postTitel =  $filter->sanatizeInput($_POST["titel"], 'string');
            //Als het een bestand is
            if(isset($_FILES['file']['name'])) 
            {
                $videoAllowedTypes = array('video/mp4', 'video/webm', 'video/H264'); 
                //Is een toegestaan bestandstype
                if(in_array($_FILES["file"]["type"], $videoAllowedTypes) && $_FILES["file"]["size"] < 5000000000) 
                {
                    $uploadFolder = $_SERVER['DOCUMENT_ROOT'].'/uploads/';
                    $bestandsNaam = basename(sha1(time().$_FILES["file"]["tmp_name"]));
                    $uploadExtensie = '.'.explode(".", $_FILES["file"]["name"])[1];
                    $videoPath = $uploadFolder.$bestandsNaam.$uploadExtensie; 

                    move_uploaded_file($_FILES["file"]["tmp_name"], $videoPath);

                    $videoPathDB = "/uploads/".$bestandsNaam.$uploadExtensie;
                    $videolengte = floor($videoParser->analyze($videoPath)['playtime_seconds']);
                    $DB->Insert("INSERT INTO video (gebruiker_id, titel, videopath, videolengte)
                      VALUES (?, ?, ?, ?);",[$user->id, $postTitel, $videoPathDB, $videolengte]);
                    echo "Video succesvol geuploaded!";

                }
            }
            else {
                    $youtubeLink = $filter->sanatizeInput($_POST['ytlink'], 'url');
        
                    //Als het een youtube URL is
                    if(strpos($youtubeLink, "youtube")) {
                        $playbackID = explode('?v=', $_POST['ytlink'])[1];
                        $DB->Insert("INSERT INTO video (gebruiker_id, titel, playback_id) VALUES (?, ?, ?);",[$user->id, $postTitel, $playbackID]);
                        echo "Video succesvol geuploaded!";
                    } else {
                        echo "Geen valide Youtube URL";
                    }
                
            }


            $videoID = $DB->InsertId();
            $DB->Insert("INSERT INTO vak_video (video_id, vak_id) VALUES (?, ?)", [$videoID, $vakKeuze]);
        }
        else {
            echo "Error: Geen link/video meegegeven.";
        }
    }
    else {
        echo "Error: Geen titel meegegeven.";
    }
}
?>

<div class="spotlightVideo">
        <div class="adminView">
            <div class="adminViewRand"></div>
            <div class="adminContent"></div>
            <div class="adminOptions">
            <div class="sectionTitle">videobeheer</div>
                <div class="sectionTitle link" data-link="/admin/videobeheer/upload">> uploaden</div>
                <div class="sectionTitle link" data-link="/admin/videobeheer">> terug naar overzicht</div>
                <div class="sectionTitle link" data-link="/admin/start">> terug naar hoofdmenu</div>

            </div>
            <div class="adminTableView">
        <?php 

        //Laat de weergave pagina zien
        if(!isset($_GET['Path_2']) && !isset($_GET['Path_3']))
        {
            $videoResult = $DB->Select("SELECT * FROM video 
                                        INNER JOIN vak_video 
                                        ON vak_video.video_id = video.video_id
                                        INNER JOIN gebruiker
                                        ON gebruiker.gebruiker_id = video.gebruiker_id");

            echo '<div class="sectionTitle">overzicht</div><table>
                    <thead>
                        <tr>
                            <th>Docent</th>
                            <th>Titel</th>
                            <th>Datum</th>
                            <th><i class="fa fa-pencil-square-o" aria-hidden="true"></i></th>
                            <th><i class="fa fa-times" aria-hidden="true"></i></th>
                        </tr>
                    </thead>
                    <tbody>';

            foreach($videoResult as $key => $value) 
            {
                echo "<tr>";
                    //td
                    echo "<td>".$value['voornaam']." ".$value['achternaam']. "</td>";
                    echo "<td>".$value['titel']."</td>";
                    echo "<td>".$value['uploadDatum']."</td>";
                    echo "<td class='link' data-link='/admin/videobeheer/edit/".$value['gebruiker_id']."'>
                    <i class='fa fa-pencil-square-o' aria-hidden='true'></i>
                    </td>";
                    echo "<td class='link' data-link='/admin/videobeheer/verwijder/".$value['gebruiker_id']."'>
                            <i class='fa fa-times' aria-hidden='true'></i>
                        </td>";
                echo "</tr>";
            }

            echo "</tbody>
            </table>";

        }
        //Laat de invoegen pagina zien
        else if(isset($_GET['Path_2']) && $_GET['Path_2'] == 'upload')
        {
            $videoData = $DB->Select("SELECT vak_id, vak_naam FROM vak");

            echo '<div class="sectionTitle">uploaden</div>
                    <form enctype="multipart/form-data" method="POST">';

            if(!empty($videoData))  {
                echo '<label for="vak">Vak:<select name="vakKeuze">';

                foreach($videoData as $key => $vakLijst) 
                { 
                    echo "<option value='{$vakLijst["vak_id"]}'>{$vakLijst["vak_naam"]}</option>";
                }
               
                echo '</select></label><br>';
            }
          
            echo '<label for="titel">Videotitel:
            <input type="text" placeholder="Titel" name="titel"><br></label><br>
            
            <label for="ytlink">Upload hier je YT link
            <input type="url" placeholder="Youtubelink" name="ytlink"></label>
        
            <label for="file">of selecteer een bestand:
            <input type="file" placeholder="file" name="file"></label>
                <!-- TAGS --> 
            <button name="submitButton" type="submit">uploaden</button>
        </form>';
        }

        //Laat de delete pagina zien
        else if(isset($_GET['Path_2']) && $_GET['Path_2'] == 'verwijder')
        {
           
            //header('Location: /admin/videobeheer');
        }

        //Laat de studentaanpassen pagina zien
        else if(isset($_GET['Path_2']) && $_GET['Path_2'] == 'edit' && isset($_GET['Path_3']) && $user->rank >= 2)
        {
            $videoID = $filter->sanatizeInput($_GET['Path_3'], 'int');

            $videoResult = $DB->Select("SELECT * FROM video 
                                        INNER JOIN vak_video 
                                        ON vak_video.video_id = video.video_id
                                        INNER JOIN gebruiker
                                        ON gebruiker.gebruiker_id = video.gebruiker_id
                                        WHERE video.video_id != ?", [$videoID]);
        }
    ?>
             
            </div>
        </div>
    </div>