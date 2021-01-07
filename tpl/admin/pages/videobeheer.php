<?php

if(isset($_POST["submitButton"])) 
{

    if(!empty($_POST['titel'])) 
    {
        $vakKeuze = $filter->sanatizeInput($_POST["vakKeuze"], 'int');
        $postTitel =  $filter->sanatizeInput($_POST["titel"], 'string');

        if(isset($_POST['ytlink'])) {
            $youtubeLink = $filter->sanatizeInput($_POST['ytlink'], 'url');

            //Als het een youtube URL is
            if(strpos($youtubeLink, "youtube")) {
                $playbackID = explode('?v=', $_POST['ytlink'])[1];
                $DB->Insert("INSERT INTO video (gebruiker_id, titel, playback_id) VALUES (?, ?, ?);",[$user->id, $postTitel, $playbackID]);
            } else {
                echo "Geen valide Youtube URL";
            }
        }


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
                $videolengte = 69;

                move_uploaded_file($_FILES["file"]["tmp_name"], $videoPath);

                $videoPath = "/uploads/".$bestandsNaam.$uploadExtensie;
                $DB->Insert("INSERT INTO video (gebruiker_id, titel, videopath, videolengte)
                VALUES (?, ?, ?, ?);",[$user->id, $postTitel, $videoPath, $videolengte]);
            }
        }


        $videoID = $DB->InsertId();
        $DB->Insert("INSERT INTO vak_video (video_id, vak_id) VALUES (?, ?)", [$videoID, $vakKeuze]);
        
    }
}
?>


<form enctype="multipart/form-data" method="POST">
    <label for="vak">Vak:
    <select name="vakKeuze">
        <?php
        $sql = "SELECT vak_id, vak_naam FROM vak";
        $videoData = $DB->Select("SELECT vak_id, vak_naam FROM vak");
        if(!empty($videoData))  {
            foreach($videoData as $key => $vakLijst) 
            { 
                echo "<option value='{$vakLijst["vak_id"]}'>{$vakLijst["vak_naam"]}</option>";
            }
        }
        ?>
    </select></label><br>
    
    <label for="titel">Videotitel:
    <input type="text" placeholder="Titel" name="titel"><br></label><br>
    
    <label for="ytlink">Upload hier je YT link
    <input type="url" placeholder="Youtubelink" name="ytlink"></label>

    <label for="file">of selecteer een bestand:
    <input type="file" placeholder="file" name="file"></label>

    <button name="submitButton" type="submit">Submit</button>
</form>