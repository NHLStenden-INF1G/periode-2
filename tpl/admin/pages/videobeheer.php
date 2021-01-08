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
                    $uploadFolder = $_SERVER['DOCUMENT_ROOT'].'/uploads/video/';
                    $bestandsNaam = basename(sha1(time().$_FILES["file"]["tmp_name"]));
                    $uploadExtensie = '.'.explode(".", $_FILES["file"]["name"])[1];
                    $videoPath = $uploadFolder.$bestandsNaam.$uploadExtensie; 
                    
                    move_uploaded_file($_FILES["file"]["tmp_name"], $videoPath);

                    $videoPathDB = "/uploads/video/".$bestandsNaam.$uploadExtensie;
                    $videolengte = floor($videoParser->analyze($videoPath)['playtime_seconds']);

                    $DB->Insert("INSERT INTO video (gebruiker_id, titel, videopath, videolengte)
                      VALUES (?, ?, ?, ?);",[$user->id, $postTitel, $videoPathDB, $videolengte]);

                    $core->createThumbnail($videoPathDB, $videolengte);

                    $core->createVideoPreview($videoPathDB, $videolengte);

                    header('Location: /admin/videobeheer');
                    
                }
            }
            else {
                    $youtubeLink = $filter->sanatizeInput($_POST['ytlink'], 'url');
        
                    //Als het een youtube URL is
                    if(strpos($youtubeLink, "youtube")) {
                        $playbackID = explode('?v=', $_POST['ytlink'])[1];
                        $DB->Insert("INSERT INTO video (gebruiker_id, titel, playback_id) VALUES (?, ?, ?);",[$user->id, $postTitel, $playbackID]);
                        header('Location: /admin/videobeheer');
                    } else {
                        echo "Geen valide Youtube URL";
                    }
                
            }


            $videoID = $DB->InsertId();
            $DB->Insert("INSERT INTO video_vak (video_id, vak_id) VALUES (?, ?)", [$videoID, $vakKeuze]);

            foreach ($_POST['tagKeuze'] as $key => $value) {
                $DB->Insert("INSERT INTO tag_video (tag_id, video_id) VALUES (?, ?)", [$value, $videoID]);
            }
        }
        else {
            echo "Error: Geen link/video meegegeven.";
        }
    }
    else {
        echo "Error: Geen titel meegegeven.";
    }
}




