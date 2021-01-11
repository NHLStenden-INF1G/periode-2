<?php 
    if(isset($_POST['submitButton'])) {
        if(!empty($_POST['passwordOne'])) {
            if(!empty($_POST['passwordTwo'])) {
                if($_POST['passwordOne'] === $_POST['passwordTwo']) {
                    $passwordOne = $filter->sanatizeInput($_POST['passwordOne'], "string");
                    $passwordTwo = $filter->sanatizeInput($_POST['passwordTwo'], "string");
                    $passwordTwo =  password_hash($passwordTwo, PASSWORD_DEFAULT);
                    $DB->Update("UPDATE gebruiker SET wachtwoord = ? WHERE gebruiker_id = ?", [$passwordTwo, $user->id]);
                }
            }
        }
    }

?>
<div class="mainWrapper"> 
    <main> 
    
    <div class="userInfo" style="grid-row: 1;">
        <div class="sectionTitle">{subpageTitle}</div>  
    </div>
            <?php

if ($user->logged_in && isset($_GET['Path_1']) == $user->id && isset($_GET['Path_2']) == 'edit') {
    $this->Set("subpageTitle", "Mijn profiel aanpassen");

        echo "<form method='post'>
                <label>Wachtwoord: <input type='password' name='passwordOne' placeholder='Wachtwoord' required /></label><br />
                <label>Wachtwoord (herhaal): <input type='password' name='passwordTwo' placeholder='Wachtwoord' required /></label><br />
                <button type='submit' name='submitButton'>wachtwoord veranderen</button>
                <span class='button link' data-link='/profiel/{$user->id}'>annuleren</span>
            </form>";
}
else if(isset($_GET['Path_1']) && !isset($_GET['Path_2'])){

    if($user->logged_in && $_GET['Path_1'] == $user->id) {
        $gebruikerID = $user->id;

        $editButton = "<button class='link star' data-link='/profiel/{$gebruikerID}/edit'><i class='fa fa-pencil'></i></button>";
        $voortgangResult = $DB->Select("SELECT  video.*, voortgang.*, gebruiker.voornaam, gebruiker.achternaam
                                        FROM voortgang 
                                        INNER JOIN video
                                        ON video.video_id = voortgang.video_id
                                        INNER JOIN gebruiker
                                        ON gebruiker.gebruiker_id = video.gebruiker_id
                                        WHERE voortgang.gebruiker_id = ?
                                        ORDER BY voortgang.datum DESC
                                        LIMIT 6", [$gebruikerID]);

          echo '<div class="Aanbevolen">
                    <div class="sectionTitle">recent bekeken video\'s</div>
                    <div class="thumbnailContainer">';
    if(!empty($voortgangResult)) {
     
        foreach ($voortgangResult as $key => $value) {

            $value['videoTags'] = $DB->Select("SELECT * FROM tag_video 
                                                INNER JOIN tag 
                                                    ON tag.tag_id = tag_video.tag_id
                                                WHERE tag_video.video_id = ?", [$value['video_id']]);

            if(!empty($value['timestamp'])) {
                $percentage = $videoTools->getProgress($value['timestamp'], $value['videoLengte']);

            }
            $value['rating'] = $DB->Select("SELECT AVG(rating) AS rating FROM beoordeling WHERE video_id = ?",[$value['video_id']])[0]['rating'];
        
            echo "<div class='videoThumbBlock'>
                <div class='videoThumbBlockRand'></div>
                    <div class='videoThumb link' data-link='/watch/{$value['video_id']}' id='{$videoTools->getVideoName($value['videoPath'])}'>
                    <img class='videoThumbImg' src='{uploadsFolder}/{$videoTools->getThumbnail($value['videoPath'])}' id='thumb-{$videoTools->getVideoName($value['videoPath'])}'>
                    <div class='videoThumbTags'> ";

                    foreach ($value['videoTags'] as $key1 => $value1) {
                        echo "<li class='videoTag link' data-link=''>#{$value1['naam']}</li>";
                    }

                    echo "</div>
                    <div class='videoDetailContainer'>
                        <div class='videoDetailsTitle'><strong style='font-size: clamp(27px, 0.3vw, 30px);'>{$value['titel']}</strong></div>
                        <div class='videoThumbDocent'>{$value['voornaam']} {$value['achternaam']}</div>
                        <div class='videoThumbDetails'>{$value['uploadDatum']}
                        <div style='margin-left: -0.4vw; margin-bottom: 0.5vw;'>
                            ".$videoTools->getRating($value['rating'], $value['video_id'])."</div>
                            <p>(".gmdate("H:i:s", $value['videoLengte']).")</p>

                        </div>
                    </div>
                    <div class='videoProgress' style='grid-row: 5; grid-column: 1; background-color: red; width: {$percentage}%;'></div>
                </div>
            </div>";
        }
    } else {
        echo 'Nog geen video\'s bekeken!';
    }
        echo '</div>
        </div>';       
      
    }
    else {
        $editButton = "";
        $gebruikerID =  $filter->sanatizeInput(intval($_GET['Path_1']), "int");
    }

    $gebruikerResult = $DB->Select("SELECT * FROM gebruiker WHERE gebruiker_id = ? LIMIT 1", [$gebruikerID]);

    if(!empty($gebruikerResult)) {
        $userlevel =  $user->userLevel($gebruikerResult[0]['gebruiker_id']);
        $this->Set("subpageTitle", "Profiel van {$gebruikerResult[0]['voornaam']} {$gebruikerResult[0]['achternaam']} ({$userlevel}) {$editButton}");

        if($gebruikerResult[0]['level'] >= 2) {
            $uploadResult = $DB->Select("SELECT * FROM video 
                                        INNER JOIN gebruiker 
                                            ON gebruiker.gebruiker_id = video.gebruiker_id
                                        WHERE video.gebruiker_id = ?
                                        ORDER BY video.uploaddatum DESC 
                                        LIMIT 6
                                        ", [$gebruikerID]);

            echo '<div style="grid-row: 3;">
            <div class="sectionTitle">geuploade video\'s</div>
            <div class="thumbnailContainer">';
            if(!empty($uploadResult)){

           
            foreach ($uploadResult as $key => $value) {

            $value['videoTags'] = $DB->Select("SELECT * FROM tag_video 
                                                INNER JOIN tag 
                                                    ON tag.tag_id = tag_video.tag_id
                                                WHERE tag_video.video_id = ?", [$value['video_id']]);

            if(!empty($value['timestamp'])) {
                $percentage = $videoTools->getProgress($value['timestamp'], $value['videoLengte']);

            }
            else {
                $percentage = 0;
            }

            $value['rating'] = $DB->Select("SELECT AVG(rating) AS rating FROM beoordeling WHERE video_id = ?",[$value['video_id']])[0]['rating'];

            echo "<div class='videoThumbBlock'>
                <div class='videoThumbBlockRand'></div>
                    <div class='videoThumb link' data-link='/watch/{$value['video_id']}' id='{$videoTools->getVideoName($value['videoPath'])}'>
                    <img class='videoThumbImg' src='{uploadsFolder}/{$videoTools->getThumbnail($value['videoPath'])}' id='thumb-{$videoTools->getVideoName($value['videoPath'])}'>
                    <div class='videoThumbTags'> ";

                    foreach ($value['videoTags'] as $key1 => $value1) {
                        echo "<li class='videoTag link' data-link=''>#{$value1['naam']}</li>";
                    }

                    echo "</div>
                    <div class='videoDetailContainer'>
                        <div class='videoDetailsTitle'><strong style='font-size: clamp(27px, 0.3vw, 30px);'>{$value['titel']}</strong></div>
                        <div class='videoThumbDocent'>{$value['voornaam']} {$value['achternaam']}</div>
                        <div class='videoThumbDetails'>{$value['uploadDatum']}
                        <div style='margin-left: -0.4vw; margin-bottom: 0.5vw;'>
                            ".$videoTools->getRating($value['rating'], $value['video_id'])."</div>
                            <p>(".gmdate("H:i:s", $value['videoLengte']).")</p>

                        </div>
                    </div>
                    <div class='videoProgress' style='grid-row: 5; grid-column: 1; background-color: red; width: {$percentage}%;'></div>
                </div>
            </div>";

            }
        }
        else {
            echo 'Deze docent heeft nog geen video\'s geuploaded';
        }
        echo '</div>
        </div>';
            }
    }   
    else {
        $this->Set("subpageTitle", "Profiel niet gevonden!");
    }
}

?>
    </main>     
</div>