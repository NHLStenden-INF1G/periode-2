<?php
    $this->Set("pageTitle", $this->Get("ADMIN_VIDEOBEHEER"));

if(isset($_POST["submitButton"])) 
{
    if(!empty($_POST['titel'])) 
    {
        if(!empty($_POST['vakKeuze']) && $_POST['vakKeuze'] != 'ERROR')
        {
            if(!empty($_POST['titel'] && !empty($_FILES["file"]["name"])))
            {

                $vakKeuze = $filter->sanatizeInput($_POST["vakKeuze"], 'int');
                $postTitel =  $filter->sanatizeInput($_POST["titel"], 'string');
                //Als het een bestand is
                if(isset($_FILES['file']['name'])) 
                {
                    $videoAllowedTypes = array('video/mp4'); 
                    //Is een toegestaan bestandstype
                    if(in_array($_FILES["file"]["type"], $videoAllowedTypes) && $_FILES["file"]["size"] < 5000000000) 
                    {
                        $uploadFolder = $_SERVER['DOCUMENT_ROOT'].'/uploads/video/';
                        $bestandsNaam = basename(sha1(time().$_FILES["file"]["tmp_name"]));
                        $uploadExtensie = '.mp4';
                        $videoPath = $uploadFolder.$bestandsNaam.$uploadExtensie; 
                        
                        move_uploaded_file($_FILES["file"]["tmp_name"], $videoPath);

                        $videoPathDB = "/uploads/video/".$bestandsNaam.$uploadExtensie;
                        $videolengte = floor($videoParser->analyze($videoPath)['playtime_seconds']);

                        $DB->Insert("INSERT INTO video (gebruiker_id, titel, videopath, videolengte)
                        VALUES (?, ?, ?, ?);",[$user->id, $postTitel, $videoPathDB, $videolengte]);

                        $videoTools->createThumbnail($videoPathDB, $videolengte);

                        header('Location: /admin/videobeheer');
                        
                    }
                }

                $videoID = $DB->InsertId();
                $DB->Insert("INSERT INTO video_vak (video_id, vak_id) VALUES (?, ?)", [$videoID, $vakKeuze]);

                foreach ($_POST['tagKeuze'] as $key => $value) {
                    $DB->Insert("INSERT INTO tag_video (tag_id, video_id) VALUES (?, ?)", [$value, $videoID]);
                }
            }
            else {
                echo "Error: {VIDEOBEHEER_UPLOADEN_BESTAND_KIEZEN_GEEN}";
            }               
        }
        else 
        {
            echo "Error: {VAK_GEEN}";
        }
    }
    else {
        echo "Error:  {VIDEOBEHEER_UPLOADEN_BESTAND_TITEL_GEEN}";
    }
}




if(isset($_POST["editSubmit"])) 
{
    if(!empty($_POST['titel'])) 
    {
        if(!empty($_POST['vakKeuze']) && $_POST['vakKeuze'] != 'ERROR')
        {
            $vakKeuze = $filter->sanatizeInput($_POST["vakKeuze"], 'int');
            $postTitel =  $filter->sanatizeInput($_POST["titel"], 'string');
            $videoID =  $filter->sanatizeInput($_POST["videoID"], 'int');

            //Als het een bestand is
            if(!empty($_FILES['file']['name'])) 
            {
                $videoAllowedTypes = array('video/mp4', 'video/webm', 'video/H264'); 
                //Is een toegestaan bestandstype
                if(in_array($_FILES["file"]["type"], $videoAllowedTypes) && $_FILES["file"]["size"] < 5000000000) 
                {
                    $videoPathSel = $DB->Select("SELECT videoPath FROM video WHERE video_id = ?", [$videoID])[0]['videoPath'];
                    $videoNaam = explode("/uploads/video/", explode(".", $videoPathSel)[0])[1];

                    
                    $uploadFolder = $_SERVER['DOCUMENT_ROOT'].'/uploads/video/';
                    $bestandsNaam = basename(sha1(time().$_FILES["file"]["tmp_name"]));
                    $uploadExtensie = '.mp4';
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

                    $videoTools->createThumbnail($videoPathDB, $videolengte);

                    header('Location: /admin/videobeheer');
                    
                }
            }
            else {
                $DB->Update("UPDATE video SET gebruiker_id = ?, titel = ? 
                            WHERE video_id = ?",[$user->id, $postTitel, $videoID]);
                header('Location: /admin/videobeheer');
            }

            $DB->Delete("DELETE FROM video_vak WHERE video_id = ?", [$videoID]);
            $DB->Insert("INSERT INTO video_vak (video_id, vak_id) VALUES (?, ?)", [$videoID, $vakKeuze]);

            $DB->Delete("DELETE FROM tag_video WHERE video_id = ?", [$videoID]);

            foreach ($_POST['tagKeuze'] as $key => $value) {
                $DB->Insert("INSERT INTO tag_video (tag_id, video_id) VALUES (?, ?)", [$value, $videoID]);
            }
        }
        else 
        {
            echo "Error: {VAK_GEEN}";
        }
    }
    else {
        echo "Error:  {VIDEOBEHEER_UPLOADEN_BESTAND_TITEL_GEEN}";
    }
}
?>

<div class="spotlightVideo">
        <div class="adminView">
            <div class="adminViewRand"></div>
            <div class="adminContent"></div>
            <div class="adminOptions">
            <div class="sectionTitle">{ADMIN_VIDEOBEHEER}</div>
                <div class="sectionTitle link" data-link="/admin/videobeheer/upload">> {BEHEER_NAV_UPLOADEN}</div>
                <div class="sectionTitle link" data-link="/admin/videobeheer">> {BEHEER_NAV_TERUG_OVERZICHT}</div>
                <div class="sectionTitle link" data-link="/admin/start">> {BEHEER_NAV_TERUG_HOOFDMENU}</div>
            </div>
            <div class="adminTableView">
        <?php 

        //Laat de weergave pagina zien
        if(!isset($_GET['Path_2']) && !isset($_GET['Path_3']))
        {
            $videoResult = $DB->Select("SELECT video.*, gebruiker.* FROM video 
                                        LEFT JOIN video_vak 
                                        ON video_vak.video_id = video.video_id
                                        INNER JOIN gebruiker
                                        ON gebruiker.gebruiker_id = video.gebruiker_id");

            echo '<div class="sectionTitle">{BEHEER_OVERZICHT}</div><table>
                    <thead>
                        <tr>
                            <th>{VIDEOBEHEER_DOCENT}</th>
                            <th>{VIDEOBEHEER_TITEL}</th>
                            <th>{BEHEER_DATUM}</th>
                            <th><i class="fa fa-pencil-square-o" aria-hidden="true"></i></th>
                            <th><i class="fa fa-times" aria-hidden="true"></i></th>
                        </tr>
                    </thead>
                    <tbody>';
            foreach($videoResult as $key => $value) 
            {
                echo "<tr>";
                    echo "<td>".$value['voornaam']." ".$value['achternaam']. "</td>";
                    echo "<td class='link' data-link='/watch/".$value['video_id']."'>".$value['titel']."</td>";
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

            $tagData = $DB->Select("SELECT * FROM tag");
            echo '<div class="sectionTitle">upload</div>
                    <form enctype="multipart/form-data" method="POST">';
            echo '<label>{VIDEOBEHEER_UPLOADEN_VAK}:<select name="vakKeuze">';

            if(!empty($videoData))  {

                foreach($videoData as $key => $vakLijst) 
                { 
                    echo "<option value='{$vakLijst["vak_id"]}'>{$vakLijst["vak_naam"]}</option>";
                }
               
            }
            else 
            {
                echo "<option value='ERROR'>{VAK_GEEN}</option>";
            }
            echo '</select></label><br>';

            if(!empty($tagData))  {
                echo '<label>Tags:<select name="tagKeuze[]" multiple>';

                foreach($tagData as $key => $tagLijst) 
                { 

                    echo "<option value='{$tagLijst['tag_id']}'>{$tagLijst["naam"]}</option>";
                }
               
                echo '</select></label><br>';
            }
          
            echo '<label for="titel">{VIDEOBEHEER_TITEL}:
            <input type="text" placeholder="{VIDEOBEHEER_TITEL}" name="titel"><br></label><br>

            <label for="file">{VIDEOBEHEER_UPLOADEN_BESTAND}
            <input type="file" placeholder="file" name="file"></label>
            <button name="submitButton" type="submit">upload</button>
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
                                        LEFT JOIN video_vak 
                                            ON video_vak.video_id = video.video_id
                                        INNER JOIN gebruiker
                                            ON gebruiker.gebruiker_id = video.gebruiker_id
                                        WHERE video.video_id = ?", [$videoID])[0];
            $videoResult['videoTags'] = $DB->Select("SELECT tag_id FROM tag_video WHERE video_id = ?", [$videoID]);

            $videoData = $DB->Select("SELECT vak_id, vak_naam FROM vak");
            $tagResult = $DB->Select("SELECT * FROM tag"); 

            if(!empty($videoResult)){
                echo '<div class="sectionTitle">{VIDEOBEHEER_AANPASSEN_TITEL}</div>
                <form enctype="multipart/form-data" method="POST">';

        echo '<label for="vak">{VIDEOBEHEER_UPLOADEN_VAK}:<select name="vakKeuze">';

        if(!empty($videoData))  {

            foreach($videoData as $key => $vakLijst) 
            { 
             if(in_array($videoResult['vak_id'], $vakLijst)) {
                 echo "<option class='optionSelected' value='{$vakLijst['vak_id']}' selected >{$vakLijst['vak_naam']} (geselecteerd)</option>";
             }
             
             else {
                 echo "<option value='{$vakLijst["vak_id"]}'>{$vakLijst["vak_naam"]}</option>";
             }
            }
           
        }
        else 
        {
            echo "<option value='ERROR'>{VAK_GEEN}</option>";
        }

        echo '</select></label><br>';

        if(!empty($tagResult))  {
            echo '<label for="vak">Tags:<select name="tagKeuze[]" multiple>';
            $videoTags = [];

            foreach ($videoResult['videoTags'] as $key1 => $value) {
                $videoTags[$value['tag_id']] = 1;
            }

            foreach($tagResult as $key => $tagLijst) 
            { 
                 if($videoTags[$tagLijst['tag_id']] == 1) {
                    echo "<option class='optionSelected' value='{$tagLijst['tag_id']}' selected >{$tagLijst['naam']} (geselecteerd)</option>";
                }
                 else {
                    echo "<option value='{$tagLijst['tag_id']}'>{$tagLijst["naam"]}</option>";
                 }
            }
           
            echo '</select></label><br>';
        }
      
            echo '<label for="titel">{VIDEOBEHEER_UPLOADEN_VIDEOTITEL}:
            <input type="text" placeholder="Titel" name="titel" value="'.$videoResult['titel'].'"><br></label><br>
                <label for="file">{VIDEOBEHEER_UPLOADEN_BESTAND}
                <input type="file" placeholder="file" name="file"></label>
                <input type="hidden" name="videoID" value="'.$videoResult['video_id'].'">
                <button name="editSubmit" type="submit">{VIDEOBEHEER_AANPASSEN_TITEL}</button>
            </form>';

            }     
        }
    ?>
             
            </div>
        </div>
    </div>