if(isset($_POST["editSubmit"])) 
{
    if(!empty($_POST['titel'])) 
    {
            $vakKeuze = $filter->sanatizeInput($_POST["vakKeuze"], 'int');
            $postTitel =  $filter->sanatizeInput($_POST["titel"], 'string');
            $videoID =  $filter->sanatizeInput($_POST["videoID"], 'int');

            //Als het een bestand is
            if(isset($_FILES['file']['name'])) 
            {
                $videoAllowedTypes = array('video/mp4', 'video/webm', 'video/H264'); 
                //Is een toegestaan bestandstype
                if(in_array($_FILES["file"]["type"], $videoAllowedTypes) && $_FILES["file"]["size"] < 5000000000) 
                {
                    $videoPathSel = $DB->Select("SELECT videoPath FROM video WHERE video_id = ?", [$videoID])[0]['videoPath'];
                    $videoNaam = explode("/uploads/video/", explode(".", $videoPathSel)[0])[1];

                    
                    $uploadFolder = $_SERVER['DOCUMENT_ROOT'].'/uploads/video/';
                    $bestandsNaam = basename(sha1(time().$_FILES["file"]["tmp_name"]));
                    $uploadExtensie = '.'.explode(".", $_FILES["file"]["name"])[1];
                    $videoPathUpload = $uploadFolder.$bestandsNaam.$uploadExtensie; 
                    
                    //Video verwijderen
                    unlink($_SERVER['DOCUMENT_ROOT'].$videoPathSel);

                    //Thumb verwijderen
                    
                    unlink($_SERVER['DOCUMENT_ROOT'].'/uploads/thumbnails/'.$videoNaam.'.png');

                    //Preview verwijderen
                    unlink($_SERVER['DOCUMENT_ROOT'].'/uploads/previews/'.$videoNaam.'.gif');
                    move_uploaded_file($_FILES["file"]["tmp_name"], $videoPathUpload);

                    $videoPathDB = "/uploads/video/".$bestandsNaam.$uploadExtensie;
                    $videolengte = floor($videoParser->analyze($videoPathUpload)['playtime_seconds']);

                    $DB->Update("UPDATE video SET gebruiker_id = ?, titel = ?, videopath = ?, videolengte = ?
                                WHERE video_id = ?",[$user->id, $postTitel, $videoPathDB, $videolengte, $videoID]);

                    $core->createThumbnail($videoPathDB, $videolengte);

                    $core->createVideoPreview($videoPathDB, $videolengte);

                    header('Location: /admin/videobeheer');
                    
                }
            }
            else {
                    $youtubeLink = $filter->sanatizeInput($_POST['ytlink'], 'url');
        
                    //Als het een youtube URL is
                    if(strpos($youtubeLink, "youtube")) {
                        $playbackID = explode('?v=', $_POST['ytlink'])[1];
                        $DB->Insert("INSERT INTO video (gebruiker_id, titel, playback_id) VALUES (?, ?, ?);",[$user->id, $postTitel, $playbackID]);
                        header('Location: /admin/videobeheer');
                    } else {
                        echo "Geen valide Youtube URL";
                    }
                
            }


            $videoID = $DB->InsertId();
            $DB->Insert("INSERT INTO video_vak (video_id, vak_id) VALUES (?, ?)", [$videoID, $vakKeuze]);

            foreach ($_POST['tagKeuze'] as $key => $value) {
                $DB->Insert("INSERT INTO tag_video (tag_id, video_id) VALUES (?, ?)", [$value, $videoID]);
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
                                        INNER JOIN video_vak 
                                        ON video_vak.video_id = video.video_id
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
                    echo "<td>".$value['voornaam']." ".$value['achternaam']. "</td>";
                    echo "<td>".$value['titel']."</td>";
                    echo "<td>".$value['uploadDatum']."</td>";
                    echo "<td class='link' data-link='/admin/videobeheer/edit/".$value['video_id']."'>
                    <i class='fa fa-pencil-square-o' aria-hidden='true'></i>
                    </td>";
                    echo "<td class='link' data-link='/admin/videobeheer/verwijder/".$value['video_id']."'>
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
            $tagData = $DB->Select("SELECT tag.tag_id, tag.naam AS tagnaam, opleiding.* FROM tag INNER JOIN opleiding ON opleiding.opleiding_id = tag.opleiding_id");

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

            if(!empty($tagData))  {
                echo '<label for="vak">Tags:<select name="tagKeuze[]" multiple>';

                foreach($tagData as $key => $tagLijst) 
                { 

                    echo "<option value='{$tagLijst['tag_id']}'>{$tagLijst["tagnaam"]}</option>";
                }
               
                echo '</select></label><br>';
            }
          
            echo '<label for="titel">Videotitel:
            <input type="text" placeholder="Titel" name="titel"><br></label><br>
            
            <label for="ytlink">Upload hier je YT link (mag leeg zijn)
            <input type="url" placeholder="Youtubelink" name="ytlink"></label>
        
            <label for="file">of selecteer een bestand:
            <input type="file" placeholder="file" name="file"></label>
            <button name="submitButton" type="submit">uploaden</button>
        </form>';
        }

        //Laat de delete pagina zien
        else if(isset($_GET['Path_2']) && $_GET['Path_2'] == 'verwijder')
        {
            $videoID = $filter->sanatizeInput($_GET['Path_3'], 'int');

            //Verwijder van server
            $videoPath = $DB->Select("SELECT videoPath FROM video WHERE video_id = ?", [$videoID])[0]['videoPath'];
            $videoNaam = explode("/uploads/video/", explode(".", $videoPath)[0])[1];

            //Video verwijderen
            unlink($_SERVER['DOCUMENT_ROOT'].$videoPath);

            //Thumb verwijderen
            unlink($_SERVER['DOCUMENT_ROOT'].'/uploads/thumbnails/'.$videoNaam.'.png');

            //Preview verwijderen
            unlink($_SERVER['DOCUMENT_ROOT'].'/uploads/previews/'.$videoNaam.'.gif');
            //Verwijder van database
            $DB->Delete("DELETE FROM video WHERE video_id = ?", [$videoID]);

            header('Location: /admin/videobeheer');
        }

        //Laat de studentaanpassen pagina zien
        else if(isset($_GET['Path_2']) && $_GET['Path_2'] == 'edit' && isset($_GET['Path_3']) && $user->rank >= 2)
        {
            $videoID = $filter->sanatizeInput($_GET['Path_3'], 'int');

            $videoResult = @$DB->Select("SELECT * FROM video 
                                        INNER JOIN video_vak 
                                        ON video_vak.video_id = video.video_id
                                        INNER JOIN gebruiker
                                        ON gebruiker.gebruiker_id = video.gebruiker_id
                                        INNER JOIN tag_video
                                        ON tag_video.video_id = video.video_id
                                        WHERE video.video_id = ?
                                        LIMIT 1", [$videoID])[0];
               $videoData = $DB->Select("SELECT vak_id, vak_naam FROM vak");
               $tagResult = $DB->Select("SELECT * FROM tag"); 

            if(!empty($videoResult)){
                echo '<div class="sectionTitle">aanpassen</div>
                <form enctype="multipart/form-data" method="POST">';

        if(!empty($videoData))  {
            echo '<label for="vak">Vak:<select name="vakKeuze">';

            foreach($videoData as $key => $vakLijst) 
            { 
             if(in_array($videoResult['vak_id'], $vakLijst)) {
                 echo "<option class='optionSelected' value='{$vakLijst['vak_id']}' selected >{$vakLijst['vak_naam']} (geselecteerd)</option>";
             }
             
             else {
                 echo "<option value='{$vakLijst["vak_id"]}'>{$vakLijst["vak_naam"]}</option>";
             }
            }
           
            echo '</select></label><br>';
        }

        if(!empty($tagResult))  {
            echo '<label for="vak">Tags:<select name="tagKeuze[]" multiple>';

            foreach($tagResult as $key => $tagLijst) 
            { 
                 if(in_array($videoResult['tag_id'], $tagLijst)) {
                     echo "<option class='optionSelected' value='{$tagLijst['tag_id']}' selected >{$tagLijst['naam']} (geselecteerd)</option>";
                 }
                 
                 else {
                     echo "<option value='{$tagLijst['tag_id']}'>{$tagLijst["naam"]}</option>";
                 }
            }
           
            echo '</select></label><br>';
        }
      
        echo '<label for="titel">Videotitel:
        <input type="text" placeholder="Titel" name="titel" value="'.$videoResult['titel'].'"><br></label><br>
        
        <label for="ytlink">Upload hier je YT link (mag leeg zijn)
        <input type="url" placeholder="Youtubelink" name="ytlink" value="'.$videoResult['playback_id'].'"></label>
    
        <label for="file">of selecteer een bestand:
        <input type="file" placeholder="file" name="file"></label>
        <input type="hidden" name="videoID" value="'.$videoResult['video_id'].'">
        <button name="editSubmit" type="submit">aanpassen</button>
    </form>';
            }
               
        }
    ?>
             
            </div>
        </div>
    </div>